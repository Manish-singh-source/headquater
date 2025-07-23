<?php 

@php
$statuses = [
    'pending' => 'Pending',
    'blocked' => 'Blocked',
    'completed' => 'Completed',
    'ready_to_ship' => 'Ready To Ship',
    'ready_to_package' => 'Ready To Package',
];
@endphp


{{ $statuses[$order->status] ?? 'On Hold' }}