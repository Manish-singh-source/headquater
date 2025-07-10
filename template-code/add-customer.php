<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<body>


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
              <li class="breadcrumb-item active" aria-current="page">Customer Form</li>
            </ol>
          </nav>
        </div>

      </div>
      <!--end breadcrumb-->

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body p-4">
              <h5 class="mb-4">Customer details</h5>
              <form class="row g-3">
                <div class="col-md-6">
                  <label for="firstName" class="form-label">First Name</label>
                  <input type="text" class="form-control" id="firstName" placeholder="Enter First Name">
                </div>

                <div class="col-md-6">
                  <label for="lastName" class="form-label">Last Name</label>
                  <input type="text" class="form-control" id="lastName" placeholder="Enter Last Name">
                </div>

                <div class="col-md-6">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control" id="email" placeholder="Enter Email">
                </div>

                <div class="col-md-6">
                  <label for="phone" class="form-label">Phone Number</label>
                  <input type="text" class="form-control" id="phone" placeholder="Enter Phone Number">
                </div>

                <h5 class="mt-4">Company details</h5>

                <div class="col-md-6">
                  <label for="companyName" class="form-label">Company Name</label>
                  <input type="text" class="form-control" id="customerName" placeholder="Enter Company Name">
                </div>

                <div class="col-md-6">
                  <label for="gstNumber" class="form-label">GST Number</label>
                  <input type="text" class="form-control" id="gstNumber" placeholder="Enter GST Number">
                </div>

                <div class="col-md-6">
                  <label for="panNumber" class="form-label">PAN Number</label>
                  <input type="text" class="form-control" id="panNumber" placeholder="Enter PAN Number">
                </div>
                
                <div class="col-md-12">
                  <label for="address" class="form-label">Shipping Address</label>
                  <textarea class="form-control" id="address" placeholder="Enter Full Address" rows="3"></textarea>
                </div>                

                <div class="col-md-3">
                  <label for="customerCode" class="form-label">Country</label>
                  <input type="text" class="form-control" id="customerCode" placeholder="Enter Country Name">
                </div>

                <div class="col-md-3">
                  <label for="customerCode" class="form-label">State</label>
                  <input type="text" class="form-control" id="customerCode" placeholder="Enter State Name">
                </div>

                <div class="col-md-3">
                  <label for="customerCode" class="form-label">City</label>
                  <input type="text" class="form-control" id="customerCode" placeholder="Enter City Name">
                </div>

                <div class="col-md-3">
                  <label for="pinCode" class="form-label">Pin Code</label>
                  <input type="text" class="form-control" id="pinCode" placeholder="Enter Pin Code">
                </div>

                <div class="col-md-12">
                  <label for="address" class="form-label">Billing  Address</label>
                  <textarea class="form-control" id="address" placeholder="Enter Full Address" rows="3"></textarea>
                </div>                

                <div class="col-md-3">
                  <label for="customerCode" class="form-label">Country</label>
                  <input type="text" class="form-control" id="customerCode" placeholder="Enter Country Name">
                </div>

                <div class="col-md-3">
                  <label for="customerCode" class="form-label">State</label>
                  <input type="text" class="form-control" id="customerCode" placeholder="Enter State Name">
                </div>

                <div class="col-md-3">
                  <label for="customerCode" class="form-label">City</label>
                  <input type="text" class="form-control" id="customerCode" placeholder="Enter City Name">
                </div>

                <div class="col-md-3">
                  <label for="pinCode" class="form-label">Pin Code</label>
                  <input type="text" class="form-control" id="pinCode" placeholder="Enter Pin Code">
                </div>

                <div class="col-md-3">
                  <label for="status" class="form-label">Status</label>
                  <select id="status" class="form-select">
                    <option selected>Active</option>
                    <option>Inactive</option>
                  </select>
                </div>

                <div class="col-md-12">
                  <div class="d-md-flex d-grid align-items-center gap-3">
                    <a href="ecommerce-customers.php" type="submit" class="btn btn-primary px-4">Submit</a>

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
            <img src="assets/images/orders/01.png" class="img-fluid rounded-3" width="75" alt="">
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
            <img src="assets/images/orders/02.png" class="img-fluid rounded-3" width="75" alt="">
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
            <img src="assets/images/orders/03.png" class="img-fluid rounded-3" width="75" alt="">
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
            <img src="assets/images/orders/04.png" class="img-fluid rounded-3" width="75" alt="">
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
            <img src="assets/images/orders/05.png" class="img-fluid rounded-3" width="75" alt="">
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
            <img src="assets/images/orders/06.png" class="img-fluid rounded-3" width="75" alt="">
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
            <img src="assets/images/orders/07.png" class="img-fluid rounded-3" width="75" alt="">
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
            <img src="assets/images/orders/08.png" class="img-fluid rounded-3" width="75" alt="">
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
        <button type="button" class="btn btn-grd btn-grd-primary" data-bs-dismiss="offcanvas">View Products</button>
      </div>
    </div>
  </div>
  <!--end cart-->


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