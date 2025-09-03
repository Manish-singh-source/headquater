<!doctype html>
<html lang="en" data-bs-theme="blue-theme">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HEADQUATERS | Admin Dashboard</title>
    <!--favicon-->
    <link rel="icon" href="assets/images/favicon-32x32.png" type="image/png">
    <!-- loader-->
    <link href="assets/css/pace.min.css" rel="stylesheet">
    <script src="assets/js/pace.min.js"></script>

    <!--plugins-->
    <link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/plugins/metismenu/metisMenu.min.css">
    <link rel="stylesheet" type="text/css" href="assets/plugins/metismenu/mm-vertical.css">
    <!--bootstrap css-->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&amp;display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
    <!--main css-->
    <link href="assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="sass/main.css" rel="stylesheet">
    <link href="sass/dark-theme.css" rel="stylesheet">
    <link href="sass/blue-theme.css" rel="stylesheet">
    <link href="sass/responsive.css" rel="stylesheet">

</head>

<body class="d-flex align-items-center justify-content-center min-vh-100 ">
    <div class="mx-3 mx-lg-0">
        
        <div class="card my-5 col-xl-9 col-xxl-8 mx-auto rounded-4 overflow-hidden p-4">
            <div class="row g-4">
                <div class="col-lg-6 d-flex">
                    <div class="card-body">
                        <img src="assets/images/logo1.png" class="mb-4" width="145" alt="">
                        <h4 class="fw-bold">Get Started Now</h4>
                        <p class="mb-0">Enter your credentials to login your account</p>

                        <div class="form-body mt-4">
                            <form class="row g-3" action="{{ route('login.auth.check') }}" method="POST">
                                @csrf
                                @method('POST')
                                <div class="col-12">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email"
                                        @error('email') aria-describedby="validationServer05Feedback"  @enderror
                                        placeholder="example@gmail.com">
                                    @error('email')
                                        <div id="validationServer05Feedback" class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group" id="show_hide_password">
                                        <input type="password"
                                            class="form-control border-end-0 @error('password') is-invalid @enderror"
                                            id="password" name="password"
                                            @error('password') aria-describedby="validationServer06Feedback"  @enderror
                                            placeholder="Enter Password">
                                        <a href="javascript:;" class="input-group-text bg-transparent text-white"><i
                                                class="bi bi-eye-slash-fill"></i></a>
                                        @error('password')
                                            <div id="validationServer05Feedback" class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked"
                                            checked>
                                        <label class="form-check-label" for="flexSwitchCheckChecked">Remember Me</label>
                                    </div>
                                </div>
                                <div class="col-md-6 text-end"> <a href="auth-boxed-forgot-password.html">Forgot
                                        Password ?</a>
                                </div>
                                <div class="col-12">
                                    <div class="d-grid">
                                        <input type="submit" class="btn btn-grd-info" value="Login" name="submit">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="text-start">
                                        <p class="mb-0">Don't have an account yet? <a
                                                href="{{ route('register') }}">Sign up here</a>
                                        </p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-lg-flex d-none">
                    <div class="p-3 rounded-4 w-100 d-flex align-items-center justify-content-center bg-grd-info">
                        <img src="assets/images/auth/login1.png" class="img-fluid" alt="">
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!--plugins-->
    <script src="assets/js/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $("#show_hide_password a").on('click', function(event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bi-eye-slash-fill");
                    $('#show_hide_password i').removeClass("bi-eye-fill");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bi-eye-slash-fill");
                    $('#show_hide_password i').addClass("bi-eye-fill");
                }
            });
        });
    </script>

</body>

</html>
