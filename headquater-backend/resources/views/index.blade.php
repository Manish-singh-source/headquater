@extends('layouts.master')
@section('main-content')
  <!--start main wrapper-->

  <main class="main-wrapper">
    <div class="main-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Dashboard</div>
        <div class="ps-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">Analysis</li>
            </ol>
          </nav>
        </div>
        <!-- <div class="ms-auto">
						<div class="btn-group">
							<button type="button" class="btn btn-outline-primary">Settings</button>
							<button type="button" class="btn btn-outline-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">	<span class="visually-hidden">Toggle Dropdown</span>
							</button>
							<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">	<a class="dropdown-item" href="javascript:;">Action</a>
								<a class="dropdown-item" href="javascript:;">Another action</a>
								<a class="dropdown-item" href="javascript:;">Something else here</a>
								<div class="dropdown-divider"></div>	<a class="dropdown-item" href="javascript:;">Separated link</a>
							</div>
						</div>
					</div> -->
      </div>
      <!--end breadcrumb-->

      <div class="row">
        <div class="col-xxl-8 d-flex align-items-stretch">
          <div class="card w-100 overflow-hidden rounded-4">
            <div class="card-body position-relative p-4">
              <div class="row">
                <div class="col-12 col-sm-7">
                  <div class="d-flex align-items-center gap-3 mb-5">
                    <img src="assets/images/avatars/01.png" class="rounded-circle bg-grd-info p-1" width="60" height="60" alt="user">
                    <div class="">
                      <p class="mb-0 fw-semibold">Welcome back</p>
                      <h4 class="fw-semibold mb-0 fs-4 mb-0">Manish Singh!</h4>
                    </div>
                  </div>
                  <div class="d-flex align-items-center gap-5">
                    <div class="">
                      <h4 class="mb-1 fw-semibold d-flex align-content-center">₹65.4K<i class="ti ti-arrow-up-right fs-5 lh-base text-success"></i>
                      </h4>
                      <p class="mb-3">Today's Sales</p>
                      <div class="progress mb-0" style="height:5px;">
                        <div class="progress-bar bg-grd-success" role="progressbar" style="width: 60%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                    <div class="vr"></div>
                    <div class="">
                      <h4 class="mb-1 fw-semibold d-flex align-content-center">78.4%<i class="ti ti-arrow-up-right fs-5 lh-base text-success"></i>
                      </h4>
                      <p class="mb-3">Growth Rate</p>
                      <div class="progress mb-0" style="height:5px;">
                        <div class="progress-bar bg-grd-danger" role="progressbar" style="width: 60%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-sm-5">
                  <div class="welcome-back-img pt-4">
                    <img src="assets/images/gallery/welcome-back-3.png" height="180" alt="">
                  </div>
                </div>
              </div><!--end row-->
            </div>
          </div>
        </div>

        <div class="col-xxl-4">
          <div class="row">
            <div class="col-xl-6 col-sm-6 col-12 d-flex">
              <div class="card bg-white sale-widget flex-fill">
                <div class="card-body d-flex align-items-center">
                  <span class="sale-icon bg-white text-primary">
                    <i class="ti ti-file-text fs-24"></i>
                  </span>
                  <div class="ms-2">
                    <p class="text-dark mb-1">Total Customers</p>
                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                      <h4 class="text-dark">2.3K</h4>
                      <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-6 col-sm-6 col-12 d-flex">
              <div class="card bg-white sale-widget flex-fill">
                <div class="card-body d-flex align-items-center">
                  <span class="sale-icon bg-white text-primary">
                    <i class="ti ti-file-text fs-24"></i>
                  </span>
                  <div class="ms-2">
                    <p class="text-dark mb-1">Total Vendors</p>
                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                      <h4 class="text-dark">400</h4>
                      <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-6 col-sm-6 col-12 d-flex">
              <div class="card bg-white sale-widget flex-fill">
                <div class="card-body d-flex align-items-center">
                  <span class="sale-icon bg-white text-primary">
                    <i class="ti ti-file-text fs-24"></i>
                  </span>
                  <div class="ms-2">
                    <p class="text-dark mb-1">Total Customer Orders</p>
                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                      <h4 class="text-dark">158</h4>
                      <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-6 col-sm-6 col-12 d-flex">
              <div class="card bg-white sale-widget flex-fill">
                <div class="card-body d-flex align-items-center">
                  <span class="sale-icon bg-white text-primary">
                    <i class="ti ti-file-text fs-24"></i>
                  </span>
                  <div class="ms-2">
                    <p class="text-dark mb-1">Total Vendor Orders</p>
                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                      <h4 class="text-dark">88</h4>
                      <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

        <div class="col-xl-6 col-xxl-8 d-flex align-items-stretch">
          <div class="card w-100 rounded-4">
            <div class="card-body">
              <div class="text-center">
                <h6 class="mb-0">Monthly Revenue</h6>
              </div>
              <div class="mt-4" id="chart5"></div>
              <p>Average monthly sale</p>
              <div class="d-flex align-items-center gap-3 mt-4">
                <div class="">
                  <h1 class="mb-0 text-primary">68.9%</h1>
                </div>
                <div class="d-flex align-items-center align-self-end">
                  <p class="mb-0 text-success">34.5%</p>
                  <span class="material-icons-outlined text-success">expand_less</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xxl-4">
          <div class="row">
            <div class="col-xl-6 col-sm-6 col-12 d-flex">
              <div class="card bg-white sale-widget flex-fill">
                <div class="card-body d-flex align-items-center">
                  <span class="sale-icon bg-white text-primary">
                    <i class="ti ti-file-text fs-24"></i>
                  </span>
                  <div class="ms-2">
                    <p class="text-dark mb-1">Total Products</p>
                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                      <h4 class="text-dark">2916</h4>
                      <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-6 col-sm-6 col-12 d-flex">
              <div class="card bg-white sale-widget flex-fill">
                <div class="card-body d-flex align-items-center">
                  <span class="sale-icon bg-white text-primary">
                    <i class="ti ti-file-text fs-24"></i>
                  </span>
                  <div class="ms-2">
                    <p class="text-dark mb-1">Total Warehouses</p>
                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                      <h4 class="text-dark">1</h4>
                      <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-6 col-sm-6 col-12 d-flex">
              <div class="card bg-white sale-widget flex-fill">
                <div class="card-body d-flex align-items-center">
                  <span class="sale-icon bg-white text-primary">
                    <i class="ti ti-file-text fs-24"></i>
                  </span>
                  <div class="ms-2">
                    <p class="text-dark mb-1">Total Invoices</p>
                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                      <h4 class="text-dark">895</h4>
                      <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-6 col-sm-6 col-12 d-flex">
              <div class="card bg-white sale-widget flex-fill">
                <div class="card-body d-flex align-items-center">
                  <span class="sale-icon bg-white text-primary">
                    <i class="ti ti-file-text fs-24"></i>
                  </span>
                  <div class="ms-2">
                    <p class="text-dark mb-1">Packaging List</p>
                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                      <h4 class="text-dark">10 Orders</h4>
                      <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-6 col-sm-6 col-12 d-flex">
              <div class="card bg-white sale-widget flex-fill">
                <div class="card-body d-flex align-items-center">
                  <span class="sale-icon bg-white text-primary">
                    <i class="ti ti-file-text fs-24"></i>
                  </span>
                  <div class="ms-2">
                    <p class="text-dark mb-1">Ready To Ship</p>
                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                      <h4 class="text-dark">15 Orders</h4>
                      <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-6 col-sm-6 col-12 d-flex">
              <div class="card bg-white sale-widget flex-fill">
                <div class="card-body d-flex align-items-center">
                  <span class="sale-icon bg-white text-primary">
                    <i class="ti ti-file-text fs-24"></i>
                  </span>
                  <div class="ms-2">
                    <p class="text-dark mb-1">Damaged Products</p>
                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                      <h4 class="text-dark">545</h4>
                      <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-6 col-sm-6 col-12 d-flex">
              <div class="card bg-white sale-widget flex-fill">
                <div class="card-body d-flex align-items-center">
                  <span class="sale-icon bg-white text-primary">
                    <i class="ti ti-file-text fs-24"></i>
                  </span>
                  <div class="ms-2">
                    <p class="text-dark mb-1">Missing Products</p>
                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                      <h4 class="text-dark">24</h4>
                      <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-6 col-sm-6 col-12 d-flex">
              <div class="card bg-white sale-widget flex-fill">
                <div class="card-body d-flex align-items-center">
                  <span class="sale-icon bg-white text-primary">
                    <i class="ti ti-file-text fs-24"></i>
                  </span>
                  <div class="ms-2">
                    <p class="text-dark mb-1">Total Tickets</p>
                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                      <h4 class="text-dark">48</h4>
                      <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

        <div class="col-lg-12 col-xxl-6 d-flex align-items-stretch">
          <div class="card w-100 rounded-4">
            <div class="card-body">
              <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="">
                  <h5 class="mb-0">Recent Vendor Orders</h5>
                </div>
                <div class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                    data-bs-toggle="dropdown">
                    <span class="material-icons-outlined fs-5">more_vert</span>
                  </a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                    <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                    <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                  </ul>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table align-middle">
                  <thead class="table-light">
                    <tr>
                      <th>
                        <input class="form-check-input" type="checkbox">
                      </th>
                      <th>Order Id</th>
                      <th>Vendor Name</th>
                      <th>Order Status</th>
                      <th>Ordered Date</th>
                      <th>Warehouse</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <input class="form-check-input" type="checkbox">
                      </td>
                      <td>#001</td>
                      <td>
                        <p class="mb-0 customer-name fw-bold">ABC</p>

                      </td>
                      <td>
                        Completed
                      </td>
                      <td>2025-04-11</td>
                      <td>Baroda</td>
                      <td>
                        <a aria-label="anchor" href="vendor-order-view.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <input class="form-check-input" type="checkbox">
                      </td>
                      <td>#002</td>
                      <td>
                        <p class="mb-0 customer-name fw-bold">XYZ</p>
                      </td>
                      <td>
                        On Hold
                      </td>
                      <td>2025-04-11</td>
                      <td>Baroda</td>
                      <td>
                        <a aria-label="anchor" href="vendor-order-view.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <input class="form-check-input" type="checkbox">
                      </td>
                      <td>#003</td>
                      <td>
                        <p class="mb-0 customer-name fw-bold">EFG</p>
                      </td>
                      <td>
                        Pending
                      </td>
                      <td>2025-04-11</td>
                      <td>Baroda</td>
                      <td>
                        <a aria-label="anchor" href="vendor-order-view.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-12 col-xxl-6 d-flex align-items-stretch">
          <div class="card w-100 rounded-4">
            <div class="card-body">
              <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="">
                  <h5 class="mb-0">Recent Customer Orders</h5>

                </div>
                <div class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                    data-bs-toggle="dropdown">
                    <span class="material-icons-outlined fs-5">more_vert</span>
                  </a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                    <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                    <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                  </ul>
                </div>
              </div>
              <div class="table-responsive">

                <table class="table align-middle">
                  <thead class="table-light">
                    <tr>
                      <th>
                        <input class="form-check-input" type="checkbox">
                      </th>
                      <th>Order Id</th>
                      <th>Customer Name</th>
                      <th>Order Status</th>
                      <th>Ordered Date</th>
                      <th>Warehouse</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <input class="form-check-input" type="checkbox">
                      </td>
                      <td>#001</td>
                      <td>
                        <p class="mb-0 customer-name fw-bold">ABC</p>

                      </td>
                      <td>
                        Completed
                      </td>
                      <td>2025-04-11</td>
                      <td>Baroda</td>
                      <td>
                        <a aria-label="anchor" href="customer-order-view.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <input class="form-check-input" type="checkbox">
                      </td>
                      <td>#002</td>
                      <td>
                        <p class="mb-0 customer-name fw-bold">XYZ</p>
                      </td>
                      <td>
                        On Hold
                      </td>
                      <td>2025-04-11</td>
                      <td>Baroda</td>
                      <td>
                        <a aria-label="anchor" href="customer-order-view.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <input class="form-check-input" type="checkbox">
                      </td>
                      <td>#003</td>
                      <td>
                        <p class="mb-0 customer-name fw-bold">EFG</p>
                      </td>
                      <td>
                        Pending
                      </td>
                      <td>2025-04-11</td>
                      <td>Baroda</td>
                      <td>
                        <a aria-label="anchor" href="customer-order-view.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-12 col-xxl-6 d-flex align-items-stretch">
          <div class="card w-100 rounded-4">
            <div class="card-body">
              <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="">
                  <h5 class="mb-0">Packaging List</h5>
                </div>
                <div class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                    data-bs-toggle="dropdown">
                    <span class="material-icons-outlined fs-5">more_vert</span>
                  </a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                    <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                    <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                  </ul>
                </div>
              </div>
              <div class="table-responsive">
                <table id="example2" class="table table-striped">
                  <thead class="table-light">
                    <tr>
                      <th>
                        <input class="form-check-input" type="checkbox">
                      </th>
                      <th>Order Id</th>
                      <th>Customer Name</th>
                      <th>Ordered Date</th>
                      <th>Package Pdf</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <input class="form-check-input" type="checkbox">
                      </td>
                      <td>#001</td>
                      <td>
                        <p class="mb-0 customer-name fw-bold">ABC</p>

                      </td>
                      <td>
                        2025-04-11
                      </td>
                      <td>BK159.pdf</td>
                      <td class="text-success">Completed</td>
                      <td>
                        <a aria-label="anchor" href="packing-products-list.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <input class="form-check-input" type="checkbox">
                      </td>
                      <td>#002</td>
                      <td>
                        <p class="mb-0 customer-name fw-bold">XYZ</p>
                      </td>
                      <td>
                        2025-04-11
                      </td>
                      <td>BK158.pdf</td>
                      <td class="text-primary">Pending</td>
                      <td>
                        <a aria-label="anchor" href="packing-products-list.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <input class="form-check-input" type="checkbox">
                      </td>
                      <td>#003</td>
                      <td>
                        <p class="mb-0 customer-name fw-bold">EFG</p>
                      </td>
                      <td>
                        2025-04-11
                      </td>
                      <td>BK157.pdf</td>
                      <td class="text-danger">Issue</td>
                      <td>
                        <a aria-label="anchor" href="packing-products-list.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-12 col-xxl-6 d-flex align-items-stretch">
          <div class="card w-100 rounded-4">
            <div class="card-body">
              <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="">
                  <h5 class="mb-0">Ready To Ship</h5>

                </div>
                <div class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                    data-bs-toggle="dropdown">
                    <span class="material-icons-outlined fs-5">more_vert</span>
                  </a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                    <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                    <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                  </ul>
                </div>
              </div>
              <div class="table-responsive">
                <table id="example2" class="table table-striped">
                  <thead class="table-light">
                    <tr>
                      <th>
                        <input class="form-check-input" type="checkbox">
                      </th>
                      <th>Order Id</th>
                      <th>Customer Name</th>
                      <th>Ordered Date</th>
                      <th>Delivery Date</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <input class="form-check-input" type="checkbox">
                      </td>
                      <td>#001</td>
                      <td>
                        <p class="mb-0 customer-name fw-bold">ABC</p>

                      </td>
                      <td>
                        2025-04-11
                      </td>
                      <td>
                        2025-05-15
                      </td>
                      <td class="text-success">Completed</td>
                      <td>
                        <a aria-label="anchor" href="ready-to-ship-detail.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <input class="form-check-input" type="checkbox">
                      </td>
                      <td>#002</td>
                      <td>
                        <p class="mb-0 customer-name fw-bold">XYZ</p>
                      </td>
                      <td>
                        2025-04-11
                      </td>
                      <td>
                        2025-05-15
                      </td>
                      <td class="text-primary">Delivered</td>
                      <td>
                        <a aria-label="anchor" href="ready-to-ship-detail.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <input class="form-check-input" type="checkbox">
                      </td>
                      <td>#003</td>
                      <td>
                        <p class="mb-0 customer-name fw-bold">EFG</p>
                      </td>
                      <td>
                        2025-04-11
                      </td>
                      <td>
                        2025-05-15
                      </td>
                      <td class="text-Secondary">Out For Delivery</td>
                      <td>
                        <a aria-label="anchor" href="ready-to-ship-detail.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-12 col-xxl-6 d-flex align-items-stretch">
          <div class="card w-100 rounded-4">
            <div class="card-body">
              <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="">
                  <h5 class="mb-0">Invoices</h5>

                </div>
                <div class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                    data-bs-toggle="dropdown">
                    <span class="material-icons-outlined fs-5">more_vert</span>
                  </a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                    <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                    <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                  </ul>
                </div>
              </div>
              <div class="table-responsive">
                <table id="example2" class="table table-striped">
                  <thead class="table-light">
                    <tr>
                      <th>
                        <input class="form-check-input" type="checkbox">
                      </th>
                      <th>Order Id</th>
                      <th>Invoice No</th>
                      <th>Customer Name</th>
                      <th>Due Date</th>
                      <th>Amount</th>
                      <th>Paid</th>
                      <th>Amount Due</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <input class="form-check-input" type="checkbox">
                      </td>
                      <td>#001</td>
                      <td>INV0001</td>
                      <td>ABC</td>
                      </td>
                      <td>
                        2025-04-11
                      </td>
                      <td>10,000</td>
                      <td>8,000</td>
                      <td>2,000</td>
                      <td class="text-success">Completed</td>
                      <td>
                        <a aria-label="anchor" href="invoices-details.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <input class="form-check-input" type="checkbox">
                      </td>
                      <td>#002</td>
                      <td>INV0002</td>
                      <td>EFG</td>
                      <td>
                        2025-04-11
                      </td>
                      <td>10,000</td>
                      <td>8,000</td>
                      <td>2,000</td>
                      <td class="text-primary">Pending</td>
                      <td>
                        <a aria-label="anchor" href="invoices-details.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <input class="form-check-input" type="checkbox">
                      </td>
                      <td>#003</td>
                      <td>INV0003</td>
                      <td>XYZ</td>
                      <td>
                        2025-04-11
                      </td>
                      <td>10,000</td>
                      <td>8,000</td>
                      <td>2,000</td>
                      <td class="text-danger">Issue</td>
                      <td>
                        <a aria-label="anchor" href="invoices-details.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-12 col-xxl-6 d-flex align-items-stretch">
          <div class="card w-100 rounded-4">
            <div class="card-body">
              <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="">
                  <h5 class="mb-0">Payments</h5>

                </div>
                <div class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                    data-bs-toggle="dropdown">
                    <span class="material-icons-outlined fs-5">more_vert</span>
                  </a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                    <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                    <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                  </ul>
                </div>
              </div>
              <div class="table-responsive">
                <table id="example2" class="table table-striped">
                  <thead class="table-light">
                    <tr>
                      <th>
                        <input class="form-check-input" type="checkbox">
                      </th>
                      <th>Order Id</th>
                      <th>Customer Name</th>
                      <th>Ordered Date</th>
                      <th>Delivery Date</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <input class="form-check-input" type="checkbox">
                      </td>
                      <td>#001</td>
                      <td>
                        <p class="mb-0 customer-name fw-bold">ABC</p>

                      </td>
                      <td>
                        2025-04-11
                      </td>
                      <td>
                        2025-05-15
                      </td>
                      <td class="text-success">Completed</td>
                      <td>
                        <a aria-label="anchor" href="ready-to-ship-detail.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <input class="form-check-input" type="checkbox">
                      </td>
                      <td>#002</td>
                      <td>
                        <p class="mb-0 customer-name fw-bold">XYZ</p>
                      </td>
                      <td>
                        2025-04-11
                      </td>
                      <td>
                        2025-05-15
                      </td>
                      <td class="text-primary">Delivered</td>
                      <td>
                        <a aria-label="anchor" href="ready-to-ship-detail.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <input class="form-check-input" type="checkbox">
                      </td>
                      <td>#003</td>
                      <td>
                        <p class="mb-0 customer-name fw-bold">EFG</p>
                      </td>
                      <td>
                        2025-04-11
                      </td>
                      <td>
                        2025-05-15
                      </td>
                      <td class="text-Secondary">Out For Delivery</td>
                      <td>
                        <a aria-label="anchor" href="ready-to-ship-detail.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-12 col-xxl-6 d-flex align-items-stretch">
          <div class="card w-100 rounded-4">
            <div class="card-body">
              <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="">
                  <h5 class="mb-0">Appointments</h5>

                </div>
                <div class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                    data-bs-toggle="dropdown">
                    <span class="material-icons-outlined fs-5">more_vert</span>
                  </a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                    <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                    <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                  </ul>
                </div>
              </div>
              <div class="table-responsive">
                <table id="example2" class="table table-striped">
                  <thead class="table-light">
                    <tr>
                      <th>
                        <input class="form-check-input" type="checkbox">
                      </th>
                      <th>Order Id</th>
                      <th>Customer Name</th>
                      <th>Ordered Date</th>
                      <th>Delivery Date</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <input class="form-check-input" type="checkbox">
                      </td>
                      <td>#001</td>
                      <td>
                        <p class="mb-0 customer-name fw-bold">ABC</p>

                      </td>
                      <td>
                        2025-04-11
                      </td>
                      <td>
                        2025-05-15
                      </td>
                      <td class="text-success">Completed</td>
                      <td>
                        <a aria-label="anchor" href="ready-to-ship-detail.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <input class="form-check-input" type="checkbox">
                      </td>
                      <td>#002</td>
                      <td>
                        <p class="mb-0 customer-name fw-bold">XYZ</p>
                      </td>
                      <td>
                        2025-04-11
                      </td>
                      <td>
                        2025-05-15
                      </td>
                      <td class="text-primary">Delivered</td>
                      <td>
                        <a aria-label="anchor" href="ready-to-ship-detail.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <input class="form-check-input" type="checkbox">
                      </td>
                      <td>#003</td>
                      <td>
                        <p class="mb-0 customer-name fw-bold">EFG</p>
                      </td>
                      <td>
                        2025-04-11
                      </td>
                      <td>
                        2025-05-15
                      </td>
                      <td class="text-Secondary">Out For Delivery</td>
                      <td>
                        <a aria-label="anchor" href="ready-to-ship-detail.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-12 col-xxl-6 d-flex align-items-stretch">
          <div class="card w-100 rounded-4">
            <div class="card-body">
              <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="">
                  <h5 class="mb-0">GRNs</h5>

                </div>
                <div class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                    data-bs-toggle="dropdown">
                    <span class="material-icons-outlined fs-5">more_vert</span>
                  </a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                    <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                    <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                  </ul>
                </div>
              </div>
              <div class="table-responsive">
                <table id="example2" class="table table-striped">
                  <thead class="table-light">
                    <tr>
                      <th>
                        <input class="form-check-input" type="checkbox">
                      </th>
                      <th>Order Id</th>
                      <th>Customer Name</th>
                      <th>Ordered Date</th>
                      <th>Delivery Date</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <input class="form-check-input" type="checkbox">
                      </td>
                      <td>#001</td>
                      <td>
                        <p class="mb-0 customer-name fw-bold">ABC</p>

                      </td>
                      <td>
                        2025-04-11
                      </td>
                      <td>
                        2025-05-15
                      </td>
                      <td class="text-success">Completed</td>
                      <td>
                        <a aria-label="anchor" href="ready-to-ship-detail.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <input class="form-check-input" type="checkbox">
                      </td>
                      <td>#002</td>
                      <td>
                        <p class="mb-0 customer-name fw-bold">XYZ</p>
                      </td>
                      <td>
                        2025-04-11
                      </td>
                      <td>
                        2025-05-15
                      </td>
                      <td class="text-primary">Delivered</td>
                      <td>
                        <a aria-label="anchor" href="ready-to-ship-detail.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <input class="form-check-input" type="checkbox">
                      </td>
                      <td>#003</td>
                      <td>
                        <p class="mb-0 customer-name fw-bold">EFG</p>
                      </td>
                      <td>
                        2025-04-11
                      </td>
                      <td>
                        2025-05-15
                      </td>
                      <td class="text-Secondary">Out For Delivery</td>
                      <td>
                        <a aria-label="anchor" href="ready-to-ship-detail.php" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                          </svg>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>



    </div>
  </main>
  <!--end main wrapper-->
<!--bootstrap js-->
  <script src="assets/js/bootstrap.bundle.min.js"></script>

  <!--plugins-->
  <script src="assets/js/jquery.min.js"></script>
  <!--plugins-->
  <script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
  <script src="assets/plugins/metismenu/metisMenu.min.js"></script>
  <script src="assets/plugins/apexchart/apexcharts.min.js"></script>
  <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
  <script src="assets/plugins/peity/jquery.peity.min.js"></script>
  <script>
    $(".data-attributes span").peity("donut")
  </script>
  <script src="assets/js/main.js"></script>
  <script src="assets/js/dashboard1.js"></script>
  <script>
    new PerfectScrollbar(".user-list")
  </script>
 @endsection