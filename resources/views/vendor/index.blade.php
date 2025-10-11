@extends('layouts.master')
@section('main-content')
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
                            <li class="breadcrumb-item active" aria-current="page">Vendors</li>
                        </ol>
                    </nav>
                </div>
                <div class="justify-end">
                    <div class="row g-3 justify-content-end">
                        <div class="col-12 col-md-auto">
                            <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                                <a href="{{ route('vendor.create') }}"><button class="btn btn-primary px-4"><i
                                            class="bi bi-plus-lg me-2"></i>Add Vendor</button></a>
                                <div class="ms-auto">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-outline-primary">Action</button>
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
                    <!-- Tabs -->
                    <ul class="nav nav-tabs" id="vendorTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="all-vendors" type="button" data-status="all">All</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" type="button" id="active-vendors" data-status="1">Active</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" type="button" id="inactive-vendors" data-status="0">Inactive</button>
                        </li>
                    </ul>

                    <div class="customer-table mt-3">
                        <div class="table-responsive white-space-nowrap">
                            <table id="vendorTable" class="table table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <input class="form-check-input" type="checkbox" id="select-all">
                                        </th>
                                        <th>SR.No</th>
                                        <th>Vendor Code</th>
                                        <th>Vendor Name</th>
                                        <th>Email</th>
                                        <th>Contact Number</th>
                                        <th>Orders</th>
                                        <th>Location</th>
                                        <th>Joined At</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($vendors as $key=> $vendor)
                                        <tr>
                                            <td>
                                                <input class="form-check-input row-checkbox" type="checkbox" name="ids[]"
                                                    value="{{ $vendor->id }}">
                                            </td>
                                            <td>
                                                {{ $key + 1 }}
                                            </td>
                                            <td>{{ $vendor->vendor_code }}</td>
                                            <td>
                                                <a class="d-flex align-items-center gap-3"
                                                    href="{{ route('vendor.view', $vendor->id) }}">
                                                    <p class="mb-0 customer-name fw-bold">{{ $vendor->contact_name }}</p>
                                                </a>
                                            </td>
                                            <td>
                                                <a href="javascript:;" class="font-text1">{{ $vendor->email }}</a>
                                            </td>
                                            <td>{{ $vendor->phone_number }}</td>
                                            <td>142</td>
                                            <td>Mumbai</td>

                                            <td>Nov 12, 10:45 PM</td>
                                            <td>
                                                <div class="form-switch form-check-success">
                                                    <input class="form-check-input status-switch1" type="checkbox"
                                                        role="switch" data-vendor-id="{{ $vendor->id }}"
                                                        {{ $vendor->status == 1 ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a aria-label="anchor" href="{{ route('vendor.view', $vendor->id) }}"
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

                                                    <a aria-label="anchor" href="{{ route('vendor.edit', $vendor->id) }}"
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

                                                    <form action="{{ route('vendor.destroy', $vendor->id) }}"
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
                                                    <a aria-label="anchor" data-bs-toggle="tooltip"
                                                        data-bs-original-title="Delete">
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center">
                                                No Record Found
                                            </td>
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
@endsection
@section('script')
    <script>
        $(document).on('change', '.status-switch1', function() {
            var vendorId = $(this).data('vendor-id');
            var status = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: '{{ route('vendor.toggleStatus') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: vendorId,
                    status: status
                },
                success: function(response) {
                    if (response.success) {
                        alert('Status updated successfully!');
                    } else {
                        alert('Failed to update status.');
                    }
                },
                error: function() {
                    alert('Status update failed!');
                }
            });
        });
    </script>
    <script>
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
                if (selected.length === 0) {
                    alert('Please select at least one record.');
                    return;
                }
                if (confirm('Are you sure you want to delete selected records?')) {
                    // Create a form and submit
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('delete.selected.vendor') }}';
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


    <script>
        $(document).ready(function() {
            var vendorTable = $('#vendorTable').DataTable({
                "columnDefs": [{
                        "orderable": false,
                        "targets": -1
                    } // last column not orderable
                ],
                lengthChange: true,
                buttons: [{
                        extend: 'excelHtml5',
                        className: 'd-none'
                    } // hide default button
                ]
            });

            // Function to filter table based on status
            function filterWarehouse(status) {
                vendorTable.rows().every(function() {
                    var $checkbox = $(this.node()).find('.status-switch2');
                    // var isChecked = $checkbox.prop('checked') ? 1 : 0;
                    var isChecked = $checkbox.is(':checked') ? '1' : '0';

                    if (status === 'all') {
                        $(this.node()).show();
                    } else if (isChecked == status) {
                        $(this.node()).show();
                    } else {
                        $(this.node()).hide();
                    }
                });
            }

            // Tab click event
            $('#vendorTabs button').on('click', function() {
                $('#vendorTabs button').removeClass('active');
                $(this).addClass('active');

                var status = $(this).data('status'); // all / 1 / 0
                console.log("Filtering for status:", status);
                filterWarehouse(status);
            });

            // Initial load: show all
            filterWarehouse('all');
        });
    </script>
@endsection
