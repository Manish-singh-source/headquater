  <!doctype html>
  <html lang="en" data-bs-theme="semi-dark">

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
      <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/simplebar/css/simplebar.css') }}">
      <!--bootstrap css-->
      <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
      <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
      <link href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" rel="stylesheet" />
      <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&amp;display=swap"
          rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
      <!--main css-->
      <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">
      <link href="{{ asset('sass/main.css') }}" rel="stylesheet">
      <link href="{{ asset('sass/dark-theme.css') }}" rel="stylesheet">
      <link href="{{ asset('sass/blue-theme.css') }}" rel="stylesheet">
      <link href="{{ asset('sass/semi-dark.css') }}" rel="stylesheet">
      <link href="{{ asset('sass/bordered-theme.css') }}" rel="stylesheet">
      <link href="{{ asset('sass/responsive.css') }}" rel="stylesheet">

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

          div.dt-buttons {
              margin-bottom: 10px;
          }

          .dataTables_length {
              margin-bottom: 10px;

          }

          tbody,
          td,
          tfoot,
          th,
          thead,
          tr {
              border-color: inherit;
              border-style: solid;
              border-width: 2px;
          }

          table.dataTable {
              clear: both;
              margin-top: 6px !important;
              margin-bottom: 6px !important;
              max-width: none !important;
              border-collapse: collapse !important;
              border-spacing: 0;
          }
      </style>
      <style>
          /* From Uiverse.io by adamgiebl */
          .dots-container {
              z-index: 1000;
              position: absolute;
              transform: translateX(50%);
              transform: translateY(50%);
              bottom: 50%;
              /* right: 50%; */
              height: 100%;
              width: 100%;
              background-color: rgb(0, 0, 0, 0.5);
          }

          .dot {
              height: 20px;
              width: 20px;
              margin-right: 10px;
              border-radius: 10px;
              background-color: #b3d4fc;
              animation: pulse 1.5s infinite ease-in-out;
          }

          .dot:last-child {
              margin-right: 0;
          }

          .dot:nth-child(1) {
              animation-delay: -0.3s;
          }

          .dot:nth-child(2) {
              animation-delay: -0.1s;
          }

          .dot:nth-child(3) {
              animation-delay: 0.1s;
          }

          @keyframes pulse {
              0% {
                  transform: scale(0.8);
                  background-color: #b3d4fc;
                  box-shadow: 0 0 0 0 rgba(178, 212, 252, 0.7);
              }

              50% {
                  transform: scale(1.2);
                  background-color: #6793fb;
                  box-shadow: 0 0 0 10px rgba(178, 212, 252, 0);
              }

              100% {
                  transform: scale(0.8);
                  background-color: #b3d4fc;
                  box-shadow: 0 0 0 0 rgba(178, 212, 252, 0.7);
              }
          }
      </style>

  </head>

  <body>
      <!--start header-->
      {{-- <section class="dots-container">
          <div class="dot"></div>
          <div class="dot"></div>
          <div class="dot"></div>
          <div class="dot"></div>
          <div class="dot"></div>
      </section> --}}
      <header class="top-header">
          <nav class="navbar navbar-expand align-items-center gap-4">
              <div class="btn-toggle">
                  <a href="javascript:;"><i class="material-icons-outlined">menu</i></a>
              </div>
              <div class="search-bar flex-grow-1">
                  <div class="position-relative">
                      <input class="form-control rounded-5 px-5 search-control d-lg-block d-none" type="text"
                          placeholder="Search">
                      <span
                          class="material-icons-outlined position-absolute d-lg-block d-none ms-3 translate-middle-y start-0 top-50">search</span>
                      <span
                          class="material-icons-outlined position-absolute me-3 translate-middle-y end-0 top-50 search-close">close</span>
                      <div class="search-popup p-3">
                          <div class="card rounded-4 overflow-hidden">
                              <div class="card-header d-lg-none">
                                  <div class="position-relative">
                                      <input class="form-control rounded-5 px-5 mobile-search-control" type="text"
                                          placeholder="Search">
                                      <span
                                          class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50">search</span>
                                      <span
                                          class="material-icons-outlined position-absolute me-3 translate-middle-y end-0 top-50 mobile-search-close">close</span>
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
                      <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative"
                          data-bs-auto-close="outside" data-bs-toggle="dropdown" href="javascript:;"><i
                              class="material-icons-outlined">notifications</i>
                          <span class="badge-notify">5</span>
                      </a>
                      <div class="dropdown-menu dropdown-notify dropdown-menu-end shadow">
                          <div class="px-3 py-1 d-flex align-items-center justify-content-between border-bottom">
                              <h5 class="notiy-title mb-0">Notifications</h5>
                              <div class="dropdown">
                                  <button class="btn btn-secondary dropdown-toggle dropdown-toggle-nocaret option"
                                      type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                      <span class="material-icons-outlined">
                                          more_vert
                                      </span>
                                  </button>
                                  <div class="dropdown-menu dropdown-option dropdown-menu-end shadow">
                                      <div><a class="dropdown-item d-flex align-items-center gap-2 py-2"
                                              href="javascript:;"><i
                                                  class="material-icons-outlined fs-6">inventory_2</i>Archive All</a>
                                      </div>
                                      <div><a class="dropdown-item d-flex align-items-center gap-2 py-2"
                                              href="javascript:;"><i
                                                  class="material-icons-outlined fs-6">done_all</i>Mark all as read</a>
                                      </div>
                                      <div><a class="dropdown-item d-flex align-items-center gap-2 py-2"
                                              href="javascript:;"><i
                                                  class="material-icons-outlined fs-6">mic_off</i>Disable
                                              Notifications</a></div>
                                      <div><a class="dropdown-item d-flex align-items-center gap-2 py-2"
                                              href="javascript:;"><i
                                                  class="material-icons-outlined fs-6">grade</i>What's new ?</a></div>
                                      <div>
                                          <hr class="dropdown-divider">
                                      </div>
                                      <div><a class="dropdown-item d-flex align-items-center gap-2 py-2"
                                              href="javascript:;"><i
                                                  class="material-icons-outlined fs-6">leaderboard</i>Reports</a></div>
                                  </div>
                              </div>
                          </div>
                          <div class="notify-list">
                              <div>
                                  <a class="dropdown-item border-bottom py-2" href="javascript:;">
                                      <div class="d-flex align-items-center gap-3">
                                          <div class="">
                                              <img src="assets/images/avatars/01.png" class="rounded-circle"
                                                  width="45" height="45" alt="">
                                          </div>
                                          <div class="">
                                              <h5 class="notify-title">Congratulations Jhon</h5>
                                              <p class="mb-0 notify-desc">Many congtars jhon. You have won the gifts.
                                              </p>
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
                                              <img src="assets/images/apps/13.png" class="rounded-circle"
                                                  width="45" height="45" alt="">
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
                                              <img src="assets/images/apps/14.png" class="rounded-circle"
                                                  width="45" height="45" alt="">
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
                                              <img src="assets/images/avatars/06.png" class="rounded-circle"
                                                  width="45" height="45" alt="">
                                          </div>
                                          <div class="">
                                              <h5 class="notify-title">Congratulations Jhon</h5>
                                              <p class="mb-0 notify-desc">Many congtars jhon. You have won the gifts.
                                              </p>
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
                      <a href="javascrpt:;" class="dropdown-toggle dropdown-toggle-nocaret"
                          data-bs-toggle="dropdown">
                          <img src="assets/images/avatars/01.png" class="rounded-circle p-1 border" width="45"
                              height="45" alt="">
                      </a>
                      <div class="dropdown-menu dropdown-user dropdown-menu-end shadow">
                          <a class="dropdown-item  gap-2 py-2" href="javascript:;">
                              <div class="text-center">
                                  <img src="assets/images/avatars/01.png" class="rounded-circle p-1 shadow mb-3"
                                      width="90" height="90" alt="">
                                  <h5 class="user-name mb-0 fw-bold">Hello, Jhon</h5>
                              </div>
                          </a>
                          <hr class="dropdown-divider">
                          <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="user-profile.php"><i
                                  class="material-icons-outlined">person_outline</i>Profile</a>

                          <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                              href="{{ route('index') }}"><i
                                  class="material-icons-outlined">dashboard</i>Dashboard</a>
                          <hr class="dropdown-divider">
                          <a class="dropdown-item d-flex align-items-center gap-2 py-2"
                              href="{{ route('logout') }}"><i
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
                  <a href="{{ route('index') }}">
                      <img src="{{ asset('assets/images/logo-icon.png') }}" class="logo-img" alt="">
                  </a>
              </div>
              <a href="{{ route('index') }}">
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
                      <a href="{{ route('index') }}">
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
                          <li><a href="{{ route('staff') }}"><i
                                      class="material-icons-outlined">arrow_right</i>Staff</a>
                          </li>
                          <li><a href="{{ route('role') }}"><i
                                      class="material-icons-outlined">arrow_right</i>Role</a>
                          </li>
                      </ul>
                  </li>
                  <li>
                      <a href="javascript:;" class="has-arrow">
                          <div class="parent-icon"><i class="material-icons-outlined">category</i>
                          </div>
                          <div class="menu-title">Master</div>
                      </a>
                      <ul>
                          <li><a href="{{ route('groups') }}"><i
                                      class="material-icons-outlined">arrow_right</i>Customers</a>
                          </li>
                          <li><a href="{{ route('vendor') }}"><i
                                      class="material-icons-outlined">arrow_right</i>Vendor</a>
                          </li>
                          <li><a href="{{ route('products') }}"><i
                                      class="material-icons-outlined">arrow_right</i>Products</a>
                          </li>
                          <li><a href="{{ route('warehouse') }}"><i
                                      class="material-icons-outlined">arrow_right</i>Warehouses</a>
                          </li>
                      </ul>
                  </li>
                  <li>
                      <a href="javascript:;" class="has-arrow">
                          <div class="parent-icon"><i class="material-icons-outlined">shopping_cart</i>
                          </div>
                          <div class="menu-title">Purchase</div>
                      </a>
                      <ul>
                          <li><a href="{{ route('assign-order') }}"><i
                                      class="material-icons-outlined">arrow_right</i>Purchase
                                  Order</a>
                          </li>
                      </ul>
                  </li>
                  <li>
                      <a href="javascript:;" class="has-arrow">
                          <div class="parent-icon"><i class="material-icons-outlined">sell</i>
                          </div>
                          <div class="menu-title">Sales</div>
                      </a>
                      <ul>
                          <li><a href="{{ route('order') }}"><i class="material-icons-outlined">arrow_right</i>Sales
                                  Order</a>
                          </li>
                      </ul>
                  </li>
                  <li>
                      <a href="{{ route('invoices') }}">
                          <div class="parent-icon"><i class="material-icons-outlined">receipt_long</i>
                          </div>
                          <div class="menu-title">Invoices</div>
                      </a>
                  </li>
                  <li class="menu-label">Warehouse</li>
                  <li>
                      <a href="{{ route('received-products') }}">
                          <div class="parent-icon"><i class="material-icons-outlined">move_to_inbox</i>
                          </div>
                          <div class="menu-title">Received Products</div>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('packaging-list') }}">
                          <div class="parent-icon"><i class="material-icons-outlined">all_inbox</i>
                          </div>
                          <div class="menu-title">Packaging List</div>
                      </a>
                  </li>
                  {{-- <li>
                      <a href="{{ route('raise-a-ticket') }}">
                          <div class="parent-icon"><i class="material-icons-outlined">confirmation_number</i>
                          </div>
                          <div class="menu-title">Tickets</div>
                      </a>
                  </li> --}}
                  <li>
                      <a href="{{ route('ready-to-ship') }}">
                          <div class="parent-icon"><i class="material-icons-outlined">local_shipping</i>
                          </div>
                          <div class="menu-title">Ready To Ship</div>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('track-order') }}">
                          <div class="parent-icon"><i class="material-icons-outlined">search</i>
                          </div>
                          <div class="menu-title">Track Order</div>
                      </a>
                  </li>
                  <li class="menu-label">Reports</li>
                  <li>
                      <a href="{{ route('vendor-purchase-history') }}">
                          <div class="parent-icon"><i class="material-icons-outlined">store</i>
                          </div>
                          <div class="menu-title">Vendor Purchase</div>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('inventory-stock-history') }}">
                          <div class="parent-icon"><i class="material-icons-outlined">inventory_2</i>
                          </div>
                          <div class="menu-title">Inventory Stock</div>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('customer-sales-history') }}">
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

      @yield('main-content')

      <!--start footer-->
      <!--start overlay-->
      <div class="overlay btn-toggle"></div>
      <!--end overlay-->

      <!--start footer-->
      <footer class="page-footer">
          <p class="mb-0">Copyright © 2025. All right reserved.</p>
      </footer>
      <!--top footer-->

      <!--start cart-->
      <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasCart">
          <div class="offcanvas-header border-bottom h-70">
              <h5 class="mb-0" id="offcanvasRightLabel">8 New Orders</h5>
              <a href="javascript:;" class="primaery-menu-close" data-bs-dismiss="offcanvas">
                  <i class="material-icons-outlined">close</i>
              </a>
          </div>
          <div class="offcanvas-body p-0">
              <div class="order-list">
                  <div class="order-item d-flex align-items-center gap-3 p-3 border-bottom">
                      <div class="order-img">
                          <img src="assets/images/orders/01.png" class="img-fluid rounded-3" width="75"
                              alt="">
                      </div>
                      <div class="order-info flex-grow-1">
                          <h5 class="mb-1 order-title">White Men Shoes</h5>
                          <p class="mb-0 order-price">$289</p>
                      </div>
                      <div class="d-flex">
                          <a class="order-delete"><span class="material-icons-outlined">delete</span></a>
                          <a class="order-delete"><span class="material-icons-outlined">visibility</span></a>
                      </div>
                  </div>

                  <div class="order-item d-flex align-items-center gap-3 p-3 border-bottom">
                      <div class="order-img">
                          <img src="assets/images/orders/02.png" class="img-fluid rounded-3" width="75"
                              alt="">
                      </div>
                      <div class="order-info flex-grow-1">
                          <h5 class="mb-1 order-title">Red Airpods</h5>
                          <p class="mb-0 order-price">$149</p>
                      </div>
                      <div class="d-flex">
                          <a class="order-delete"><span class="material-icons-outlined">delete</span></a>
                          <a class="order-delete"><span class="material-icons-outlined">visibility</span></a>
                      </div>
                  </div>

                  <div class="order-item d-flex align-items-center gap-3 p-3 border-bottom">
                      <div class="order-img">
                          <img src="assets/images/orders/03.png" class="img-fluid rounded-3" width="75"
                              alt="">
                      </div>
                      <div class="order-info flex-grow-1">
                          <h5 class="mb-1 order-title">Men Polo Tshirt</h5>
                          <p class="mb-0 order-price">$139</p>
                      </div>
                      <div class="d-flex">
                          <a class="order-delete"><span class="material-icons-outlined">delete</span></a>
                          <a class="order-delete"><span class="material-icons-outlined">visibility</span></a>
                      </div>
                  </div>

                  <div class="order-item d-flex align-items-center gap-3 p-3 border-bottom">
                      <div class="order-img">
                          <img src="assets/images/orders/04.png" class="img-fluid rounded-3" width="75"
                              alt="">
                      </div>
                      <div class="order-info flex-grow-1">
                          <h5 class="mb-1 order-title">Blue Jeans Casual</h5>
                          <p class="mb-0 order-price">$485</p>
                      </div>
                      <div class="d-flex">
                          <a class="order-delete"><span class="material-icons-outlined">delete</span></a>
                          <a class="order-delete"><span class="material-icons-outlined">visibility</span></a>
                      </div>
                  </div>

                  <div class="order-item d-flex align-items-center gap-3 p-3 border-bottom">
                      <div class="order-img">
                          <img src="assets/images/orders/05.png" class="img-fluid rounded-3" width="75"
                              alt="">
                      </div>
                      <div class="order-info flex-grow-1">
                          <h5 class="mb-1 order-title">Fancy Shirts</h5>
                          <p class="mb-0 order-price">$758</p>
                      </div>
                      <div class="d-flex">
                          <a class="order-delete"><span class="material-icons-outlined">delete</span></a>
                          <a class="order-delete"><span class="material-icons-outlined">visibility</span></a>
                      </div>
                  </div>

                  <div class="order-item d-flex align-items-center gap-3 p-3 border-bottom">
                      <div class="order-img">
                          <img src="assets/images/orders/06.png" class="img-fluid rounded-3" width="75"
                              alt="">
                      </div>
                      <div class="order-info flex-grow-1">
                          <h5 class="mb-1 order-title">Home Sofa Set </h5>
                          <p class="mb-0 order-price">$546</p>
                      </div>
                      <div class="d-flex">
                          <a class="order-delete"><span class="material-icons-outlined">delete</span></a>
                          <a class="order-delete"><span class="material-icons-outlined">visibility</span></a>
                      </div>
                  </div>

                  <div class="order-item d-flex align-items-center gap-3 p-3 border-bottom">
                      <div class="order-img">
                          <img src="assets/images/orders/07.png" class="img-fluid rounded-3" width="75"
                              alt="">
                      </div>
                      <div class="order-info flex-grow-1">
                          <h5 class="mb-1 order-title">Black iPhone</h5>
                          <p class="mb-0 order-price">$1049</p>
                      </div>
                      <div class="d-flex">
                          <a class="order-delete"><span class="material-icons-outlined">delete</span></a>
                          <a class="order-delete"><span class="material-icons-outlined">visibility</span></a>
                      </div>
                  </div>

                  <div class="order-item d-flex align-items-center gap-3 p-3 border-bottom">
                      <div class="order-img">
                          <img src="assets/images/orders/08.png" class="img-fluid rounded-3" width="75"
                              alt="">
                      </div>
                      <div class="order-info flex-grow-1">
                          <h5 class="mb-1 order-title">Goldan Watch</h5>
                          <p class="mb-0 order-price">$689</p>
                      </div>
                      <div class="d-flex">
                          <a class="order-delete"><span class="material-icons-outlined">delete</span></a>
                          <a class="order-delete"><span class="material-icons-outlined">visibility</span></a>
                      </div>
                  </div>
              </div>
          </div>
          <div class="offcanvas-footer h-70 p-3 border-top">
              <div class="d-grid">
                  <button type="button" class="btn btn-grd btn-grd-primary" data-bs-dismiss="offcanvas">View
                      Products</button>
              </div>
          </div>
      </div>
      <!--end cart-->


      <!--bootstrap js-->
      <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

      <!--plugins-->
      <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
      <!--plugins-->
      <script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
      <script src="{{ asset('assets/plugins/metismenu/metisMenu.min.js') }}"></script>
      <script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
      <script src="{{ asset('assets/js/main.js') }}"></script>


      <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
      <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
      <script>
          $(document).ready(function() {
              var table1 = $('#example').DataTable({
                  "columnDefs": [{
                          "orderable": false,
                          //   "targets": [0, -1],
                      } // Disable sorting for the 4th column (index starts at 0)
                  ],
                  lengthChange: true,
                    // buttons: ['excel', 'pdf', 'print']
                    buttons: ['excel']
              });

              table1.buttons().container()
                  .appendTo('#example_wrapper .col-md-6:eq(0)');

              $('#departmentFilter').on('change', function() {
                  var selected = $(this).val();

                  // Use regex for exact match
                  table1.column(1).search(selected ? '^' + selected + '$' : '', true, false).draw();
              });
          });
      </script>

      <script>
          $(document).ready(function() {
              var table2 = $('#example2').DataTable({
                  lengthChange: false,
                  buttons: ['copy', 'excel', 'pdf', 'print']
              });

              table2.buttons().container()
                  .appendTo('#example2_wrapper .col-md-6:eq(0)');
          });
      </script>

      <script>
          //   $(".dots-container").show();
          //   $(document).ready(function() {
          //       $(".dots-container").hide();
          //   });
      </script>


      <script>
          document.addEventListener('DOMContentLoaded', function() {
              const selectAll = document.getElementById('select-all');
              const checkboxes = document.querySelectorAll('.row-checkbox');

              selectAll.addEventListener('change', function() {
                  checkboxes.forEach(cb => cb.checked = selectAll.checked);
              });
          });
      </script>

      @yield('script')
  </body>

  </html>
