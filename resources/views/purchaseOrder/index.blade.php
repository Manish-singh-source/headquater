@extends('layouts.master')
@section('main-content')

    @php
        $statuses = [
            'pending' => 'Pending',
            'blocked' => 'Blocked',
            'completed' => 'Completed',
            'ready_to_ship' => 'Ready To Ship',
            'ready_to_package' => 'Ready To Package',
        ];
    @endphp

    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Vendor Order List</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-12 col-md-auto">
                <div class="d-flex align-items-center gap-2 justify-content-end">

                    <a href="{{ route('purches.store') }}" class="btn btn-primary px-4"><i
                            class="bi bi-plus-lg me-2"></i>Create Order</a>
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

            <div class="card mt-4">
                <div class="card-body">
                    <div class="customer-table">
                        <div class="table-responsive white-space-nowrap">
                            <table id="example" class="table table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <input class="form-check-input" type="checkbox">
                                        </th>
                                        <th>Order Id</th>
                                        <th>Vendor Code</th>
                                        <th>Order Status</th>
                                        <th>Ordered Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($purchaseOrders as $order)
                                        <tr>
                                            <td>
                                                <input class="form-check-input" type="checkbox">
                                            </td>
                                            <td>{{ 'ORDER-' . $order->id }}</td>
                                            <td>
                                                <p class="mb-0 customer-name fw-bold">
                                                    @php
                                                        $vendorCodes = $order->purchaseOrderProducts
                                                            ->pluck('vendor_code')
                                                            ->filter()
                                                            ->unique();
                                                    @endphp
                                                    @forelse($vendorCodes as $vendor)
                                                        {{ $vendor }},
                                                    @empty
                                                        NA
                                                    @endforelse
                                                </p>
                                            </td>
                                            <td>
                                                {{ $statuses[$order->status] ?? 'On Hold' }}
                                            </td>
                                            <td>{{ $order->created_at->format('d-M-Y') }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('PermissionChecker', 'view_purchase_order_detail')
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
                                                    @endcan

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

                                                    @can('PermissionChecker', 'delete_purchase_order')
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
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">No Records Found</td>
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
            $(".available-product").hide();
            $(".unavailable-product").hide();
            $(".loader").hide();

            $("#orderStatus").on("click", function() {
                $(".loader").show();

                setTimeout(function() {
                    $(".loader").hide();
                    $(".available-product").show();
                    $(".unavailable-product").show();
                }, 3000);
            });

            $("#holdOrder").on("click", function() {
                $(".loader").show();
                setTimeout(function() {
                    $(".loader").hide();
                    location.pathname = '/headquater/order.php';
                }, 2000);
            });

            $("#submitOrder").on("click", function() {
                $(".loader").show();
                setTimeout(function() {
                    $(".loader").hide();
                    location.pathname = '/headquater/order.php';
                }, 2000);
            });

            $(".customer-groups").hide();
            // $("#add-customer").on("click", function() {
            //     $(".customer-groups").show();
            //     let groupName = $("#groupName").val();
            //     let customerName = $("#customerName").val();
            //     let subCustomerName = $("#subCustomerName").val();

            //     let row = document.createElement("tr");
            //     let td = document.createElement("td");
            //     let table = $("#customerGroupTable tbody").append(row).append(td).html(groupName);
            // });

            $("#add-customer").on("click", function() {
                $(".customer-groups").show();

                let groupName = $("#groupName").val();
                let customerName = $("#customerName").val();
                let subCustomerName = $("#subCustomerName").val();

                // Create table row with 3 td cells
                //let row = `
            //        <tr>
            //            <td>${customerName}</td>
            //            <td>${subCustomerName}</td>
            //        </tr>
            //    `;

                // Append to table body
                $("#groupTitle").html(groupName);
                $("#customerGroupTable tbody").append(row);
            });

            $("#upload-excel").on("click", function() {
                $(".customer-groups").show();

                let document_image = $("#document_image").val();
                let warehouseLocation = $("#warehouseLocation").val();

                console.log(document_image);
                console.log(warehouseLocation);
                // Create table row with 3 td cells
                let rowHeading = `
                        <th>Document File</th>
                        <th>Warehouse Location</th>
                    `;
                let row = `
                        <td>${document_image}</td>
                        <td>${warehouseLocation}</td>
                    `;

                // Append to table bod
                $("#customerGroupTable thead tr").append(rowHeading);
                $("#customerGroupTable tbody tr").append(row);

                $(".loader").show();

                setTimeout(function() {
                    $(".po-uploads").hide();
                    $(".loader").hide();
                    $(".available-product").show();
                    $(".unavailable-product").show();
                }, 3000);
            });

            $("#orderStatus").hide();
            $(".po-uploads").hide();
            $("#save-customers").on("click", function() {
                $(".customer-inputs").hide();
                $(".po-uploads").show();
                $("#orderStatus").show();
                $(this).hide();
            });

        });
    </script>

    <script>
        function exportTableToExcel(tableID, filename = 'table_data.xls') {
            const dataType = 'application/vnd.ms-excel';
            const tableSelect = document.getElementById(tableID);
            const tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

            // Create download link element
            const downloadLink = document.createElement("a");
            document.body.appendChild(downloadLink);

            downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
            downloadLink.download = filename;
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }
    </script>
@endsection
