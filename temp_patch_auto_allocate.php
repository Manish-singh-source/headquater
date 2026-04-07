<?php
$path = __DIR__ . '/app/Services/WarehouseAllocationService.php';
$content = file_get_contents($path);
$pattern = '/    public function autoAllocateStock\(\$sku, \$requiredQuantity, \$salesOrderId, \$salesOrderProductId, \$warehouseIds = null\)\r?\n    \{.*?\r?\n    \}\r?\n\r?\n    \/\*\*\r?\n     \* Allocate stock for entire sales order/s';
$replacement = <<<'PHP'
    public function autoAllocateStock($sku, $requiredQuantity, $salesOrderId, $salesOrderProductId, $warehouseIds = null)
    {
        DB::beginTransaction();
        try {
            $allocations = [];
            $orderProduct = SalesOrderProduct::findOrFail($salesOrderProductId);
            $remainingQuantity = $requiredQuantity;
            $sequence = 1;
            $totalAllocated = 0;
            $firstAllocatedWarehouseStockId = null;

            // Get all active warehouses with positive stock for this SKU.
            $warehouseStocksQuery = WarehouseStock::where('sku', $sku)
                ->where('available_quantity', '>', 0)
                ->with('warehouse')
                ->orderBy('warehouse_id');

            if ($warehouseIds && is_array($warehouseIds)) {
                $warehouseStocksQuery->whereIn('warehouse_id', $warehouseIds);
            } else {
                $warehouseStocksQuery->whereHas('warehouse', function ($query) {
                    $query->where('status', '1');
                });
            }

            $warehouseStocks = $warehouseStocksQuery->get();

            foreach ($warehouseStocks as $warehouseStock) {
                if ($remainingQuantity <= 0) {
                    break;
                }

                $availableInWarehouse = $warehouseStock->available_quantity;
                if ($availableInWarehouse <= 0) {
                    continue;
                }

                $allocateQty = min($availableInWarehouse, $remainingQuantity);

                if ($allocateQty > 0) {
                    $allocation = WarehouseAllocation::create([
                        'sales_order_id' => $salesOrderId,
                        'sales_order_product_id' => $salesOrderProductId,
                        'warehouse_id' => $warehouseStock->warehouse_id,
                        'customer_id' => $orderProduct->customer_id,
                        'sku' => $sku,
                        'allocated_quantity' => $allocateQty,
                        'box_count' => 0,
                        'sequence' => $sequence,
                        'status' => 'allocated',
                        'notes' => "Auto-allocated {$allocateQty} units from warehouse {$warehouseStock->warehouse->name}",
                    ]);

                    $warehouseStock->available_quantity -= $allocateQty;
                    $warehouseStock->block_quantity += $allocateQty;
                    $warehouseStock->save();

                    activity()
                        ->performedOn($allocation)
                        ->causedBy(Auth::user())
                        ->withProperties([
                            'warehouse_id' => $warehouseStock->warehouse_id,
                            'warehouse_name' => $warehouseStock->warehouse->name,
                            'sku' => $sku,
                            'allocated_quantity' => $allocateQty,
                            'sequence' => $sequence,
                        ])
                        ->log("Stock allocated from warehouse {$warehouseStock->warehouse->name}");

                    $allocations[] = [
                        'warehouse_id' => $warehouseStock->warehouse_id,
                        'warehouse_name' => $warehouseStock->warehouse->name,
                        'allocated_quantity' => $allocateQty,
                        'sequence' => $sequence,
                        'status' => 'allocated',
                    ];

                    $totalAllocated += $allocateQty;
                    $remainingQuantity -= $allocateQty;

                    if ($firstAllocatedWarehouseStockId === null) {
                        $firstAllocatedWarehouseStockId = $warehouseStock->id;
                    }

                    $sequence++;
                }
            }

            // If some quantity still needs PO, create one pending tracking row as well.
            if ($remainingQuantity > 0) {
                $fallbackWarehouseStock = WarehouseStock::with('warehouse')
                    ->where('sku', $sku)
                    ->when($warehouseIds && is_array($warehouseIds), function ($query) use ($warehouseIds) {
                        $query->whereIn('warehouse_id', $warehouseIds);
                    }, function ($query) {
                        $query->whereHas('warehouse', function ($warehouseQuery) {
                            $warehouseQuery->where('status', '1');
                        });
                    })
                    ->orderBy('warehouse_id')
                    ->first();

                if ($fallbackWarehouseStock) {
                    $shortageAllocation = WarehouseAllocation::create([
                        'sales_order_id' => $salesOrderId,
                        'sales_order_product_id' => $salesOrderProductId,
                        'warehouse_id' => $fallbackWarehouseStock->warehouse_id,
                        'customer_id' => $orderProduct->customer_id,
                        'sku' => $sku,
                        'allocated_quantity' => 0,
                        'box_count' => 0,
                        'sequence' => $sequence,
                        'status' => 'pending',
                        'notes' => "Purchase order required for {$remainingQuantity} units",
                    ]);

                    $allocations[] = [
                        'warehouse_id' => $fallbackWarehouseStock->warehouse_id,
                        'warehouse_name' => $fallbackWarehouseStock->warehouse->name ?? 'N/A',
                        'allocated_quantity' => 0,
                        'sequence' => $sequence,
                        'status' => 'pending',
                    ];

                    activity()
                        ->performedOn($shortageAllocation)
                        ->causedBy(Auth::user())
                        ->withProperties([
                            'warehouse_id' => $fallbackWarehouseStock->warehouse_id,
                            'warehouse_name' => $fallbackWarehouseStock->warehouse->name ?? 'N/A',
                            'sku' => $sku,
                            'pending_quantity' => $remainingQuantity,
                            'sequence' => $sequence,
                        ])
                        ->log("Purchase order pending for warehouse allocation tracking");
                }
            }

            if ($firstAllocatedWarehouseStockId !== null) {
                $orderProduct->warehouse_stock_id = $firstAllocatedWarehouseStockId;
            }
            $orderProduct->purchase_ordered_quantity = $remainingQuantity;
            $orderProduct->save();

            DB::commit();

            $warehouseCount = max(0, $sequence - 1);

            return [
                'success' => true,
                'sku' => $sku,
                'required_quantity' => $requiredQuantity,
                'total_allocated' => $totalAllocated,
                'pending_quantity' => $remainingQuantity,
                'need_purchase' => $remainingQuantity > 0,
                'allocations' => $allocations,
                'message' => $remainingQuantity > 0
                    ? "Partially allocated. {$remainingQuantity} units need to be purchased."
                    : "Fully allocated from {$warehouseCount} warehouse(s).",
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Warehouse allocation failed: ' . $e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'sku' => $sku,
                'required_quantity' => $requiredQuantity,
            ];
        }
    }

    /**
     * Allocate stock for entire sales order
PHP;
$newContent = preg_replace($pattern, $replacement, $content, 1, $count);
if ($count !== 1) {
    fwrite(STDERR, "Failed to replace autoAllocateStock method\n");
    exit(1);
}
file_put_contents($path, $newContent);
