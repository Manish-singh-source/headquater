
<td>
    @php
        // Calculate Final Dispatch Qty from warehouse allocations if available
        $finalDispatchQty = 0;
        $hasAllocations = $order->warehouseAllocations && $order->warehouseAllocations->count() > 0;

        if ($hasAllocations) {
            if ($isAdmin ?? false) {
                // Admin: Sum all warehouses' final dispatch quantities
        $finalDispatchQty = $order->warehouseAllocations->sum('final_dispatched_quantity') ?: 0;
    } else {
        // Warehouse user: Only their warehouse's final dispatch quantity
                $finalDispatchQty =
                    $order->warehouseAllocations
                        ->where('warehouse_id', $userWarehouseId ?? 0)
                        ->sum('final_dispatched_quantity') ?:
                    0;
            }
        } else {
            // Single warehouse or fallback to sales_order_products table
            $finalDispatchQty = $order->final_dispatched_quantity ?? 0;
        }
    @endphp
    {{ $finalDispatchQty }}
</td>
