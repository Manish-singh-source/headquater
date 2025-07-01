<!-- Blade file -->

<div class="row g-3">
    <div class="col-12 col-md-2">
        <div class="position-relative">
            <input class="form-control px-5" type="search" id="search-customer" placeholder="Search Customers">
            <span
                class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50 fs-5">search</span>
        </div>
    </div>
    <div class="col-12 col-md-2 flex-grow-1 overflow-auto">
        <div class="btn-group position-static">
            <div class="btn-group position-static">
                <select name="sort_customer" class="form-control" id="sort-customer">
                    <option value="">Sort</option>
                    <option value="first_name">By Name</option>
                    <option value="email">By Email</option>
                </select>
            </div>
            <div class="btn-group position-static">
                <select name="status_customer" class="form-control" id="status-customer">
                    <option value="">Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-auto">
        <div class="d-flex align-items-center gap-2 justify-content-lg-end">
            <form action="{{ route('excel.form') }}" method="GET">
                <button type="submit" class="btn btn-filter px-4"><i class="bi bi-box-arrow-right me-2"></i>Export</button>
            </form>
            <a href="{{ route('add-customer') }}" class="btn btn-primary px-4"><i
                    class="bi bi-plus-lg me-2"></i>Add Customers</a>
        </div>
    </div>
</div>
<!--end row-->














<!-- API for fetching table data -->

@section('script')
<script>
    $(document).ready(function() {

        function getFilteredData(url) {
            let search = $("#search-vendor").val();
            let sort = $("#sort-vendor").val();
            let status = $("#status-vendor").val();
            console.log(search);
            console.log(sort);
            console.log(status);

            $.ajax({
                url: url,
                method: 'GET',
                data: {
                    search: search,
                    sort: sort,
                    status: status,
                },
                success: function(response) {
                    console.log(response);
                    let data = response.data.data;
                    let table = $("#tableData");

                    let rows = document.createElement("tr");
                    data.forEach(vendor => {
                        console.log(vendor);
                        rows +=
                            `<tr>
                                    <td>
                                        <input class="form-check-input" type="checkbox">
                                    </td>
                                    <td>
                                        <a class="d-flex align-items-center gap-3"
                                            href="${vendor.id}">
                                            <p class="mb-0 customer-name fw-bold">${vendor.first_name}</p>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="javascript:;" class="font-text1">${vendor.email}</a>
                                    </td>
                                    <td>${vendor.phone}</td>
                                    <td>142</td>
                                    <td>Mumbai</td>

                                    <td>Nov 12, 10:45 PM</td>
                                    <td>
                                        <div class=" form-switch form-check-success">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="flexSwitchCheckSuccess" checked="">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a aria-label="anchor"
                                                href=""
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
                                            <a aria-label="anchor" href=""
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

                                            <form action=""
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
                                </tr>`;
                    });

                    table.html(rows);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }

        getFilteredData("/filters-vendor-data");

        $("#search-vendor").on("keyup", function() {
            getFilteredData("/filters-vendor-data");
        });

        $("#sort-vendor").on("change", function() {
            getFilteredData("/filters-vendor-data");
        });

        $("#status-vendor").on("change", function() {
            getFilteredData("/filters-vendor-data");
        });

    });
</script>
@endsection














<!-- Controller Query Function -->

public function filterData(Request $request)
{
$query = Customer::with(['shippingCountry']);

// Search
if ($search = $request->input('search')) {
$query->where('first_name', 'like', "%{$search}%");
}

if ($sort = $request->input('sort')) {
$query->orderBy($sort, 'ASC');
}

if ($status = $request->input('status')) {
if ($status == 'active') {
$query->where('status', '1');
} elseif ($status == 'inactive') {
$query->where('status', '0');
}
}

// Pagination
$vendors = $query->paginate(10)->withQueryString();

return response()->json([
'status' => false,
'message' => 'Vendors Not Fouond',
'data' => $vendors
], 200);
}












<!-- Route -->
Route::get('/filters-customer-data', [CustomerController::class, 'filterData']);
