@extends('layouts.master')
@section('main-content')
<main class="main-wrapper">
        <div class="main-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Inventory Stock Report</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="col">
                <div class="row">
                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-white sale-widget flex-fill">
                            <div class="card-body d-flex align-items-center">
                                <span class="sale-icon bg-white text-primary">
                                    <i class="ti ti-file-text fs-24"></i>
                                </span>
                                <div class="ms-2">
                                    <p class="text-dark mb-1">Total Product Available</p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">32198</h4>
                                        <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-white sale-widget flex-fill">
                            <div class="card-body d-flex align-items-center">
                                <span class="sale-icon bg-white text-primary">
                                    <i class="ti ti-file-text fs-24"></i>
                                </span>
                                <div class="ms-2">
                                    <p class="text-dark mb-1">Total Hold Products</p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">3485</h4>
                                        <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-white sale-widget flex-fill">
                            <div class="card-body d-flex align-items-center">
                                <span class="sale-icon bg-white text-primary">
                                    <i class="ti ti-file-text fs-24"></i>
                                </span>
                                <div class="ms-2">
                                    <p class="text-dark mb-1">Total Damage Products</p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">204</h4>
                                        <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12 d-flex">
                        <div class="card bg-white sale-widget flex-fill">
                            <div class="card-body d-flex align-items-center">
                                <span class="sale-icon bg-white text-primary">
                                    <i class="ti ti-file-text fs-24"></i>
                                </span>
                                <div class="ms-2">
                                    <p class="text-dark mb-1">Total Missing Products</p>
                                    <div class="d-inline-flex align-items-center flex-wrap gap-2">
                                        <h4 class="text-dark">241</h4>
                                        <!-- <span class="badge badge-soft-primary text-dark"><i class="ti ti-arrow-up me-1"></i>+22%</span> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card mt-4">
                <div class="card-body pb-1">
                    <form action="customer-report.html">
                        <div class="row align-items-end">
                            <div class="col-lg-10">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Choose Date</label>
                                            <div class="input-icon-start position-relative">
                                                <input type="text" class="form-control date-range bookingrange" placeholder="dd/mm/yyyy - dd/mm/yyyy">
                                                <span class="input-icon-left">
                                                    <i class="ti ti-calendar"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Vendor Name</label>
                                            <select id="status" class="form-select">
                                                <option disabled selected>-- Select --</option>
                                                <option>Carl</option>
                                                <option>Minerva</option>
                                                <option>Robert </option>
                                                <option>Evans</option>
                                                <option>Rameriz</option>
                                                <option>Lamon</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Product Status</label>
                                            <select id="status" class="form-select">
                                                <option disabled selected>-- Select --</option>
                                                <option>Available</option>
                                                <option>Hold</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Sort</label>
                                            <select id="status" class="form-select">
                                                <option disabled selected>-- Select --</option>
                                                <option>Asc</option>
                                                <option>Dce</option>
                                                <option>High To Low</option>
                                                <option>Low To High</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <a href="#" class="btn btn-danger w-100" type="">Generate Report</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


            <div class="card mt-4">
                <div class="card-body">
                    <div class="product-table">
                        <div class="table-responsive white-space-nowrap">
                            <table id="example" class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <input class="form-check-input" type="checkbox">
                                        </th>
                                        <th>Product Name</th>
                                        <th>SKU</th>
                                        <th>Price</th>
                                        <th>Total Quantity</th>
                                        <th>Available Quantity</th>
                                        <th>Hold Quantity</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">

                                                <div class="product-info">
                                                    <a href="javascript:;" class="product-title">Women Pink Floral Printed</a>

                                                </div>
                                            </div>
                                        </td>
                                        <td>BOK4897984</td>
                                        <td>49₹</td>


                                        <td>
                                            150
                                        </td>
                                        <td>110</td>
                                        <td>40</td>
                                        <td>
                                            Nov 12, 10:45 PM
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">

                                                <div class="product-info">
                                                    <a href="javascript:;" class="product-title">Women Pink Floral Printed</a>

                                                </div>
                                            </div>
                                        </td>
                                        <td>BOK4897984</td>
                                        <td>49₹</td>


                                        <td>
                                            150
                                        </td>
                                        <td>110</td>
                                        <td>40</td>
                                        <td>
                                            Nov 12, 10:45 PM
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">

                                                <div class="product-info">
                                                    <a href="javascript:;" class="product-title">Women Pink Floral Printed</a>

                                                </div>
                                            </div>
                                        </td>
                                        <td>BOK4897984</td>
                                        <td>49₹</td>


                                        <td>
                                            150
                                        </td>
                                        <td>110</td>
                                        <td>40</td>
                                        <td>
                                            Nov 12, 10:45 PM
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">

                                                <div class="product-info">
                                                    <a href="javascript:;" class="product-title">Women Pink Floral Printed</a>

                                                </div>
                                            </div>
                                        </td>
                                        <td>BOK4897984</td>
                                        <td>49₹</td>


                                        <td>
                                            150
                                        </td>
                                        <td>110</td>
                                        <td>40</td>
                                        <td>
                                            Nov 12, 10:45 PM
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">

                                                <div class="product-info">
                                                    <a href="javascript:;" class="product-title">Women Pink Floral Printed</a>

                                                </div>
                                            </div>
                                        </td>
                                        <td>BOK4897984</td>
                                        <td>49₹</td>


                                        <td>
                                            150
                                        </td>
                                        <td>110</td>
                                        <td>40</td>
                                        <td>
                                            Nov 12, 10:45 PM
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">

                                                <div class="product-info">
                                                    <a href="javascript:;" class="product-title">Women Pink Floral Printed</a>

                                                </div>
                                            </div>
                                        </td>
                                        <td>BOK4897984</td>
                                        <td>49₹</td>


                                        <td>
                                            150
                                        </td>
                                        <td>110</td>
                                        <td>40</td>
                                        <td>
                                            Nov 12, 10:45 PM
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">

                                                <div class="product-info">
                                                    <a href="javascript:;" class="product-title">Women Pink Floral Printed</a>

                                                </div>
                                            </div>
                                        </td>
                                        <td>BOK4897984</td>
                                        <td>49₹</td>


                                        <td>
                                            150
                                        </td>
                                        <td>110</td>
                                        <td>40</td>
                                        <td>
                                            Nov 12, 10:45 PM
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">

                                                <div class="product-info">
                                                    <a href="javascript:;" class="product-title">Women Pink Floral Printed</a>

                                                </div>
                                            </div>
                                        </td>
                                        <td>BOK4897984</td>
                                        <td>49₹</td>


                                        <td>
                                            150
                                        </td>
                                        <td>110</td>
                                        <td>40</td>
                                        <td>
                                            Nov 12, 10:45 PM
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input" type="checkbox">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">

                                                <div class="product-info">
                                                    <a href="javascript:;" class="product-title">Women Pink Floral Printed</a>

                                                </div>
                                            </div>
                                        </td>
                                        <td>BOK4897984</td>
                                        <td>49₹</td>


                                        <td>
                                            150
                                        </td>
                                        <td>110</td>
                                        <td>40</td>
                                        <td>
                                            Nov 12, 10:45 PM
                                        </td>

                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </main>
@endsection
    

