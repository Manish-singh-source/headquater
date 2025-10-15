@extends('layouts.master')
@section('main-content')
    @php
        $statuses = [
            'pending' => 'Pending',
            'blocked' => 'Blocked',
            'received' => 'Products Received',
            'completed' => 'Completed',
            'ready_to_ship' => 'Ready To Ship',
            'ready_to_package' => 'Ready To Package',
        ];
    @endphp

    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center justify-content-between mb-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Vendor Order List</li>
                        </ol>
                    </nav>
                </div>
                <div class="justify-end">
                    <div class="row g-3 justify-content-end">
                        <div class="col-12 col-md-auto">
                            <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                                {{-- <a href="{{ route('purches.create') }}" class="btn btn-primary px-4"><i
                                        class="bi bi-plus-lg me-2"></i>Create Order</a> --}}
                                <div>
                                    <div class="btn-group">
                                        <button type="button" class="btn border-2 border-primary">Action</button>
                                        <button type="button"
                                            class="btn btn-outline-primary split-bg-primary dropdown-toggle dropdown-toggle-split"
                                            data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                                            <a class="dropdown-item cursor-pointer" id="delete-selected">Delete All</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-body">
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active status-filter-tab" id="all-tab" data-bs-toggle="tab"
                                data-order="all" data-bs-target="#all" type="button" role="tab" aria-controls="all"
                                aria-selected="true">All</button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link status-filter-tab" id="active-tab" data-bs-toggle="tab"
                                data-order="Completed" data-bs-target="#active" type="button" role="tab"
                                aria-controls="active" aria-selected="false">Completed</button>
                        </li>

                        <li class="nav-item" role="presentation">
                            <button class="nav-link status-filter-tab" id="inactive-tab" data-bs-toggle="tab"
                                data-order="Pending" data-bs-target="#inactive" type="button" role="tab"
                                aria-controls="inactive" aria-selected="false">Pending</button>
                        </li>
                    </ul>



                    <div class="customer-table">
                        <div class="table-responsive white-space-nowrap">
                            <table id="purchase_order" class="table table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                        </th>
                                        <th>Sales&nbsp;Order&nbsp;Id</th>
                                        <th>Purchase&nbsp;Order&nbsp;Id</th>
                                        <th>Vendor&nbsp;Code</th>
                                        <th>Order&nbsp;Status</th>
                                        <th>Total&nbsp;Product</th>
                                        <th>Total&nbsp;Amount</th>
                                        <th>Total&nbsp;Paid&nbsp;Amount</th>
                                        <th>Total&nbsp;Due&nbsp;Amount</th>
                                        <th>Ordered&nbsp;Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($purchaseOrders as $order)
                                        <tr>
                                            <td>
                                                <input class="form-check-input row-checkbox" type="checkbox" name="ids[]"
                                                    value="{{ $order->id }}">
                                            </td>
                                            <td>{{ $order->sales_order_id }}</td>
                                            <td>{{ $order->id }}</td>
                                            <td>
                                                <p class="mb-0 customer-name fw-bold">
                                                    {{ $order->vendor_code ?? 'N/A' }}
                                                </p>
                                            </td>
                                            <td>
                                                {{ $statuses[$order->status] ?? 'On Hold' }}
                                            </td>
                                            <td>{{ $order->purchase_order_products_count ?? 0 }}</td>
                                            <td>{{ $order->vendorPI[0]->total_amount ?? 0 }}</td>
                                            <td>{{ $order->vendorPI[0]->total_paid_amount ?? 0 }}</td>
                                            <td>{{ $order->vendorPI[0]->total_due_amount ?? 0 }}</td>
                                            <td>{{ $order->created_at->format('d-M-Y') }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <a aria-label="anchor"
                                                        href="{{ route('purchase.order.view', $order->id) }}"
                                                        class="btn btn-icon btn-sm bg-primary-subtle me-1"
                                                        data-bs-toggle="tooltip" data-bs-original-title="View">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                                            height="13" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            class="feather feather-eye text-primary">
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                            <circle cx="12" cy="12" r="3"></circle>
                                                        </svg>
                                                    </a>

                                                    {{-- @can('PermissionChecker', 'update_purchase_order')
                                                        <a aria-label="anchor" href="#"
                                                            class="btn btn-icon btn-sm bg-warning-subtle me-1"
                                                            data-bs-toggle="tooltip" data-bs-original-title="Edit">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                                                height="13" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                class="feather feather-edit text-warning">
                                                                <path
                                                                    d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                                                </path>
                                                                <path
                                                                    d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                                                </path>
                                                            </svg>
                                                        </a>
                                                    @endcan --}}

                                                    <form action="{{ route('purchase.order.delete', $order->id) }}"
                                                        method="POST" onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-icon btn-sm bg-danger-subtle delete-row">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="13"
                                                                height="13" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-trash-2 text-danger">
                                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                                <path
                                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                                </path>
                                                                <line x1="10" y1="11" x2="10"
                                                                    y2="17"></line>
                                                                <line x1="14" y1="11" x2="14"
                                                                    y2="17"></line>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center">No Records Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </main>
    <!--end main wrapper-->
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            var brandSelection = $('#purchase_order').DataTable({
                "columnDefs": [{
                    "orderable": false,
                }],
                lengthChange: true,
                buttons: [{
                    extend: 'excelHtml5',
                    className: 'd-none', // hide the default button
                }]
            });

            $('.status-filter-tab').on('click', function() {
                var selected = $(this).data('order').trim();
                console.log(selected);

                if (selected === 'all') {
                    selected = '';
                }
                // Use regex for exact match
                brandSelection.column(4).search(selected ? '^' + selected + '$' : '', true, false).draw();
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Select All functionality
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.row-checkbox');
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
            });

            // Delete Selected functionality
            document.getElementById('delete-selected').addEventListener('click', function() {
                let selected = [];
                document.querySelectorAll('.row-checkbox:checked').forEach(cb => {
                    selected.push(cb.value);
                });
                console.log(selected);

                if (selected.length === 0) {
                    alert('Please select at least one record.');
                    return;
                }
                if (confirm('Are you sure you want to delete selected records?')) {
                    // Create a form and submit
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('purchase.order.bulk.delete') }}';
                    form.innerHTML = `
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="ids" value="${selected.join(',')}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    </script>
@endsection
