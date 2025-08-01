@extends('layouts.master')
@section('main-content')
    <style>
        .loader {
            position: absolute;
            top: 50%;
            left: 50%;
            background-color: #333;
            transform: translateX(-50%);
            transform: translateY(-50%);
            font-weight: bold;
            color: #fff;
            text-align: center;
            padding: 10px;
            z-index: 222;
        }
    </style>

    <!-- Loader Element -->
    {{-- <div class="loader">Loading...</div> --}}

    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <div class="row">
                <form action="{{ route('customer.groups.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')

                    <div class="col-12">
                        <div class="row">
                            <div class="col">
                                <div class="card customer-inputs">
                                    <div class="card-header border-bottom-dashed">
                                        <div class="d-flex g-4 flex-row align-items-center justify-content-between">
                                            <div>
                                                <h5 class="card-title mb-0">
                                                    Add Customers Group
                                                </h5>
                                            </div>
                                            {{-- <div>
                                                <b>
                                                    #0081
                                                </b>
                                            </div> --}}
                                        </div>
                                    </div>

                                    <div class="card-body">
                                        <div class="row g-3 align-items-end">
                                            <div class="col-12">
                                                <span><b>Group:</b></span>
                                            </div>

                                            {{-- <div class="col-12 col-lg-3">
                                                <label for="marital" class="form-label">Customer Group Name
                                                    <span class="text-danger">*</span></label>
                                                <input type="text" name="customer_id" class="form-control"
                                                    placeholder="Enter Group Name" id="groupName">
                                            </div> --}}
                                            <div class="col-12 col-lg-3">
                                                <label for="name" class="form-label">Customer Group Name
                                                    <span class="text-danger">*</span></label>
                                                <input type="text"
                                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                                    id="name" placeholder="Enter Group Name"
                                                    aria-describedby="nameFeedback" required>

                                                @error('name')
                                                    <div id="nameFeedback" class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-lg-3">
                                                <label for="csv_file" class="form-label">Customers Data (Excel/CSV) <span
                                                        class="text-danger">*</span></label>
                                                <input type="file" name="csv_file" id="csv_file" class="form-control @error('csv_file') is-invalid @enderror"
                                                    value="" required="" placeholder="Upload ID Document"
                                                    aria-describedby="csv_fileFeedback">

                                                @error('csv_file')
                                                    <div id="csv_fileFeedback" class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-12 col-lg-1">
                                                <input type="submit" class="btn border-2 border-primary" id="upload-excel"
                                                    value="Submit">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
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
    <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        new PerfectScrollbar(".customer-notes")
    </script>
    <!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script> -->
@endsection
