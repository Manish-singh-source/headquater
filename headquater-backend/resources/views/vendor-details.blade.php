  <!doctype html>
  <html lang="en" data-bs-theme="semi-dark">

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
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
    <!--main css-->
    <link href="assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="sass/main.css" rel="stylesheet">
    <link href="sass/dark-theme.css" rel="stylesheet">
    <link href="sass/blue-theme.css" rel="stylesheet">
    <link href="sass/semi-dark.css" rel="stylesheet">
    <link href="sass/bordered-theme.css" rel="stylesheet">
    <link href="sass/responsive.css" rel="stylesheet">

    <!-- Include Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <style>
      .form-control:focus {
        color: var(--bs-body-color);
        background-color: var(--bs-body-bg);
        border-color: #86b7fe;
        outline: 0;
        box-shadow: 0 0 0 -1.75rem rgba(13, 110, 253, .25);
      }

      .form-select:focus {
        color: var(--bs-body-color);
        background-color: var(--bs-body-bg);
        border-color: #86b7fe;
        outline: 0;
        box-shadow: 0 0 0 -1.75rem rgba(13, 110, 253, .25);
      }
    </style>
  </head>

  <body>
 <!--start header-->
  <header class="top-header">
    <nav class="navbar navbar-expand align-items-center gap-4">
      <div class="btn-toggle">
        <a href="javascript:;"><i class="material-icons-outlined">menu</i></a>
      </div>
      <div class="search-bar flex-grow-1">
        <div class="position-relative">
          <input class="form-control rounded-5 px-5 search-control d-lg-block d-none" type="text" placeholder="Search">
          <span class="material-icons-outlined position-absolute d-lg-block d-none ms-3 translate-middle-y start-0 top-50">search</span>
          <span class="material-icons-outlined position-absolute me-3 translate-middle-y end-0 top-50 search-close">close</span>
          <div class="search-popup p-3">
            <div class="card rounded-4 overflow-hidden">
              <div class="card-header d-lg-none">
                <div class="position-relative">
                  <input class="form-control rounded-5 px-5 mobile-search-control" type="text" placeholder="Search">
                  <span class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50">search</span>
                  <span class="material-icons-outlined position-absolute me-3 translate-middle-y end-0 top-50 mobile-search-close">close</span>
                 </div>
              </div>
              <div class="card-body search-content">
                
              </div>
              
            </div>
          </div>
        </div>
      </div>
      <ul class="navbar-nav gap-1 nav-right-links align-items-center">
        <li class="nav-item d-lg-none mobile-search-btn">
          <a class="nav-link" href="javascript:;"><i class="material-icons-outlined">search</i></a>
        </li>

      
       
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" data-bs-auto-close="outside"
            data-bs-toggle="dropdown" href="javascript:;"><i class="material-icons-outlined">notifications</i>
            <span class="badge-notify">5</span>
          </a>
          <div class="dropdown-menu dropdown-notify dropdown-menu-end shadow">
            <div class="px-3 py-1 d-flex align-items-center justify-content-between border-bottom">
              <h5 class="notiy-title mb-0">Notifications</h5>
              <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle dropdown-toggle-nocaret option" type="button"
                  data-bs-toggle="dropdown" aria-expanded="false">
                  <span class="material-icons-outlined">
                    more_vert
                  </span>
                </button>
                <div class="dropdown-menu dropdown-option dropdown-menu-end shadow">
                  <div><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:;"><i
                        class="material-icons-outlined fs-6">inventory_2</i>Archive All</a></div>
                  <div><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:;"><i
                        class="material-icons-outlined fs-6">done_all</i>Mark all as read</a></div>
                  <div><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:;"><i
                        class="material-icons-outlined fs-6">mic_off</i>Disable Notifications</a></div>
                  <div><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:;"><i
                        class="material-icons-outlined fs-6">grade</i>What's new ?</a></div>
                  <div>
                    <hr class="dropdown-divider">
                  </div>
                  <div><a class="dropdown-item d-flex align-items-center gap-2 py-2" href="javascript:;"><i
                        class="material-icons-outlined fs-6">leaderboard</i>Reports</a></div>
                </div>
              </div>
            </div>
            <div class="notify-list">
              <div>
                <a class="dropdown-item border-bottom py-2" href="javascript:;">
                  <div class="d-flex align-items-center gap-3">
                    <div class="">
                      <img src="assets/images/avatars/01.png" class="rounded-circle" width="45" height="45" alt="">
                    </div>
                    <div class="">
                      <h5 class="notify-title">Congratulations Jhon</h5>
                      <p class="mb-0 notify-desc">Many congtars jhon. You have won the gifts.</p>
                      <p class="mb-0 notify-time">Today</p>
                    </div>
                    <div class="notify-close position-absolute end-0 me-3">
                      <i class="material-icons-outlined fs-6">close</i>
                    </div>
                  </div>
                </a>
              </div>
              <div>
                <a class="dropdown-item border-bottom py-2" href="javascript:;">
                  <div class="d-flex align-items-center gap-3">
                    <div class="user-wrapper bg-primary text-primary bg-opacity-10">
                      <span>RS</span>
                    </div>
                    <div class="">
                      <h5 class="notify-title">New Account Created</h5>
                      <p class="mb-0 notify-desc">From USA an user has registered.</p>
                      <p class="mb-0 notify-time">Yesterday</p>
                    </div>
                    <div class="notify-close position-absolute end-0 me-3">
                      <i class="material-icons-outlined fs-6">close</i>
                    </div>
                  </div>
                </a>
              </div>
              <div>
                <a class="dropdown-item border-bottom py-2" href="javascript:;">
                  <div class="d-flex align-items-center gap-3">
                    <div class="">
                      <img src="assets/images/apps/13.png" class="rounded-circle" width="45" height="45" alt="">
                    </div>
                    <div class="">
                      <h5 class="notify-title">Payment Recived</h5>
                      <p class="mb-0 notify-desc">New payment recived successfully</p>
                      <p class="mb-0 notify-time">1d ago</p>
                    </div>
                    <div class="notify-close position-absolute end-0 me-3">
                      <i class="material-icons-outlined fs-6">close</i>
                    </div>
                  </div>
                </a>
              </div>
              <div>
                <a class="dropdown-item border-bottom py-2" href="javascript:;">
                  <div class="d-flex align-items-center gap-3">
                    <div class="">
                      <img src="assets/images/apps/14.png" class="rounded-circle" width="45" height="45" alt="">
                    </div>
                    <div class="">
                      <h5 class="notify-title">New Order Recived</h5>
                      <p class="mb-0 notify-desc">Recived new order from michle</p>
                      <p class="mb-0 notify-time">2:15 AM</p>
                    </div>
                    <div class="notify-close position-absolute end-0 me-3">
                      <i class="material-icons-outlined fs-6">close</i>
                    </div>
                  </div>
                </a>
              </div>
              <div>
                <a class="dropdown-item border-bottom py-2" href="javascript:;">
                  <div class="d-flex align-items-center gap-3">
                    <div class="">
                      <img src="assets/images/avatars/06.png" class="rounded-circle" width="45" height="45" alt="">
                    </div>
                    <div class="">
                      <h5 class="notify-title">Congratulations Jhon</h5>
                      <p class="mb-0 notify-desc">Many congtars jhon. You have won the gifts.</p>
                      <p class="mb-0 notify-time">Today</p>
                    </div>
                    <div class="notify-close position-absolute end-0 me-3">
                      <i class="material-icons-outlined fs-6">close</i>
                    </div>
                  </div>
                </a>
              </div>
              <div>
                <a class="dropdown-item py-2" href="javascript:;">
                  <div class="d-flex align-items-center gap-3">
                    <div class="user-wrapper bg-danger text-danger bg-opacity-10">
                      <span>PK</span>
                    </div>
                    <div class="">
                      <h5 class="notify-title">New Account Created</h5>
                      <p class="mb-0 notify-desc">From USA an user has registered.</p>
                      <p class="mb-0 notify-time">Yesterday</p>
                    </div>
                    <div class="notify-close position-absolute end-0 me-3">
                      <i class="material-icons-outlined fs-6">close</i>
                    </div>
                  </div>
                </a>
              </div>
            </div>
          </div>
        </li>
        
        <li class="nav-item dropdown">
          <a href="javascrpt:;" class="dropdown-toggle dropdown-toggle-nocaret" data-bs-toggle="dropdown">
             <img src="assets/images/avatars/01.png" class="rounded-circle p-1 border" width="45" height="45" alt="">
          </a>
          <div class="dropdown-menu dropdown-user dropdown-menu-end shadow">
            <a class="dropdown-item  gap-2 py-2" href="javascript:;">
              <div class="text-center">
                <img src="assets/images/avatars/01.png" class="rounded-circle p-1 shadow mb-3" width="90" height="90"
                  alt="">
                <h5 class="user-name mb-0 fw-bold">Hello, Jhon</h5>
              </div>
            </a>
            <hr class="dropdown-divider">
            <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="user-profile.php"><i
              class="material-icons-outlined">person_outline</i>Profile</a>
           
            <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="index.php"><i
              class="material-icons-outlined">dashboard</i>Dashboard</a>
            <hr class="dropdown-divider">
            <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="login.php"><i
            class="material-icons-outlined">power_settings_new</i>Logout</a>
          </div>
        </li>
      </ul>

    </nav>
  </header>
  <!--end top header-->


   <!--start sidebar-->
   <aside class="sidebar-wrapper" data-simplebar="true">
     <div class="sidebar-header">
       <div class="logo-icon">
         <a href="index.php">
           <img src="assets/images/logo-icon.png" class="logo-img" alt="">
         </a>
       </div>
       <a href="index.php">
         <div class="logo-name flex-grow-1">
           <h5 class="mb-0">HEADQUATERS</h5>
         </div>
       </a>
       <div class="sidebar-close">
         <span class="material-icons-outlined">close</span>
       </div>
     </div>
     <div class="sidebar-nav">
       <!--navigation-->
       <ul class="metismenu" id="sidenav">
         <li>
           <a href="index.php">
             <div class="parent-icon"><i class="material-icons-outlined">home</i>
             </div>
             <div class="menu-title">Dashboard</div>
           </a>
         </li>
         <li>
           <a href="javascript:;" class="has-arrow">
             <div class="parent-icon"><i class="material-icons-outlined">key</i>
             </div>
             <div class="menu-title">Access Control</div>
           </a>
           <ul>
             <li><a href="staff.php"><i class="material-icons-outlined">arrow_right</i>Staff</a>
             </li>
             <li><a href="role.php"><i class="material-icons-outlined">arrow_right</i>Role</a>
             </li>
           </ul>
         </li>
         <li>
           <a href="ecommerce-customers.php">
             <div class="parent-icon"><i class="material-icons-outlined">people</i>
             </div>
             <div class="menu-title">Customers</div>
           </a>
         </li>
         <li>
           <a href="vendor.php">
             <div class="parent-icon"><i class="material-icons-outlined">storefront</i>
             </div>
             <div class="menu-title">Vendor</div>
           </a>
         </li>

         <li>
           <a href="order.php">
             <div class="parent-icon"><i class="material-icons-outlined">home</i>
             </div>
             <div class="menu-title">Order</div>
           </a>
         </li>
         <li>
           <a href="products.php">
             <div class="parent-icon"><i class="material-icons-outlined">shopping_bag</i>
             </div>
             <div class="menu-title">Products</div>
           </a>
         </li>
         <li>
           <a href="assign-order.php">
             <div class="parent-icon"><i class="material-icons-outlined">handshake</i>
             </div>
             <div class="menu-title">Place Order</div>
           </a>
         </li>
         <li>
           <a href="invoices.php">
             <div class="parent-icon"><i class="material-icons-outlined">receipt_long</i>
             </div>
             <div class="menu-title">Invoices</div>
           </a>
         </li>
         <li class="menu-label">Warehouse</li>
         <li>
           <a href="warehouse.php">
             <div class="parent-icon"><i class="material-icons-outlined">store</i>
             </div>
             <div class="menu-title">Warehouses</div>
           </a>
         </li>
         <li>
           <a href="received-products.php">
             <div class="parent-icon"><i class="material-icons-outlined">move_to_inbox</i>
             </div>
             <div class="menu-title">Received Products</div>
           </a>
         </li>
         <li>
           <a href="packaging-list.php">
             <div class="parent-icon"><i class="material-icons-outlined">all_inbox</i>
             </div>
             <div class="menu-title">Packaging List</div>
           </a>
         </li>
         <li>
           <a href="raise-a-ticket.php">
             <div class="parent-icon"><i class="material-icons-outlined">confirmation_number</i>
             </div>
             <div class="menu-title">Tickets</div>
           </a>
         </li>
         <li>
           <a href="ready-to-ship.php">
             <div class="parent-icon"><i class="material-icons-outlined">local_shipping</i>
             </div>
             <div class="menu-title">Ready To Ship</div>
           </a>
         </li>
         <li>
           <a href="track-order.php">
             <div class="parent-icon"><i class="material-icons-outlined">search</i>
             </div>
             <div class="menu-title">Track Order</div>
           </a>
         </li>
         <li class="menu-label">Reports</li>
         <li>
           <a href="vendor-purchase-history.php">
             <div class="parent-icon"><i class="material-icons-outlined">store</i>
             </div>
             <div class="menu-title">Vendor Purchase</div>
           </a>
         </li>
         <li>
           <a href="inventory-stock-history.php">
             <div class="parent-icon"><i class="material-icons-outlined">inventory_2</i>
             </div>
             <div class="menu-title">Inventory Stock</div>
           </a>
         </li>
         <li>
           <a href="customer-sales-history.php">
             <div class="parent-icon"><i class="material-icons-outlined">point_of_sale</i>
             </div>
             <div class="menu-title">Customer Sales</div>
           </a>
         </li>
       </ul>

       <!--end navigation-->
     </div>
   </aside>
   <!--end sidebar-->

    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div >
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Vendor Details</li>
                        </ol>
                    </nav>
                </div>
                <!-- <div class="ms-auto">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary">Settings</button>
                        <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split"
                            data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end"> <a class="dropdown-item"
                                href="javascript:;">Action</a>
                            <a class="dropdown-item" href="javascript:;">Another action</a>
                            <a class="dropdown-item" href="javascript:;">Something else here</a>
                            <div class="dropdown-divider"></div> <a class="dropdown-item" href="javascript:;">Separated link</a>
                        </div>
                    </div>
                </div> -->
            </div>
            <!--end breadcrumb-->


            <div class="row">
                <div class="col-12">
                    <div class="card w-100 d-flex flex-sm-row flex-col">
                        <div class="col card-body d-flex">
                            <div class="position-relative justify-content-center">
                                <img src="assets/images/avatars/02.png" class="img-fluid rounded" alt="">
                                <!-- <div class="position-absolute top-100 start-50 translate-middle">
                                    <img src="assets/images/avatars/02.png" width="100" height="100"
                                        class="rounded-circle raised p-1 bg-white" alt="">
                                </div> -->
                                <div class="text-center my-2 pt-2">
                                    <h4 class="mb-1">Manish Carry</h4>
                                    <p class="mb-0">Marketing Excutive</p>
                                </div>
                            </div>


                        </div>
                        <ul class="col-10 list-group list-group-flush">
                            <li class="list-group-item">
                                <b>Phone No</b>
                                <br>
                                +91-XXX XXX XXXX
                            </li>
                            <li class="list-group-item">
                                <b>Email</b>
                                <br>
                                mail.com
                            </li>
                            <li class="list-group-item">
                                <b>GST No</b>
                                <br>
                                -
                            </li>
                            <li class="list-group-item">
                                <b>PAN No</b>
                                <br>
                                -
                            </li>
                            <li class="list-group-item border-top">
                                <b>Address</b>
                                <br>
                                Office No. 501, 5th Floor, Ghanshyam Enclave, Next To Laljipada Police Station, Laljipada, Link Road, Kandivali (West), Mumbai - 400067. Maharashtra - India
                            </li>
                            <li class="list-group-item border-top">
                                <b>Bank Name</b>
                                <br>
                                RBL Bank
                            </li>
                            <li class="list-group-item border-top">
                                <b>Account No</b>
                                <br>
                                XX XX XX XX XX XX
                            </li>
                            <li class="list-group-item border-top">
                                <b>IFSC Code</b>
                                <br>

                                RATN0000053
                            </li>



                        </ul>


                    </div>
                </div>

            </div><!--end row-->


            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Orders<span class="fw-light ms-2">(98)</span></h5>
                    <div class="product-table">
                        <div class="table-responsive white-space-nowrap">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Payment Status</th>
                                        <th>Order Status</th>
                                        <th>Delivery Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>#2453</td>
                                        <td><span
                                                class="lable-table bg-success-subtle text-success rounded border border-success-subtle font-text2 fw-bold">Paid<i
                                                    class="bi bi-check2 ms-2"></i></span></td>
                                        <td><span
                                                class="lable-table bg-success-subtle text-success rounded border border-success-subtle font-text2 fw-bold">Completed<i
                                                    class="bi bi-check2 ms-2"></i></span></td>
                                        <td>Cash on delivery</td>
                                        <td>Jun 12, 12:56 PM</td>
                                        <td>
                                            <div class="d-flex">
                                                <a aria-label="anchor" href="{{ route('vendor-order-view')}}" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>

                                            <a aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle delete-row" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>$427</td>
                                        <td><span
                                                class="lable-table bg-danger-subtle text-danger rounded border border-danger-subtle font-text2 fw-bold">Failed<i
                                                    class="bi bi-x-lg ms-2"></i></span></td>
                                        <td><span
                                                class="lable-table bg-primary-subtle text-primary rounded border border-primary-subtle font-text2 fw-bold">Completed<i
                                                    class="bi bi-check2 ms-2"></i></span></td>
                                        <td>Cash on delivery</td>
                                        <td>Jun 12, 12:56 PM</td>
                                        <td>
                                            <div class="d-flex">
                                                <a aria-label="anchor" href="{{ route('vendor-order-view')}}" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>

                                            <a aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle delete-row" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#9635</td>
                                        <td><span
                                                class="lable-table bg-warning-subtle text-warning rounded border border-warning-subtle font-text2 fw-bold">Pending<i
                                                    class="bi bi-info-circle ms-2"></i></span></td>
                                        <td><span
                                                class="lable-table bg-danger-subtle text-danger rounded border border-danger-subtle font-text2 fw-bold">Failed<i
                                                    class="bi bi-x-lg ms-2"></i></span></td>
                                        <td>Cash on delivery</td>
                                        <td>Jun 12, 12:56 PM</td>
                                        <td>
                                            <div class="d-flex">
                                                <a aria-label="anchor" href="{{ route('vendor-order-view')}}" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>

                                            <a aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle delete-row" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#2415</td>
                                        <td><span
                                                class="lable-table bg-primary-subtle text-primary rounded border border-primary-subtle font-text2 fw-bold">Completed<i
                                                    class="bi bi-check2-all ms-2"></i></span></td>
                                        <td><span
                                                class="lable-table bg-warning-subtle text-warning rounded border border-warning-subtle font-text2 fw-bold">Pending<i
                                                    class="bi bi-info-circle ms-2"></i></span></td>
                                        <td>Cash on delivery</td>
                                        <td>Jun 12, 12:56 PM</td>
                                        <td>
                                            <div class="d-flex">
                                                <a aria-label="anchor" href="{{ route('vendor-order-view')}}" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>

                                            <a aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle delete-row" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#3526</td>
                                        <td><span
                                                class="lable-table bg-danger-subtle text-danger rounded border border-danger-subtle font-text2 fw-bold">Failed<i
                                                    class="bi bi-x-lg ms-2"></i></span></td>
                                        <td><span
                                                class="lable-table bg-success-subtle text-success rounded border border-success-subtle font-text2 fw-bold">Completed<i
                                                    class="bi bi-check2 ms-2"></i></span></td>
                                        <td>Cash on delivery</td>
                                        <td>Jun 12, 12:56 PM</td>
                                        <td>
                                            <div class="d-flex">
                                                <a aria-label="anchor" href="{{ route('vendor-order-view')}}" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>

                                            <a aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle delete-row" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#7845</td>
                                        <td><span
                                                class="lable-table bg-success-subtle text-success rounded border border-success-subtle font-text2 fw-bold">Paid<i
                                                    class="bi bi-check2 ms-2"></i></span></td>
                                        <td><span
                                                class="lable-table bg-danger-subtle text-danger rounded border border-danger-subtle font-text2 fw-bold">Failed<i
                                                    class="bi bi-x-lg ms-2"></i></span></td>
                                        <td>Cash on delivery</td>
                                        <td>Jun 12, 12:56 PM</td>
                                        <td>
                                            <div class="d-flex">
                                                <a aria-label="anchor" href="{{ route('vendor-order-view')}}" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>

                                            <a aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle delete-row" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#256</td>
                                        <td><span
                                                class="lable-table bg-warning-subtle text-warning rounded border border-warning-subtle font-text2 fw-bold">Pending<i
                                                    class="bi bi-info-circle ms-2"></i></span></td>
                                        <td><span
                                                class="lable-table bg-primary-subtle text-primary rounded border border-primary-subtle font-text2 fw-bold">Completed<i
                                                    class="bi bi-check2-all ms-2"></i></span></td>
                                        <td>Cash on delivery</td>
                                        <td>Jun 12, 12:56 PM</td>
                                        <td>
                                            <div class="d-flex">
                                                <a aria-label="anchor" href="{{ route('vendor-order-view')}}" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye text-primary">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>

                                            <a aria-label="anchor" class="btn btn-icon btn-sm bg-danger-subtle delete-row" data-bs-toggle="tooltip" data-bs-original-title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                                </svg>
                                            </a>
                                            </div>
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
    <!--end main wrapper-->


    <!--start overlay-->
    <div class="overlay btn-toggle"></div>
    <!--end overlay-->

    <!--start footer-->
    <footer class="page-footer">
        <p class="mb-0">Copyright © 2025. All right reserved.</p>
    </footer>
    <!--top footer-->

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


</body>


</html>