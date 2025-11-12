<td>
    @php
        $hasAllocations = $order->warehouseAllocations && $order->warehouseAllocations->count() > 0;
    @endphp

    @if ($hasAllocations)
        {{-- Auto-allocation: Show warehouse-wise breakdown --}}
        @if ($isAdmin ?? false)
            {{-- Admin sees all warehouses --}}
            @if ($order->warehouseAllocations->count() > 0)
                @foreach ($order->warehouseAllocations->sortBy('sequence') as $allocation)
                    <div class="mb-1">
                        <strong>{{ $allocation->warehouse->name ?? 'N/A' }}</strong>:
                        {{ $allocation->final_dispatched_quantity }}
                    </div>
                @endforeach
            @else
                <span class="text-muted">0</span>
            @endif
        @else
            {{-- Warehouse user sees only their warehouse --}}
            @php
                $userAllocations = $order->warehouseAllocations->where('warehouse_id', $userWarehouseId ?? 0);
            @endphp
            @if ($userAllocations->count() > 0)
                @foreach ($userAllocations as $allocation)
                    <div class="mb-1">
                        <strong>{{ $allocation->warehouse->name ?? 'N/A' }}</strong>:
                        {{ $allocation->final_dispatched_quantity }}
                    </div>
                @endforeach
            @else
                <span class="text-muted">0</span>
            @endif
        @endif
    @else
        <span class="text-muted">0</span>
    @endif
</td>
