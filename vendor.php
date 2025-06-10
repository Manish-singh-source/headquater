<!doctype html>
<html lang="en" >


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
    <link rel="stylesheet" type="text/css" href="assets/plugins/simplebar/css/simplebar.css">
    <!--bootstrap css-->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&amp;display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
    <!--main css-->
    <link href="assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="sass/main.css" rel="stylesheet">
    <link href="sass/dark-theme.css" rel="stylesheet">
    <link href="sass/blue-theme.css" rel="stylesheet">
    <link href="sass/semi-dark.css" rel="stylesheet">
    <link href="sass/bordered-theme.css" rel="stylesheet">
    <link href="sass/responsive.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>

    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>

    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Components</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Vendors</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary">Settings</button>
                        <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end"> <a class="dropdown-item" href="javascript:;">Action</a>
                            <a class="dropdown-item" href="javascript:;">Another action</a>
                            <a class="dropdown-item" href="javascript:;">Something else here</a>
                            <div class="dropdown-divider"></div> <a class="dropdown-item" href="javascript:;">Separated link</a>
                        </div>
                    </div>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="product-count d-flex align-items-center gap-3 gap-lg-4 mb-4 fw-bold flex-wrap font-text1">
                <a href="javascript:;"><span class="me-1">All</span><span class="text-secondary">(85472)</span></a>
                <a href="javascript:;"><span class="me-1">New</span><span class="text-secondary">(145)</span></a>
                <a href="javascript:;"><span class="me-1">Checkouts</span><span class="text-secondary">(89)</span></a>
                <a href="javascript:;"><span class="me-1">Locals</span><span class="text-secondary">(5872)</span></a>
                <a href="javascript:;"><span class="me-1">Subscribers</span><span class="text-secondary">(163)</span></a>
                <a href="javascript:;"><span class="me-1">Top Reviews</span><span class="text-secondary">(8)</span></a>
            </div>

            <div class="row g-3">
                <div class="col-auto">
                    <div class="position-relative">
                        <input class="form-control px-5" type="search" placeholder="Search Customers">
                        <span class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50 fs-5">search</span>
                    </div>
                </div>
                <div class="col-auto flex-grow-1 overflow-auto">
                    <div class="btn-group position-static">
                        <div class="btn-group position-static">
                            <button type="button" class="btn btn-filter dropdown-toggle px-4" data-bs-toggle="dropdown" aria-expanded="false">
                                Country
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                            </ul>
                        </div>
                        <div class="btn-group position-static">
                            <button type="button" class="btn btn-filter dropdown-toggle px-4" data-bs-toggle="dropdown" aria-expanded="false">
                                Source
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                            </ul>
                        </div>
                        <div class="btn-group position-static">
                            <button type="button" class="btn btn-filter dropdown-toggle px-4" data-bs-toggle="dropdown" aria-expanded="false">
                                More Filters
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                                <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="d-flex align-items-center gap-2 justify-content-lg-end">
                        <button class="btn btn-filter px-4"><i class="bi bi-box-arrow-right me-2"></i>Export</button>
                        <a href="create-vendor.php"><button class="btn btn-primary px-4"><i class="bi bi-plus-lg me-2"></i>Add Vendor</button></a>
                    </div>
                </div>
            </div><!--end row-->

            <div class="card mt-5">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example2" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Vendor Name</th>
                                    <th>Phone No</th>
                                    <th>Email Id</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Rahul Sharma</td>
                                    <td>9876543210</td>
                                    <td>rahul.sharma@email.com</td>
                                    <td>Mumbai</td>
                                    <td>Active</td>
                                    <td class="text-center"><button class="btn"><i class="text-primary" data-feather="eye"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="edit"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="trash-2"></i></button>
                                </td>
                                </tr>
                                <tr>
                                    <td>Priya Verma</td>
                                    <td>9823456710</td>
                                    <td>priya.verma@email.com</td>
                                    <td>Delhi</td>
                                    <td>Inactive</td>
                                    <td class="text-center"><button class="btn"><i class="text-primary" data-feather="eye"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="edit"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="trash-2"></i></button>
                                </td>
                                </tr>
                                <tr>
                                    <td>Amit Patel</td>
                                    <td>9898765432</td>
                                    <td>amit.patel@email.com</td>
                                    <td>Ahmedabad</td>
                                    <td>Active</td>
                                    <td class="text-center"><button class="btn"><i class="text-primary" data-feather="eye"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="edit"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="trash-2"></i></button>
                                </td>
                                </tr>
                                <tr>
                                    <td>Sneha Iyer</td>
                                    <td>9812345678</td>
                                    <td>sneha.iyer@email.com</td>
                                    <td>Chennai</td>
                                    <td>Active</td>
                                    <td class="text-center"><button class="btn"><i class="text-primary" data-feather="eye"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="edit"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="trash-2"></i></button>
                                </td>
                                </tr>
                                <tr>
                                    <td>Vikram Desai</td>
                                    <td>9988776655</td>
                                    <td>vikram.desai@email.com</td>
                                    <td>Pune</td>
                                    <td>Inactive</td>
                                    <td class="text-center"><button class="btn"><i class="text-primary" data-feather="eye"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="edit"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="trash-2"></i></button>
                                </td>
                                </tr>
                                <tr>
                                    <td>Kavita Mehta</td>
                                    <td>9900112233</td>
                                    <td>kavita.mehta@email.com</td>
                                    <td>Jaipur</td>
                                    <td>Active</td>
                                    <td class="text-center"><button class="btn"><i class="text-primary" data-feather="eye"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="edit"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="trash-2"></i></button>
                                </td>
                                </tr>
                                <tr>
                                    <td>Arjun Reddy</td>
                                    <td>9871234560</td>
                                    <td>arjun.reddy@email.com</td>
                                    <td>Hyderabad</td>
                                    <td>Active</td>
                                    <td class="text-center"><button class="btn"><i class="text-primary" data-feather="eye"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="edit"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="trash-2"></i></button>
                                </td>
                                </tr>
                                <tr>
                                    <td>Neha Kapoor</td>
                                    <td>9911223344</td>
                                    <td>neha.kapoor@email.com</td>
                                    <td>Kolkata</td>
                                    <td>Inactive</td>
                                    <td class="text-center"><button class="btn"><i class="text-primary" data-feather="eye"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="edit"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="trash-2"></i></button>
                                </td>
                                </tr>
                                <tr>
                                    <td>Rohan Gupta</td>
                                    <td>9822334455</td>
                                    <td>rohan.gupta@email.com</td>
                                    <td>Surat</td>
                                    <td>Active</td>
                                    <td class="text-center"><button class="btn"><i class="text-primary" data-feather="eye"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="edit"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="trash-2"></i></button>
                                </td>
                                </tr>
                                <tr>
                                    <td>Anjali Nair</td>
                                    <td>9845123456</td>
                                    <td>anjali.nair@email.com</td>
                                    <td>Thiruvananthapuram</td>
                                    <td>Active</td>
                                    <td class="text-center"><button class="btn"><i class="text-primary" data-feather="eye"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="edit"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="trash-2"></i></button>
                                </td>
                                </tr>



                                <tr>
                                    <td>Deepak Joshi</td>
                                    <td>9765432109</td>
                                    <td>deepak.joshi@email.com</td>
                                    <td>Indore</td>
                                    <td>Active</td>
                                    <td class="text-center"><button class="btn"><i class="text-primary" data-feather="eye"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="edit"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="trash-2"></i></button>
                                </td>
                                </tr>
                                <tr>
                                    <td>Meena Kumari</td>
                                    <td>9756432189</td>
                                    <td>meena.kumari@email.com</td>
                                    <td>Lucknow</td>
                                    <td>Inactive</td>
                                    <td class="text-center"><button class="btn"><i class="text-primary" data-feather="eye"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="edit"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="trash-2"></i></button>
                                </td>
                                </tr>
                                <tr>
                                    <td>Alok Mishra</td>
                                    <td>9743210897</td>
                                    <td>alok.mishra@email.com</td>
                                    <td>Bhopal</td>
                                    <td>Active</td>
                                    <td class="text-center"><a href="vendor-detail.php"><button class="btn"><i class="text-primary" data-feather="eye"></i></button></a>
                                    <button class="btn"><i class="text-primary" data-feather="edit"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="trash-2"></i></button>
                                </td>
                                </tr>
                                <tr>
                                    <td>Swati Singh</td>
                                    <td>9723456781</td>
                                    <td>swati.singh@email.com</td>
                                    <td>Patna</td>
                                    <td>Inactive</td>
                                    <td class="text-center"><button class="btn"><i class="text-primary" data-feather="eye"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="edit"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="trash-2"></i></button>
                                </td>
                                </tr>
                                <tr>
                                    <td>Rajeev Chauhan</td>
                                    <td>9712345672</td>
                                    <td>rajeev.chauhan@email.com</td>
                                    <td>Gurgaon</td>
                                    <td>Active</td>
                                    <td class="text-center"><button class="btn"><i class="text-primary" data-feather="eye"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="edit"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="trash-2"></i></button>
                                </td>
                                </tr>
                                <tr>
                                    <td>Ishita Banerjee</td>
                                    <td>9909876543</td>
                                    <td>ishita.banerjee@email.com</td>
                                    <td>Kolkata</td>
                                    <td>Active</td>
                                    <td class="text-center"><button class="btn"><i class="text-primary" data-feather="eye"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="edit"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="trash-2"></i></button>
                                </td>
                                </tr>
                                <tr>
                                    <td>Manish Kumar</td>
                                    <td>9890123456</td>
                                    <td>manish.kumar@email.com</td>
                                    <td>Noida</td>
                                    <td>Inactive</td>
                                    <td class="text-center"><button class="btn"><i class="text-primary" data-feather="eye"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="edit"></i></button>
                                    <button class="btn"><i class="text-primary" data-feather="trash-2"></i></button>
                                </td>
                                </tr>



                            </tbody>

                        </table>
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
    <script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
    <script>
        $(document).ready(function() {
            var table = $('#example2').DataTable({
                lengthChange: false,
                buttons: ['excel']
            });

            table.buttons().container()
                .appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    </script>
    <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
	<script>
		feather.replace()
	</script>


</body>



</html>