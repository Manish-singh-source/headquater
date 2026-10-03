<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceDetails;
use App\Models\Product;
use App\Models\SalesOrderProduct;
use App\Models\Warehouse;
use App\Models\WarehouseAllocation;
use App\Models\WarehouseStock;
use App\Models\WarehouseStockLog;
use DomainException;

class InvoiceStockReturnService
{
    public function returnQuantity(
        Invoice $invoice,
        InvoiceDetails $detail,
        float $quantity,
        Warehouse $destinationWarehouse,
        ?string $remark = null,
    ): SalesOrderProduct {
        if (! $invoice->sales_order_id || ! $detail->sales_order_product_id || ! $detail->product_id) {
            throw new DomainException('Only sales order product lines can be returned to warehouse stock.');
        }

        if ($quantity <= 0) {
            throw new DomainException('Return quantity must be greater than zero.');
        }

        $salesOrderProduct = SalesOrderProduct::with('tempOrder')
            ->lockForUpdate()
            ->findOrFail($detail->sales_order_product_id);
        $product = Product::findOrFail($detail->product_id);

        if ((float) $salesOrderProduct->final_final_dispatched_quantity < $quantity) {
            throw new DomainException('The invoice quantity exceeds the remaining dispatched quantity.');
        }

        $allocations = WarehouseAllocation::where('sales_order_product_id', $salesOrderProduct->id)
            ->where('shipping_status', 'shipped')
            ->where('final_final_dispatched_quantity', '>', 0)
            ->orderBy('id')
            ->lockForUpdate()
            ->get();
        $sourceAllocations = $allocations;
        $detailWarehouseAllocations = $allocations->where('warehouse_id', $detail->warehouse_id);

        if ($detailWarehouseAllocations->sum('final_final_dispatched_quantity') >= $quantity) {
            $sourceAllocations = $detailWarehouseAllocations;
        }

        $releasePlan = [];
        $quantityToAllocate = $quantity;

        foreach ($sourceAllocations as $allocation) {
            if ($quantityToAllocate <= 0) {
                break;
            }

            $allocationQuantity = min($quantityToAllocate, (float) $allocation->final_final_dispatched_quantity);

            if ((float) $allocation->allocated_quantity < $allocationQuantity) {
                throw new DomainException('The allocated quantity is lower than the invoice quantity.');
            }

            $releasePlan[] = [
                'allocation' => $allocation,
                'warehouse_id' => (int) $allocation->warehouse_id,
                'quantity' => $allocationQuantity,
            ];
            $quantityToAllocate -= $allocationQuantity;
        }

        if ($quantityToAllocate > 0) {
            if ($allocations->isNotEmpty()) {
                throw new DomainException('Could not match the invoice quantity to shipped warehouse allocations.');
            }

            $sourceWarehouseId = $detail->warehouse_id;

            if (! $sourceWarehouseId && $salesOrderProduct->warehouse_stock_id) {
                $sourceWarehouseId = WarehouseStock::find($salesOrderProduct->warehouse_stock_id)?->warehouse_id;
            }

            if (! $sourceWarehouseId) {
                throw new DomainException('Could not find the source warehouse for this invoice line.');
            }

            $releasePlan[] = [
                'allocation' => null,
                'warehouse_id' => (int) $sourceWarehouseId,
                'quantity' => $quantityToAllocate,
            ];
        }

        $sourceStocks = collect();

        foreach ($releasePlan as $release) {
            $sourceWarehouseId = $release['warehouse_id'];

            if ($sourceStocks->has($sourceWarehouseId)) {
                continue;
            }

            $stock = WarehouseStock::where('warehouse_id', $sourceWarehouseId)
                ->where('sku', $product->sku)
                ->lockForUpdate()
                ->first();

            if (! $stock) {
                throw new DomainException("No stock record exists for {$product->sku} in the source warehouse.");
            }

            $sourceStocks->put($sourceWarehouseId, $stock);
        }

        $sourceQuantities = collect($releasePlan)->groupBy('warehouse_id')->map(
            fn ($releases) => $releases->sum('quantity')
        );

        foreach ($sourceQuantities as $sourceWarehouseId => $sourceQuantity) {
            $sourceStock = $sourceStocks->get((int) $sourceWarehouseId);

            if ((float) $sourceStock->block_quantity < $sourceQuantity) {
                throw new DomainException("Not enough blocked stock for {$product->sku} in the source warehouse.");
            }

            if ((int) $sourceWarehouseId !== (int) $destinationWarehouse->id && (float) $sourceStock->original_quantity < $sourceQuantity) {
                throw new DomainException("Not enough source stock to transfer {$product->sku} to the selected warehouse.");
            }
        }

        $destinationStock = $sourceStocks->get((int) $destinationWarehouse->id);

        if (! $destinationStock) {
            $destinationStock = WarehouseStock::where('warehouse_id', $destinationWarehouse->id)
                ->where('sku', $product->sku)
                ->lockForUpdate()
                ->first();
        }

        $quantityTransferred = 0;

        foreach ($sourceQuantities as $sourceWarehouseId => $sourceQuantity) {
            $sourceWarehouseId = (int) $sourceWarehouseId;
            $sourceStock = $sourceStocks->get($sourceWarehouseId);
            $sourceStock->block_quantity = max(0, (float) $sourceStock->block_quantity - $sourceQuantity);

            if ($sourceWarehouseId === (int) $destinationWarehouse->id) {
                $sourceStock->available_quantity = (float) $sourceStock->available_quantity + $sourceQuantity;
            } else {
                $sourceStock->original_quantity = max(0, (float) $sourceStock->original_quantity - $sourceQuantity);
                $quantityTransferred += $sourceQuantity;
            }

            $sourceStock->save();

            WarehouseStockLog::create([
                'warehouse_id' => $sourceWarehouseId,
                'sales_order_id' => $invoice->sales_order_id,
                'customer_id' => $salesOrderProduct->customer_id,
                'sku' => $product->sku,
                'block_quantity' => -$sourceQuantity,
                'reason' => 'Invoice quantity returned to available stock',
                'remark' => $remark,
            ]);
        }

        if ($quantityTransferred > 0) {
            if (! $destinationStock) {
                WarehouseStock::create([
                    'warehouse_id' => $destinationWarehouse->id,
                    'sku' => $product->sku,
                    'original_quantity' => $quantityTransferred,
                    'available_quantity' => $quantityTransferred,
                    'block_quantity' => 0,
                ]);
            } else {
                $destinationStock->original_quantity = (float) $destinationStock->original_quantity + $quantityTransferred;
                $destinationStock->available_quantity = (float) $destinationStock->available_quantity + $quantityTransferred;
                $destinationStock->save();
            }

            WarehouseStockLog::create([
                'warehouse_id' => $destinationWarehouse->id,
                'sales_order_id' => $invoice->sales_order_id,
                'customer_id' => $salesOrderProduct->customer_id,
                'sku' => $product->sku,
                'block_quantity' => 0,
                'reason' => 'Invoice quantity transferred from another warehouse',
                'remark' => $remark,
            ]);
        }

        foreach ($releasePlan as $release) {
            $allocation = $release['allocation'];

            if (! $allocation) {
                continue;
            }

            $allocation->allocated_quantity = max(0, (float) $allocation->allocated_quantity - $release['quantity']);
            $allocation->final_dispatched_quantity = max(0, (float) $allocation->final_dispatched_quantity - $release['quantity']);
            $allocation->final_final_dispatched_quantity = max(0, (float) $allocation->final_final_dispatched_quantity - $release['quantity']);

            if ($allocation->final_final_dispatched_quantity <= 0) {
                $allocation->status = 'cancelled';
                $allocation->product_status = 'pending';
                $allocation->shipping_status = 'ready_to_ship';
            }

            $allocation->save();
        }

        $salesOrderProduct->final_dispatched_quantity = max(0, (float) $salesOrderProduct->final_dispatched_quantity - $quantity);
        $salesOrderProduct->final_final_dispatched_quantity = max(0, (float) $salesOrderProduct->final_final_dispatched_quantity - $quantity);
        $salesOrderProduct->status = $salesOrderProduct->final_final_dispatched_quantity > 0
            ? $salesOrderProduct->status
            : 'ready_to_ship';

        if ($salesOrderProduct->tempOrder) {
            $remainingBlock = max(0, (float) ($salesOrderProduct->tempOrder->block ?? 0) - $quantity);
            $salesOrderProduct->tempOrder->block = $remainingBlock;
            $salesOrderProduct->tempOrder->available_quantity = $remainingBlock;
            $salesOrderProduct->tempOrder->available_quantity_track = $remainingBlock;
            $salesOrderProduct->tempOrder->unavailable_quantity = max(0, (float) ($salesOrderProduct->tempOrder->po_qty ?? 0) - $remainingBlock);
            $salesOrderProduct->tempOrder->unavailable_quantity_track = max(0, (float) ($salesOrderProduct->tempOrder->po_qty ?? 0) - $remainingBlock);
            $salesOrderProduct->tempOrder->save();
        }

        $salesOrderProduct->save();

        return $salesOrderProduct;
    }
}
