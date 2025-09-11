<!doctype html>
<html lang="en" data-bs-theme="blue-theme">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HEADQUATERS | Admin Dashboard</title>
    <!--favicon-->
    <link rel="icon" href="{{ asset('assets/images/favicon-32x32.png') }}" type="image/png">
    <!-- loader-->
    <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/js/pace.min.js') }}"></script>

    <!--plugins-->
    <link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/metismenu/metisMenu.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/metismenu/mm-vertical.css') }}">
    <!--bootstrap css-->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&amp;display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
    <!--main css-->
    <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="{{ asset('sass/main.css') }}" rel="stylesheet">
    <link href="{{ asset('sass/dark-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('sass/blue-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('sass/responsive.css') }}" rel="stylesheet">

</head>

<body class="d-flex align-items-center justify-content-center min-vh-100" >

    <div class="mx-3 mx-lg-0">

        <div class="card my-5 col-xl-9 col-xxl-8 mx-auto rounded-4 overflow-hidden border-3 p-4">
            <div class="row g-4">
                <div class="col-lg-6 d-flex">
                    <div class="card-body">
                        <img src="{{ asset('assets/images/logo1.png') }}" class="mb-4" width="145" alt="">
                        <h4 class="fw-bold">Get Started Now</h4>
                        <p class="mb-0">Enter your credentials to login your account</p>

                        <div class="form-body mt-4">
                            <form class="row g-3" action="{{ route('register.store') }}" method="POST">
                                @csrf 
                                @method('POST')

                                <div class="col-6">
                                    <label for="firstName" class="form-label">First Name</label>
                                    <input type="hidden" class="form-control" name="role_id" id="firstName" value="1" placeholder="John">
                                    <input type="text" class="form-control" name="firstName" id="firstName" placeholder="John">
                                </div>
                                <div class="col-6">
                                    <label for="lastName" class="form-label">Last name</label>
                                    <input type="text" class="form-control" name="lastName" id="lastName" placeholder="Doe">
                                </div>
                                <div class="col-6">
                                    <label for="email" class="form-label">Email Id</label>
                                    <input type="email" class="form-control" name="email" id="email"
                                        placeholder="example@user.com">
                                </div>
                                <div class="col-6">
                                    <label for="phoneNo" class="form-label">Phone No</label>
                                    <input type="text" class="form-control" name="phoneNo" id="phoneNo"
                                        placeholder="+91 123 456 7895">
                                </div>
                                <div class="col-12">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group" id="show_hide_password">
                                        <input type="password" class="form-control border-end-0"
                                            id="password" name="password" value="12345678" placeholder="Enter Password">
                                        <a href="javascript:;" class="input-group-text bg-transparent"><i
                                                class="bi bi-eye-slash-fill"></i></a>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <div class="input-group" id="show_hide_password">
                                        <input type="password" class="form-control border-end-0"
                                            id="password_confirmation" name="password_confirmation" value="12345678" placeholder="Confirm Password">
                                        <a href="javascript:;" class="input-group-text bg-transparent"><i
                                                class="bi bi-eye-slash-fill"></i></a>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">
                                        <label class="form-check-label" for="flexSwitchCheckChecked">I read and agree
                                            to Terms &amp; Conditions</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-grid">
                                        <input type="submit" class="btn btn-grd-info" value="Register" name="submit">
                                        {{-- <a href="{{ route('register') }}" type="submit"
                                            class="btn btn-grd-info">Register</a> --}}
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="text-start">
                                        <p class="mb-0">Already have an account? <a href="{{ route('login') }}">Sign
                                                in here</a></p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-lg-flex d-none">
                    <div class="p-3 rounded-4 w-100 d-flex align-items-center justify-content-center bg-grd-info">
                        <img src="{{ asset('assets/images/auth/register1.png') }}" class="img-fluid" alt="">
                    </div>
                </div>
            </div>
        </div>

    </div>

</html>
