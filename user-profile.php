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
              <li class="breadcrumb-item active" aria-current="page">User Profile</li>
            </ol>
          </nav>
        </div>

      </div>
      <!--end breadcrumb-->

      <div class="card w-100 d-flex  flex-sm-row flex-col">
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
          <!-- <li class="list-group-item border-top">
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
                            </li> -->



        </ul>


      </div>

      <div class="row">
        <div class="col-12 col-xl-8">
          <div class="card rounded-4 border-top border-4 border-primary border-gradient-1">
            <div class="card-body p-4">
              <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="">
                  <h5 class="mb-0 fw-bold">Edit Profile</h5>
                </div>
                <div class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle-nocaret options dropdown-toggle"
                    data-bs-toggle="dropdown">
                    <span class="material-icons-outlined fs-5">more_vert</span>
                  </a>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="javascript:;">Action</a></li>
                    <li><a class="dropdown-item" href="javascript:;">Another action</a></li>
                    <li><a class="dropdown-item" href="javascript:;">Something else here</a></li>
                  </ul>
                </div>
              </div>
              <form class="row g-4">
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
                  <input type="email" class="form-control" id="input4">
                </div>

                <div class="col-md-6">
                  <label for="input6" class="form-label">Company</label>
                  <input type="text" class="form-control" id="input3" placeholder="Copany Name">
                </div>
                <div class="col-md-6">
                  <label for="input7" class="form-label">Country</label>
                  <select id="input7" class="form-select">
                    <option selected="">Choose...</option>
                    <option>One</option>
                    <option>Two</option>
                    <option>Three</option>
                  </select>
                </div>

                <div class="col-md-6">
                  <label for="input8" class="form-label">City</label>
                  <input type="text" class="form-control" id="input8" placeholder="City">
                </div>
                <div class="col-md-4">
                  <label for="input9" class="form-label">State</label>
                  <select id="input9" class="form-select">
                    <option selected="">Choose...</option>
                    <option>One</option>
                    <option>Two</option>
                    <option>Three</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <label for="input10" class="form-label">Zip</label>
                  <input type="text" class="form-control" id="input10" placeholder="Zip">
                </div>
                <div class="col-md-12">
                  <label for="input11" class="form-label">Address</label>
                  <textarea class="form-control" id="input11" placeholder="Address ..." rows="4" cols="4"></textarea>
                </div>
                <div class="col-md-12">
                  <div class="d-md-flex d-grid align-items-center gap-3">
                    <button type="button" class="btn btn-success px-4">Update Profile</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="col-12 col-xl-4">
          <div class="card rounded-4">
            <div class="card-body">
              <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="">
                  <h5 class="mb-0 fw-bold">Change Password</h5>
                </div>

              </div>
              <div class="card-body mb-0">
                <div class="form-group mb-3 row">
                  <label class="form-label">Old Password</label>
                  <div class="col-lg-12 col-xl-12">
                    <input class="form-control" type="password" placeholder="Old Password">
                  </div>
                </div>
                <div class="form-group mb-3 row">
                  <label class="form-label">New Password</label>
                  <div class="col-lg-12 col-xl-12">
                    <input class="form-control" type="password" placeholder="New Password">
                  </div>
                </div>
                <div class="form-group mb-3 row">
                  <label class="form-label">Confirm Password</label>
                  <div class="col-lg-12 col-xl-12">
                    <input class="form-control" type="password" placeholder="Confirm Password">
                  </div>
                </div>

                <div class="form-group row">
                  <div class="col-lg-12 col-xl-12">
                    <button type="submit" class="btn btn-primary">Change Password</button>
                    <button type="button" class="btn btn-danger">Cancel</button>
                  </div>
                </div>

              </div>
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