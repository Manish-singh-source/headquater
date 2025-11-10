@extends('layouts.master')
@section('main-content')
    <main class="main-wrapper">
        <div class="main-content">

            <div class="div d-flex">
                <div class="col-6">
                    <i class="bx bx-home-alt"></i>
                    <h5 class="mb-3">Products Return</h5>
                </div>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="div d-flex my-2">
                                <div class="col">
                                    <h6 class="mb-3">Products Table</h6>
                                </div>
                            </div>

                            <!-- Tab Navigation -->
                            <ul class="nav nav-tabs mb-3" id="statusTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ (!isset($status) || $status == 'all') ? 'active' : '' }}"
                                            id="all-tab"
                                            data-bs-toggle="tab"
                                            data-status="all"
                                            type="button"
                                            role="tab">
                                        All
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ (isset($status) && $status == 'pending') ? 'active' : '' }}"
                                            id="pending-tab"
                                            data-bs-toggle="tab"
                                            data-status="pending"
                                            type="button"
                                            role="tab">
                                        Pending
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ (isset($status) && $status == 'accepted') ? 'active' : '' }}"
                                            id="accepted-tab"
                                            data-bs-toggle="tab"
                                            data-status="accepted"
                                            type="button"
                                            role="tab">
                                        Accepted
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ (isset($status) && $status == 'returned') ? 'active' : '' }}"
                                            id="returned-tab"
                                            data-bs-toggle="tab"
                                            data-status="returned"
                                            type="button"
                                            role="tab">
                                        Returned
                                    </button>
                                </li>
                            </ul>

                            <!-- Loading Indicator -->
                            {{-- <div id="loadingIndicator" class="text-center py-3" style="display: none;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div> --}}

                            <!-- Table Container -->
                            <div class="product-table" id="poTable">
                                <div class="table-responsive white-space-nowrap" id="tableContainer">
                                    @include('partials.vendor-return-table', ['vendorOrders' => $vendorOrders])
                                </div>
                            </div>
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
            var table3;

            // Initialize DataTable
            function initDataTable() {
                if ($.fn.DataTable.isDataTable('#shortage-exceed-table')) {
                    $('#shortage-exceed-table').DataTable().destroy();
                }

                table3 = $('#shortage-exceed-table').DataTable({
                    "columnDefs": [{
                        "orderable": false,
                    }],
                    lengthChange: true,
                    buttons: [{
                        extend: 'excelHtml5',
                        className: 'd-none',
                    }]
                });
            }

            // Initialize on page load
            initDataTable();

            // Handle tab clicks
            $('#statusTabs button[data-bs-toggle="tab"]').on('click', function(e) {
                e.preventDefault();

                var status = $(this).data('status');
                var $this = $(this);

                // Show loading indicator
                $('#loadingIndicator').show();
                $('#tableContainer').css('opacity', '0.5');

                // Make AJAX request
                $.ajax({
                    url: '{{ route("return.accept") }}',
                    type: 'GET',
                    data: {
                        status: status
                    },
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update table content
                            $('#tableContainer').html(response.html);

                            // Reinitialize DataTable
                            initDataTable();

                            // Update active tab
                            $('#statusTabs button').removeClass('active');
                            $this.addClass('active');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading data:', error);
                        alert('Error loading data. Please try again.');
                    },
                    complete: function() {
                        // Hide loading indicator
                        $('#loadingIndicator').hide();
                        $('#tableContainer').css('opacity', '1');
                    }
                });
            });

            // Handle dropdown filter (if exists)
            $('#shortExceedSelect').on('change', function() {
                var selected = $(this).val().trim();
                table3.column(-3).search(selected ? '^' + selected + '$' : '', true, false).draw();
            });
        });
    </script>
@endsection
