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

            <div class="div my-2">

                <div class="row">
                    <div class="col-12">
                        <div class="card w-100 d-flex  flex-sm-row flex-col">
                            <ul class="col-12 list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Order Id</b></span>

                                    <span>#2056</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 pe-3">
                                    <span><b>Vendor Name</b></span>
                                    <span> <b>Blinkit</b>, <b>Moonstone</b> </span>
                                </li>
                            </ul>


                        </div>
                    </div>
                </div>

            </div>

            <div class="card">
                <div class="card-body">
                    <div class="div d-flex my-2">
                        <div class="col">
                            <h6 class="mb-3">PO Table</h6>
                        </div>
                        <!-- <div class="col-12 col-lg-1 text-end">
                            <button class="form-select" onclick="poTable()">Hide Table</button>
                        </div> -->
                    </div>
                    <div class="product-table" id="poTable">
                        <div class="table-responsive white-space-nowrap">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order No</th>
                                        <th>Portal Code</th>
                                        <th>SKU Code</th>
                                        <th>Title</th>
                                        <th>MRP</th>
                                        <th>Qty Requirement</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>OPS/2025/2276</td>
                                        <td>BOCRDVF87G</td>
                                        <td>TP-260</td>
                                        <td>Yera 260ml Glass Parabolic Tumbler Set</td>
                                        <td>315</td>
                                        <td>64</td>
                                    </tr>
                                    <tr>
                                        <td>OPS/2025/2276</td>
                                        <td>BOCRDL1L94</td>
                                        <td>JR2KG</td>
                                        <td>Yera Glass Jar with Plastic Lid - 2425ml</td>
                                        <td>330</td>
                                        <td>9</td>
                                    </tr>
                                    <tr>
                                        <td>OPS/2025/2276</td>
                                        <td>BOCRDJH5YZ</td>
                                        <td>B9OFL</td>
                                        <td>Yera Ice Cream Delight 250 ml Glass Bowl Set of 6</td>
                                        <td>280</td>
                                        <td>64</td>
                                    </tr>
                                    <tr>
                                        <td>OPS/2025/2276</td>
                                        <td>BOCR6N9ZL7</td>
                                        <td>TC8P17</td>
                                        <td>Yera Conical Glass Tumbler Set - 215 ml</td>
                                        <td>230</td>
                                        <td>144</td>
                                    </tr>
                                    <tr>
                                        <td>OPS/2025/2276</td>
                                        <td>B07T2DJ6JR</td>
                                        <td>TS10-P0</td>
                                        <td>Yera Glass Tumbler Transparent 285 ml</td>
                                        <td>240</td>
                                        <td>64</td>
                                    </tr>
                                    <tr>
                                        <td>OPS/2025/2276</td>
                                        <td>B07T2D5P2L</td>
                                        <td>JS-4</td>
                                        <td>Yera Glass Aahaar Jars, 1800 ml</td>
                                        <td>190</td>
                                        <td>144</td>
                                    </tr>
                                    <tr>
                                        <td>OPS/2025/2276</td>
                                        <td>B07T1CN9SX</td>
                                        <td>T9AHB</td>
                                        <td>Yera Glass Tumblers - 250 ml, Set of 6</td>
                                        <td>250</td>
                                        <td>64</td>
                                    </tr>
                                    <tr>
                                        <td>OPS/2025/2276</td>
                                        <td>B07T1CM6S3</td>
                                        <td>JR-3</td>
                                        <td>Yera Glass Aahaar Jars Storage Container, 3600 ML</td>
                                        <td>225</td>
                                        <td>360</td>
                                    </tr>
                                    <tr>
                                        <td>OPS/2025/2276</td>
                                        <td>B07T1CM6N6</td>
                                        <td>CT9-P0</td>
                                        <td>Yera Transparent Glass Mug with Handle 240 ml</td>
                                        <td>340</td>
                                        <td>128</td>
                                    </tr>
                                    <tr>
                                        <td>OPS/2025/2276</td>
                                        <td>B07SZ867XZ</td>
                                        <td>JR-2</td>
                                        <td>Yera Glass Aahaar Jars Storage Container, 2425 ML</td>
                                        <td>185</td>
                                        <td>216</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-12 text-end">
                            <button type="" class="btn btn-success w-sm waves ripple-light">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-bottom-dashed">
                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <h5 class="card-title mb-0">
                                Add PI
                            </h5>
                        </div>
                        <div class="col-12 col-lg-2 gap-2 pi-view-show">
                            <button type="button" class="btn btn-sm btn-success w-sm add">
                                Add More
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body pi-add">
                    <div class="row g-3">
                        <div class="col-12 col-lg-3">
                            <label for="marital" class="form-label">Vendor Name
                                <span class="text-danger">*</span></label>
                            <select class="form-control" name="marital" id="marital">
                                <option selected="" disabled="" value="">-- Select --</option>
                                <option value="Active">Active</option>
                                <option value="Emily ">Emily </option>
                                <option value="John ">John </option>
                                <option value="Michael ">Michael </option>
                                <option value="Sarah ">Sarah </option>
                                <option value="Davis">Davis</option>
                                <option value="Smith">Smith</option>
                                <option value="Brown">Brown</option>
                                <option value="Wilson">Wilson</option>
                            </select>
                        </div>
                        <div class="col-12 col-lg-3">
                            <label for="document_image" class="form-label">Upload PI Excel <span class="text-danger">*</span></label>
                            <input type="file" name="document_image" id="document_image" class="form-control" value="" required="" placeholder="Upload ID Document">
                        </div>
                        <div class="col-12 col-lg-2 d-flex align-items-end gap-2">
                            <button type="" class="btn btn-success w-sm waves ripple-light upload">
                                Upload
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body pi-view-show">
                    <div class="row g-3">
                        <div class="col-12 col-lg-3">
                            <label for="marital" class="form-label">Vendor Name</label>
                            <p><b>Emily</b></p>
                        </div>
                        <div class="col-12 col-lg-3">
                            <label for="document_image" class="form-label">PI Excel</label>
                            <p> <b>ABC.xls</b> </p>
                        </div>
                        <!-- <div class="col-12 col-lg-2 d-flex align-items-end gap-2">
                            <button type="" class="btn btn-success w-sm waves ripple-light add">
                                Add More
                            </button>
                        </div> -->
                    </div>
                </div>

                <div class="card-body pi2-add">
                    <div class="row g-3">
                        <div class="col-12 col-lg-3">
                            <label for="marital" class="form-label">Vendor Name
                                <span class="text-danger">*</span></label>
                            <select class="form-control" name="marital" id="marital">
                                <option selected="" disabled="" value="">-- Select --</option>
                                <option value="Active">Active</option>
                                <option value="Emily ">Emily </option>
                                <option value="John ">John </option>
                                <option value="Michael ">Michael </option>
                                <option value="Sarah ">Sarah </option>
                                <option value="Davis">Davis</option>
                                <option value="Smith">Smith</option>
                                <option value="Brown">Brown</option>
                                <option value="Wilson">Wilson</option>
                            </select>
                        </div>
                        <div class="col-12 col-lg-3">
                            <label for="document_image" class="form-label">Upload PI Excel <span class="text-danger">*</span></label>
                            <input type="file" name="document_image" id="document_image" class="form-control" value="" required="" placeholder="Upload ID Document">
                        </div>
                        <div class="col-12 col-lg-2 d-flex align-items-end gap-2">
                            <button type="" class="btn btn-success w-sm waves ripple-light upload2">
                                Upload
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body pi2-view-show">
                    <div class="row g-3">
                        <div class="col-12 col-lg-3">
                            <label for="marital" class="form-label">Vendor Name</label>
                            <p><b>Emily</b></p>
                        </div>
                        <div class="col-12 col-lg-3">
                            <label for="document_image" class="form-label">PI Excel</label>
                            <p> <b>ABC.xls</b> </p>
                        </div>
                        <!-- <div class="col-12 col-lg-2 d-flex align-items-end gap-2">
                            <button type="" class="btn btn-success w-sm waves ripple-light">
                                Add More
                            </button>
                        </div> -->
                    </div>
                </div>

            </div>

            <div class="card">
                <div class="card-body">
                    <div class="div d-flex my-2">
                        <div class="col">
                            <h6 class="mb-3">PI Table</h6>
                        </div>
                    </div>

                    <!-- Tabs Navigation -->
                    <div class="div d-flex my-3">
                        <ul class="nav nav-tabs" id="vendorTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active bg-success text-white mx-1" id="vendor1-tab" data-bs-toggle="tab" data-bs-target="#vendor1" type="button" role="tab">Vendor 1</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link bg-success text-white mx-1" id="vendor2-tab" data-bs-toggle="tab" data-bs-target="#vendor2" type="button" role="tab">Vendor 2</button>
                            </li>
                        </ul>
                    </div>

                    <!-- Tabs Content -->
                    <div class="tab-content" id="vendorTabsContent">
                        <!-- Vendor 1 Table -->
                        <div class="tab-pane fade show active" id="vendor1" role="tabpanel" aria-labelledby="vendor1-tab">
                            <div class="product-table" id="piTable">
                                <div class="table-responsive white-space-nowrap">
                                    <table class="table align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Order No</th>
                                                <th>Portal Code</th>
                                                <th>SKU Code</th>
                                                <th>Title</th>
                                                <th>MRP</th>
                                                <th>Qty Requirement</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>OPS/2025/2276</td>
                                                <td>BOCRDVF87G</td>
                                                <td>TP-260</td>
                                                <td>Yera 260ml Glass Parabolic Tumbler Set</td>
                                                <td>315</td>
                                                <td>64</td>
                                            </tr>
                                            <tr>
                                                <td>OPS/2025/2276</td>
                                                <td>BOCRDL1L94</td>
                                                <td>JR2KG</td>
                                                <td>Yera Glass Jar with Plastic Lid - 2425ml</td>
                                                <td>330</td>
                                                <td>9</td>
                                            </tr>
                                            <tr>
                                                <td>OPS/2025/2276</td>
                                                <td>BOCRDJH5YZ</td>
                                                <td>B9OFL</td>
                                                <td>Yera Ice Cream Delight 250 ml Glass Bowl Set of 6</td>
                                                <td>280</td>
                                                <td>64</td>
                                            </tr>
                                            <tr>
                                                <td>OPS/2025/2276</td>
                                                <td>BOCR6N9ZL7</td>
                                                <td>TC8P17</td>
                                                <td>Yera Conical Glass Tumbler Set - 215 ml</td>
                                                <td>230</td>
                                                <td>144</td>
                                            </tr>
                                            <tr>
                                                <td>OPS/2025/2276</td>
                                                <td>B07T2DJ6JR</td>
                                                <td>TS10-P0</td>
                                                <td>Yera Glass Tumbler Transparent 285 ml</td>
                                                <td>240</td>
                                                <td>64</td>
                                            </tr>
                                            <tr>
                                                <td>OPS/2025/2276</td>
                                                <td>B07T2D5P2L</td>
                                                <td>JS-4</td>
                                                <td>Yera Glass Aahaar Jars, 1800 ml</td>
                                                <td>190</td>
                                                <td>144</td>
                                            </tr>
                                            <tr>
                                                <td>OPS/2025/2276</td>
                                                <td>B07T1CN9SX</td>
                                                <td>T9AHB</td>
                                                <td>Yera Glass Tumblers - 250 ml, Set of 6</td>
                                                <td>250</td>
                                                <td>64</td>
                                            </tr>
                                            <tr>
                                                <td>OPS/2025/2276</td>
                                                <td>B07T1CM6S3</td>
                                                <td>JR-3</td>
                                                <td>Yera Glass Aahaar Jars Storage Container, 3600 ML</td>
                                                <td>225</td>
                                                <td>360</td>
                                            </tr>
                                            <tr>
                                                <td>OPS/2025/2276</td>
                                                <td>B07T1CM6N6</td>
                                                <td>CT9-P0</td>
                                                <td>Yera Transparent Glass Mug with Handle 240 ml</td>
                                                <td>340</td>
                                                <td>128</td>
                                            </tr>
                                            <tr>
                                                <td>OPS/2025/2276</td>
                                                <td>B07SZ867XZ</td>
                                                <td>JR-2</td>
                                                <td>Yera Glass Aahaar Jars Storage Container, 2425 ML</td>
                                                <td>185</td>
                                                <td>216</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-12 text-end">
                                <button class="btn btn-success w-sm">Save</button>
                            </div>
                        </div>

                        <!-- Vendor 2 Table -->
                        <div class="tab-pane fade" id="vendor2" role="tabpanel" aria-labelledby="vendor2-tab">
                            <div class="product-table" id="piTable">
                                <div class="table-responsive white-space-nowrap">
                                    <table class="table align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Order No</th>
                                                <th>Portal Code</th>
                                                <th>SKU Code</th>
                                                <th>Title</th>
                                                <th>MRP</th>
                                                <th>Qty Requirement</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>OPS/2025/3376</td>
                                                <td>BOCRDVF87G</td>
                                                <td>TP-260</td>
                                                <td>Yera 260ml Glass Parabolic Tumbler Set</td>
                                                <td>315</td>
                                                <td>64</td>
                                            </tr>
                                            <tr>
                                                <td>OPS/2025/3376</td>
                                                <td>BOCRDL1L94</td>
                                                <td>JR2KG</td>
                                                <td>Yera Glass Jar with Plastic Lid - 2425ml</td>
                                                <td>330</td>
                                                <td>9</td>
                                            </tr>
                                            <tr>
                                                <td>OPS/2025/3376</td>
                                                <td>BOCRDJH5YZ</td>
                                                <td>B9OFL</td>
                                                <td>Yera Ice Cream Delight 250 ml Glass Bowl Set of 6</td>
                                                <td>280</td>
                                                <td>64</td>
                                            </tr>
                                            <tr>
                                                <td>OPS/2025/3376</td>
                                                <td>BOCR6N9ZL7</td>
                                                <td>TC8P17</td>
                                                <td>Yera Conical Glass Tumbler Set - 215 ml</td>
                                                <td>230</td>
                                                <td>144</td>
                                            </tr>
                                            <tr>
                                                <td>OPS/2025/3376</td>
                                                <td>B07T2DJ6JR</td>
                                                <td>TS10-P0</td>
                                                <td>Yera Glass Tumbler Transparent 285 ml</td>
                                                <td>240</td>
                                                <td>64</td>
                                            </tr>
                                            <tr>
                                                <td>OPS/2025/3376</td>
                                                <td>B07T2D5P2L</td>
                                                <td>JS-4</td>
                                                <td>Yera Glass Aahaar Jars, 1800 ml</td>
                                                <td>190</td>
                                                <td>144</td>
                                            </tr>
                                            <tr>
                                                <td>OPS/2025/3376</td>
                                                <td>B07T1CN9SX</td>
                                                <td>T9AHB</td>
                                                <td>Yera Glass Tumblers - 250 ml, Set of 6</td>
                                                <td>250</td>
                                                <td>64</td>
                                            </tr>
                                            <tr>
                                                <td>OPS/2025/3376</td>
                                                <td>B07T1CM6S3</td>
                                                <td>JR-3</td>
                                                <td>Yera Glass Aahaar Jars Storage Container, 3600 ML</td>
                                                <td>225</td>
                                                <td>360</td>
                                            </tr>
                                            <tr>
                                                <td>OPS/2025/3376</td>
                                                <td>B07T1CM6N6</td>
                                                <td>CT9-P0</td>
                                                <td>Yera Transparent Glass Mug with Handle 240 ml</td>
                                                <td>340</td>
                                                <td>128</td>
                                            </tr>
                                            <tr>
                                                <td>OPS/2025/3376</td>
                                                <td>B07SZ867XZ</td>
                                                <td>JR-2</td>
                                                <td>Yera Glass Aahaar Jars Storage Container, 2425 ML</td>
                                                <td>185</td>
                                                <td>216</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-12 text-end">
                                <button class="btn btn-success w-sm">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-bottom-dashed">
                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <h5 class="card-title mb-0">
                                Add Invoice
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-lg-3">
                            <label for="marital" class="form-label">Vendor Name
                                <span class="text-danger">*</span></label>
                            <select class="form-control" name="marital" id="marital">
                                <option selected="" disabled="" value="">-- Select --</option>
                                <option value="Active">Active</option>
                                <option value="Emily ">Emily </option>
                                <option value="John ">John </option>
                                <option value="Michael ">Michael </option>
                                <option value="Sarah ">Sarah </option>
                                <option value="Davis">Davis</option>
                                <option value="Smith">Smith</option>
                                <option value="Brown">Brown</option>
                                <option value="Wilson">Wilson</option>
                            </select>
                        </div>
                        <div class="col-12 col-lg-3">
                            <label for="document_image" class="form-label">Upload Invoice <span class="text-danger">*</span></label>
                            <input type="file" name="document_image" id="document_image" class="form-control" value="" required="" placeholder="Upload ID Document">
                        </div>
                        <div class="col-12 col-lg-1 d-flex align-items-end gap-2">
                            <button type="" class="btn btn-success w-sm waves ripple-light">
                                Upload
                            </button>
                            <button type="" class="btn btn-success w-sm waves ripple-light">
                                Preview
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-lg-3">
                            <label for="marital" class="form-label">Vendor Name
                                <span class="text-danger">*</span></label>
                            <select class="form-control" name="marital" id="marital">
                                <option selected="" disabled="" value="">-- Select --</option>
                                <option value="Active">Active</option>
                                <option value="Emily ">Emily </option>
                                <option value="John ">John </option>
                                <option value="Michael ">Michael </option>
                                <option value="Sarah ">Sarah </option>
                                <option value="Davis">Davis</option>
                                <option value="Smith">Smith</option>
                                <option value="Brown">Brown</option>
                                <option value="Wilson">Wilson</option>
                            </select>
                        </div>
                        <div class="col-12 col-lg-3">
                            <label for="document_image" class="form-label">Upload Invoice <span class="text-danger">*</span></label>
                            <input type="file" name="document_image" id="document_image" class="form-control" value="" required="" placeholder="Upload ID Document">
                        </div>
                        <div class="col-12 col-lg-1 d-flex align-items-end gap-2">
                            <button type="" class="btn btn-success w-sm waves ripple-light">
                                Upload
                            </button>
                            <button type="" class="btn btn-success w-sm waves ripple-light">
                                Preview
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-bottom-dashed">
                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <h5 class="card-title mb-0">
                                Add GRN
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-lg-3">
                            <label for="marital" class="form-label">Vendor Name
                                <span class="text-danger">*</span></label>
                            <select class="form-control" name="marital" id="marital">
                                <option selected="" disabled="" value="">-- Select --</option>
                                <option value="Active">Active</option>
                                <option value="Emily ">Emily </option>
                                <option value="John ">John </option>
                                <option value="Michael ">Michael </option>
                                <option value="Sarah ">Sarah </option>
                                <option value="Davis">Davis</option>
                                <option value="Smith">Smith</option>
                                <option value="Brown">Brown</option>
                                <option value="Wilson">Wilson</option>
                            </select>
                        </div>
                        <div class="col-12 col-lg-3">
                            <label for="document_image" class="form-label">Upload GRN <span class="text-danger">*</span></label>
                            <input type="file" name="document_image" id="document_image" class="form-control" value="" required="" placeholder="Upload ID Document">
                        </div>
                        <div class="col-12 col-lg-1 d-flex align-items-end gap-2">
                            <button type="" class="btn btn-success w-sm waves ripple-light">
                                Upload
                            </button>
                            <button type="" class="btn btn-success w-sm waves ripple-light">
                                Preview
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-lg-3">
                            <label for="marital" class="form-label">Vendor Name
                                <span class="text-danger">*</span></label>
                            <select class="form-control" name="marital" id="marital">
                                <option selected="" disabled="" value="">-- Select --</option>
                                <option value="Active">Active</option>
                                <option value="Emily ">Emily </option>
                                <option value="John ">John </option>
                                <option value="Michael ">Michael </option>
                                <option value="Sarah ">Sarah </option>
                                <option value="Davis">Davis</option>
                                <option value="Smith">Smith</option>
                                <option value="Brown">Brown</option>
                                <option value="Wilson">Wilson</option>
                            </select>
                        </div>
                        <div class="col-12 col-lg-3">
                            <label for="document_image" class="form-label">Upload GRN <span class="text-danger">*</span></label>
                            <input type="file" name="document_image" id="document_image" class="form-control" value="" required="" placeholder="Upload ID Document">
                        </div>
                        <div class="col-12 col-lg-1 d-flex align-items-end gap-2">
                            <button type="" class="btn btn-success w-sm waves ripple-light">
                                Upload
                            </button>
                            <button type="" class="btn btn-success w-sm waves ripple-light">
                                Preview
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header border-bottom-dashed">
                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <h5 class="card-title mb-0">
                                Payment Status
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-lg-3">
                            <label for="marital" class="form-label">Vendor Name
                                <span class="text-danger">*</span></label>
                            <select class="form-control" name="marital" id="marital">
                                <option selected="" disabled="" value="">-- Select --</option>
                                <option value="Active">Active</option>
                                <option value="Emily ">Emily </option>
                                <option value="John ">John </option>
                                <option value="Michael ">Michael </option>
                                <option value="Sarah ">Sarah </option>
                                <option value="Davis">Davis</option>
                                <option value="Smith">Smith</option>
                                <option value="Brown">Brown</option>
                                <option value="Wilson">Wilson</option>
                            </select>
                        </div>
                        <div class="col-12 col-lg-2">
                            <label for="document_image" class="form-label">Update Payment Status<span class="text-danger">*</span></label>
                            <select id="input9" class="form-select">
                                <option selected="" disabled>Payment Status</option>
                                <option>Pending</option>
                                <option>Rejected</option>
                                <option>Completed</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-lg-3">
                            <label for="marital" class="form-label">Vendor Name
                                <span class="text-danger">*</span></label>
                            <select class="form-control" name="marital" id="marital">
                                <option selected="" disabled="" value="">-- Select --</option>
                                <option value="Active">Active</option>
                                <option value="Emily ">Emily </option>
                                <option value="John ">John </option>
                                <option value="Michael ">Michael </option>
                                <option value="Sarah ">Sarah </option>
                                <option value="Davis">Davis</option>
                                <option value="Smith">Smith</option>
                                <option value="Brown">Brown</option>
                                <option value="Wilson">Wilson</option>
                            </select>
                        </div>
                        <div class="col-12 col-lg-2">
                            <label for="document_image" class="form-label">Update Payment Status<span class="text-danger">*</span></label>
                            <select id="input9" class="form-select">
                                <option selected="" disabled>Payment Status</option>
                                <option>Pending</option>
                                <option>Rejected</option>
                                <option>Completed</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 my-3 d-flex justify-content-end align-items-end gap-2">
                <a href="assign-order.php" class="btn btn-success w-sm waves ripple-light">
                    Close Order
                </a>
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


    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Initialize Select2 -->
    <script>
        $(document).ready(function() {
            $('#ms1').select2({
                placeholder: "Select Vendors",
                allowClear: true
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#ms2').select2({
                placeholder: "Select Vendors",
                allowClear: true
            });
        });
    </script>

    <!-- Bootstrap JS (required for tabs to work) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(".add-more-btn").hide();
        $(".pi-view-show").hide();
        $(".pi2-add").hide();
        $(".pi2-view-show").hide();


        $(".upload").on("click", function() {
            // 
            $(".pi-view-show").show();
            $(".pi-add").hide();
        });

        $(".add").on("click", function() {
            // 
            // $(".pi2-view-show").show();
            $(".pi2-add").show();
        });

        $(".upload2").on("click", function() {
            // 
            $(".pi2-add").hide();
            $(".pi2-view-show").show();
        });
    </script>


</body>


</html>