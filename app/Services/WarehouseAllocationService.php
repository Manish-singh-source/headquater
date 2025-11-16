<?php

namespace App\Services;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderProduct;
use App\Models\SalesOrder;
use App\Models\SalesOrderProduct;
use App\Models\Warehouse;
use App\Models\WarehouseAllocation;
use App\Models\WarehouseStock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WarehouseAllocationService
{
    /**
     * Auto-allocate stock from multiple warehouses for a given SKU and quantity
     *
     * @param  string  $sku
     * @param  int  $requiredQuantity
     * @param  int  $salesOrderId
     * @param  int  $salesOrderProductId
     * @param  array  $warehouseIds  (optional) - specific warehouses to check, otherwise all active warehouses
     * @return array
     */
    public function autoAllocateStock($sku, $requiredQuantity, $salesOrderId, $salesOrderProductId, $warehouseIds = null)
    {
        DB::beginTransaction();
        try {
            $allocations = [];
            $remainingQuantity = $requiredQuantity;
            $sequence = 1;
            $totalAllocated = 0;

            // Get all active warehouses with stock for this SKU
            $warehouseStocksQuery = WarehouseStock::where('sku', $sku)
                ->where('available_quantity', '>', 0)
                ->with('warehouse')
                ->orderBy('warehouse_id'); // You can change ordering logic (FIFO, priority, etc.)

            // If specific warehouses provided, filter by them
            if ($warehouseIds && is_array($warehouseIds)) {
                $warehouseStocksQuery->whereIn('warehouse_id', $warehouseIds);
            } else {
                // Only get active warehouses
                $warehouseStocksQuery->whereHas('warehouse', function ($query) {
                    $query->where('status', '1');
                });
            }

            $warehouseStocks = $warehouseStocksQuery->get();

            // Iterate through warehouses and allocate stock
            foreach ($warehouseStocks as $warehouseStock) {
                if ($remainingQuantity <= 0) {
                    break;
                }

                $availableInWarehouse = $warehouseStock->available_quantity;

                // Skip if no stock available in this warehouse
                if ($availableInWarehouse <= 0) {
                    continue;
                }

                $allocateQty = min($availableInWarehouse, $remainingQuantity);

                // Create allocation record only if allocating quantity > 0
                if ($allocateQty > 0) {

                    // update sales order products warehouse_stock_id
                    // $salesOrderProduct = SalesOrderProduct::find($salesOrderProductId);
                    // $salesOrderProduct->warehouse_stock_id = $warehouseStock->warehouse_id;
                    // $salesOrderProduct->save();

                    $allocation = WarehouseAllocation::create([
                        'sales_order_id' => $salesOrderId,
                        'sales_order_product_id' => $salesOrderProductId,
                        'warehouse_id' => $warehouseStock->warehouse_id,
                        'sku' => $sku,
                        'allocated_quantity' => $allocateQty,
                        'sequence' => $sequence,
                        'status' => 'allocated',
                        'notes' => "Auto-allocated {$allocateQty} units from warehouse {$warehouseStock->warehouse->name}",
                    ]);

                    // Update warehouse stock - move from available to blocked
                    $warehouseStock->available_quantity -= $allocateQty;
                    $warehouseStock->block_quantity += $allocateQty;
                    $warehouseStock->save();

                    // Log activity
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
                    $sequence++;
                }
            }

            DB::commit();

            $warehouseCount = $sequence - 1;

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
            Log::error('Warehouse allocation failed: '.$e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'sku' => $sku,
                'required_quantity' => $requiredQuantity,
            ];
        }
    }

    /**
     * Allocate stock for entire sales order (multiple SKUs)
     *
     * @param  int  $salesOrderId
     * @return array
     */
    public function allocateSalesOrder($salesOrderId)
    {
        DB::beginTransaction();
        try {
            $salesOrder = SalesOrder::with('orderedProducts')->findOrFail($salesOrderId);
            $allocationResults = [];
            $needsPurchaseOrder = false;
            $purchaseOrderItems = [];

            foreach ($salesOrder->orderedProducts as $orderProduct) {
                $result = $this->autoAllocateStock(
                    $orderProduct->sku,
                    $orderProduct->ordered_quantity,
                    $salesOrderId,
                    $orderProduct->id
                );

                $allocationResults[] = $result;

                // If stock is insufficient, mark for purchase order
                if ($result['need_purchase']) {
                    $needsPurchaseOrder = true;
                    $purchaseOrderItems[] = [
                        'sku' => $orderProduct->sku,
                        'quantity_needed' => $result['pending_quantity'],
                        'sales_order_product_id' => $orderProduct->id,
                    ];
                }
            }

            // Update sales order status
            if ($needsPurchaseOrder) {
                $salesOrder->status = 'blocked';
                $salesOrder->save();
            } else {
                $salesOrder->status = 'pending';
                $salesOrder->save();
            }

            DB::commit();

            // Log activity
            activity()
                ->performedOn($salesOrder)
                ->causedBy(Auth::user())
                ->withProperties([
                    'allocations' => $allocationResults,
                    'needs_purchase' => $needsPurchaseOrder,
                ])
                ->log("Multi-warehouse allocation completed for Sales Order #{$salesOrderId}");

            return [
                'success' => true,
                'sales_order_id' => $salesOrderId,
                'allocations' => $allocationResults,
                'needs_purchase_order' => $needsPurchaseOrder,
                'purchase_order_items' => $purchaseOrderItems,
                'message' => $needsPurchaseOrder
                    ? 'Order partially allocated. Purchase order required for remaining items.'
                    : 'Order fully allocated from available warehouse stock.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Sales order allocation failed: '.$e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'sales_order_id' => $salesOrderId,
            ];
        }
    }

    /**
     * Create purchase order for items that need to be purchased
     *
     * @param  int  $salesOrderId
     * @param  array  $purchaseItems
     * @param  int  $vendorId
     * @return array
     */
    public function createPurchaseOrderForShortage($salesOrderId, $purchaseItems, $vendorId)
    {
        DB::beginTransaction();
        try {
            $salesOrder = SalesOrder::findOrFail($salesOrderId);

            $purchaseOrder = PurchaseOrder::create([
                'sales_order_id' => $salesOrderId,
                'warehouse_id' => $salesOrder->warehouse_id,
                'customer_group_id' => $salesOrder->customer_group_id,
                'vendor_id' => $vendorId,
                'status' => 'pending',
            ]);

            foreach ($purchaseItems as $item) {
                $salesOrderProduct = SalesOrderProduct::find($item['sales_order_product_id']);

                PurchaseOrderProduct::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'sales_order_id' => $salesOrderId,
                    'sales_order_product_id' => $item['sales_order_product_id'],
                    'product_id' => $salesOrderProduct->product_id,
                    'sku' => $item['sku'],
                    'vendor_code' => $salesOrderProduct->vendor_code,
                    'ordered_quantity' => $item['quantity_needed'],
                ]);
            }

            DB::commit();

            // Log activity
            activity()
                ->performedOn($purchaseOrder)
                ->causedBy(Auth::user())
                ->withProperties(['items' => $purchaseItems])
                ->log("Purchase Order #{$purchaseOrder->id} created for stock shortage");

            return [
                'success' => true,
                'purchase_order_id' => $purchaseOrder->id,
                'items' => $purchaseItems,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Purchase order creation failed: '.$e->getMessage());

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get allocation breakdown for a sales order
     *
     * @param  int  $salesOrderId
     * @return array
     */
    public function getAllocationBreakdown($salesOrderId)
    {
        $allocations = WarehouseAllocation::where('sales_order_id', $salesOrderId)
            ->with(['warehouse', 'product', 'salesOrderProduct'])
            ->orderBy('sku')
            ->orderBy('sequence')
            ->get();

        $breakdown = [];

        foreach ($allocations->groupBy('sku') as $sku => $skuAllocations) {
            $breakdown[$sku] = [
                'sku' => $sku,
                'product_name' => $skuAllocations->first()->product->brand_title ?? 'N/A',
                'total_allocated' => $skuAllocations->sum('allocated_quantity'),
                'warehouses' => $skuAllocations->map(function ($allocation) {
                    return [
                        'warehouse_id' => $allocation->warehouse_id,
                        'warehouse_name' => $allocation->warehouse->name,
                        'allocated_quantity' => $allocation->allocated_quantity,
                        'sequence' => $allocation->sequence,
                        'status' => $allocation->status,
                    ];
                })->toArray(),
            ];
        }

        return $breakdown;
    }
}
