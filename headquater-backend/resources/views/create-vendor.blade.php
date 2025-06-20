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
                <div class="ps-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Enter Vendor Details</li>
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

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-4">
                            <h5 class="mb-4">Create Vendor</h5>
                            <form class="row g-3">
                                <div class="col-md-6">
                                    <label for="input1" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="input1" placeholder="First Name">
                                </div>
                                <div class="col-md-6">
                                    <label for="input2" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="input2" placeholder="Last Name">
                                </div>
                                <div class="col-md-6">
                                    <label for="input3" class="form-label">Phone</label>
                                    <input type="text" class="form-control" id="input3" placeholder="Phone">
                                </div>
                                <div class="col-md-6">
                                    <label for="input4" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="input4" placeholder="Email Id">
                                </div>
                                <div class="col-md-6">
                                    <label for="input5" class="form-label">GST NO</label>
                                    <input type="text" class="form-control" id="input5" placeholder="GST NO">
                                </div>
                                <div class="col-md-6">
                                    <label for="input6" class="form-label">PAN NO</label>
                                    <input type="text" class="form-control" id="input6" placeholder="PAN NO">
                                </div>
                                <div class="col-md-12">
                                    <label for="input11" class="form-label">Address</label>
                                    <textarea class="form-control" id="input11" placeholder="Address ..." rows="3"></textarea>
                                </div>
                                <div class="col-md-4">
                                    <label for="input9" class="form-label">State</label>
                                    <select id="input9" class="form-select">
                                        <option selected="" disabled>Choose...</option>
                                        <option>One</option>
                                        <option>Two</option>
                                        <option>Three</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="input8" class="form-label">City</label>
                                    <input type="text" class="form-control" id="input8" placeholder="City">
                                </div>
                                <div class="col-md-4">
                                    <label for="input8" class="form-label">Pin Code</label>
                                    <input type="text" class="form-control" id="input8" placeholder="Pin Code">
                                </div>
                                <div class="col-md-4">
                                    <label for="input10" class="form-label">Account No</label>
                                    <input type="text" class="form-control" id="input10" placeholder="Account No">
                                </div>
                                <div class="col-md-4">
                                    <label for="input8" class="form-label">IFSC Code</label>
                                    <input type="text" class="form-control" id="input8" placeholder="IFSC Code">
                                </div>
                                <div class="col-md-4">
                                    <label for="input8" class="form-label">Bank Name</label>
                                    <input type="text" class="form-control" id="input8" placeholder="Bank Name">
                                </div>


                                <div class="col-md-4">
                                    <label for="input9" class="form-label">Status</label>
                                    <select id="input9" class="form-select">
                                        <option selected="" disabled>Choose any one</option>
                                        <option>Active</option>
                                        <option>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <div class="d-md-flex d-grid align-items-center gap-3">
                                        <a href="{{ route('vendor') }}" type="submit" class="btn btn-primary px-4">Submit</a>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div><!--end row-->

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


</body>


</html>