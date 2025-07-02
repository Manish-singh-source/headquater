@extends('layouts.master')
@section('main-content')
    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Amazon</li>
                        </ol>
                    </nav>
                </div>

            </div>
            <!--end breadcrumb-->


            <div class="row">
                <div class="col-12">
                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="customer-table">
                                <div class="table-responsive white-space-nowrap">
                                    <table id="example2" class="table table-striped">
                                        <thead class="table-light">
                                            <tr>
                                                <th>
                                                    <input class="form-check-input" type="checkbox">
                                                </th>
                                                <th>Client Name</th>
                                                <th>Contact Name</th>
                                                <th>Email</th>
                                                <th>Contact Number</th>
                                                <th>Shipping Address</th>
                                                <th>Billing Address</th>
                                                <th>Orders</th>
                                                <th>Joined At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($customers as $customer)
                                                <tr>
                                                    <td>
                                                        <input class="form-check-input" type="checkbox">
                                                    </td>
                                                    <td>
                                                        <a class="d-flex align-items-center gap-3"
                                                            href="customer-detail.php">
                                                            <p class="mb-0 customer-name fw-bold">
                                                                {{ $customer->company_name }}</p>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a class="d-flex align-items-center gap-3"
                                                            href="customer-detail.php">
                                                            <p class="mb-0 customer-name fw-bold">
                                                                {{ $customer->first_name }} {{ $customer->last_name }}</p>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="javascript:;" class="font-text1">{{ $customer->email }}</a>
                                                    </td>
                                                    <td>{{ $customer->phone }}</td>
                                                    <td>{{ $customer->shipping_address }}</td>
                                                    <td>{{ $customer->billing_address }}</td>
                                                    <td>142</td>
                                                    <td>{{ $customer->created_at }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!--end row-->




        </div>
    </main>
    <!--end main wrapper-->
@endsection
