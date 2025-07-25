<?php include 'header.php'; ?>
<?php include 'sidebar.php'; ?>

<body>


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
              <li class="breadcrumb-item active" aria-current="page">Customer Details</li>
            </ol>
          </nav>
        </div>
        <div class="ms-auto">
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
        </div>
      </div>
      <!--end breadcrumb-->


      <div class="row">
        <div class="col-12 col-lg-4 d-flex">
          <div class="card w-100">
            <div class="card-body">
              <div class="position-relative">
                <img src="assets/images/gallery/18.png" class="img-fluid rounded" alt="">
                <div class="position-absolute top-100 start-50 translate-middle">
                  <img src="assets/images/avatars/02.png" width="100" height="100"
                    class="rounded-circle raised p-1 bg-white" alt="">
                </div>
              </div>
              <div class="text-center mt-5 pt-4">
                <h4 class="mb-1">Julinee Moree</h4>
                <p class="mb-0">Marketing Excutive</p>
              </div>
              <div class="d-flex align-items-center justify-content-center gap-3 my-5">
                <a href="javascript:;"
                  class="wh-48 bg-linkedin text-white rounded-circle d-flex align-items-center justify-content-center"><i
                    class="bi bi-linkedin fs-5"></i></a>
                <a href="javascript:;"
                  class="wh-48 bg-dark text-white rounded-circle d-flex align-items-center justify-content-center"><i
                    class="bi bi-twitter-x fs-5"></i></a>
                <a href="javascript:;"
                  class="wh-48 bg-facebook text-white rounded-circle d-flex align-items-center justify-content-center"><i
                    class="bi bi-facebook fs-5"></i></a>
                <a href="javascript:;"
                  class="wh-48 bg-pinterest text-white rounded-circle d-flex align-items-center justify-content-center"><i
                    class="bi bi-youtube fs-5"></i></a>
              </div>
              <div class="d-flex align-items-center justify-content-around">
                <div class="d-flex flex-column gap-2">
                  <h4 class="mb-0">798</h4>
                  <p class="mb-0">Posts</p>
                </div>
                <div class="d-flex flex-column gap-2">
                  <h4 class="mb-0">48K</h4>
                  <p class="mb-0">Following</p>
                </div>
                <div class="d-flex flex-column gap-2">
                  <h4 class="mb-0">24.3M</h4>
                  <p class="mb-0">Followers</p>
                </div>
              </div>

            </div>
            <ul class="list-group list-group-flush">
              <li class="list-group-item border-top">
                <b>Address</b>
                <br>
                123 Street Name, City, Australia
              </li>
              <li class="list-group-item">
                <b>Email</b>
                <br>
                mail.com
              </li>
              <li class="list-group-item">
                <b>Phone</b>
                <br>
                Toll Free (123) 472-796
                <br>
                Mobile : +91-9910XXXX
              </li>
            </ul>


          </div>
        </div>

        <div class="col-12 col-lg-8 d-flex">
          <div class="card w-100">
            <div class="card-body">
              <h5 class="mb-3">Send Notes to Customer</h5>
              <textarea class="form-control" placeholder="write somthing" rows="6" cols="6"></textarea>
              <button class="btn btn-filter w-100 mt-3">Add Meesage</button>
            </div>
            <div class="customer-notes mb-3">
              <div class="bg-light mx-3 my-0 rounded-3 p-3">
                <div class="notes-item">
                  <p class="mb-2">It is a long established fact that a reader will be distracted by the readable content
                    of a page when looking at its layout.
                    of letters, as opposed to using 'Content here, content here.</p>
                  <p class="mb-0 text-end fst-italic text-secondary">10 Apr, 2022</p>
                </div>
                <hr class="border-dotted">
                <div class="notes-item">
                  <p class="mb-2">Various versions have evolved over the years, sometimes</p>
                  <p class="mb-0 text-end fst-italic text-secondary">15 Apr, 2022</p>
                </div>
                <hr>
                <div class="notes-item">
                  <p class="mb-2">There are many variations of passages of Lorem Ipsum available, but the majority have
                    suffered
                    alteration in some</p>
                  <p class="mb-0 text-end fst-italic text-secondary">15 Apr, 2022</p>
                </div>
                <hr>
                <div class="notes-item">
                  <p class="mb-2">In publishing and graphic design, Lorem ipsum is a placeholder text commonly used to
                    demonstrate. quae ab illo inventore veritatis et quasi architecto</p>
                  <p class="mb-0 text-end fst-italic text-secondary">18 Apr, 2022</p>
                </div>
                <hr>
                <div class="notes-item">
                  <p class="mb-2">Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a
                    piece of classical Latin literature</p>
                  <p class="mb-0 text-end fst-italic text-secondary">22 Apr, 2022</p>
                </div>
                <hr>
                <div class="notes-item">
                  <p class="mb-2">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque
                    laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto
                    beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit
                    aut fugit, sed quia consequuntur magni dolores</p>
                  <p class="mb-0 text-end fst-italic text-secondary">22 Apr, 2022</p>
                </div>
                <hr>
                <div class="notes-item">
                  <p class="mb-2">On the other hand, we denounce with righteous indignation and dislike pleasure of the
                    moment, so blinded by desire, that they cannot foresee the pain and trouble that are bound to ensue;
                    and equal blame belongs to those</p>
                  <p class="mb-0 text-end fst-italic text-secondary">22 Apr, 2022</p>
                </div>
              </div>
            </div>
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
                    <th>Order</th>
                    <th>Expense</th>
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
                    <td>$865</td>
                    <td><span
                        class="lable-table bg-success-subtle text-success rounded border border-success-subtle font-text2 fw-bold">Paid<i
                          class="bi bi-check2 ms-2"></i></span></td>
                    <td><span
                        class="lable-table bg-success-subtle text-success rounded border border-success-subtle font-text2 fw-bold">Completed<i
                          class="bi bi-check2 ms-2"></i></span></td>
                    <td>Cash on delivery</td>
                    <td>Jun 12, 12:56 PM</td>
                    <td>
                      <div class="dropdown">
                        <button class="btn btn-sm dropdown-toggle dropdown-toggle-nocaret" type="button"
                          data-bs-toggle="dropdown">
                          <i class="bi bi-three-dots"></i>
                        </button>
                        <ul class="dropdown-menu">
                          <li><a class="dropdown-item" href="javascript:;"><i class="bi bi-eye-fill me-2"></i>View</a>
                          </li>
                          <li><a class="dropdown-item" href="javascript:;"><i
                                class="bi bi-box-arrow-right me-2"></i>Export</a></li>
                          <li class="dropdown-divider"></li>
                          <li><a class="dropdown-item text-danger" href="javascript:;"><i
                                class="bi bi-trash-fill me-2"></i>Delete</a></li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>#7845</td>
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
                      <div class="dropdown">
                        <button class="btn btn-sm dropdown-toggle dropdown-toggle-nocaret" type="button"
                          data-bs-toggle="dropdown">
                          <i class="bi bi-three-dots"></i>
                        </button>
                        <ul class="dropdown-menu">
                          <li><a class="dropdown-item" href="javascript:;"><i class="bi bi-eye-fill me-2"></i>View</a>
                          </li>
                          <li><a class="dropdown-item" href="javascript:;"><i
                                class="bi bi-box-arrow-right me-2"></i>Export</a></li>
                          <li class="dropdown-divider"></li>
                          <li><a class="dropdown-item text-danger" href="javascript:;"><i
                                class="bi bi-trash-fill me-2"></i>Delete</a></li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>#9635</td>
                    <td>$123</td>
                    <td><span
                        class="lable-table bg-warning-subtle text-warning rounded border border-warning-subtle font-text2 fw-bold">Pending<i
                          class="bi bi-info-circle ms-2"></i></span></td>
                    <td><span
                        class="lable-table bg-danger-subtle text-danger rounded border border-danger-subtle font-text2 fw-bold">Failed<i
                          class="bi bi-x-lg ms-2"></i></span></td>
                    <td>Cash on delivery</td>
                    <td>Jun 12, 12:56 PM</td>
                    <td>
                      <div class="dropdown">
                        <button class="btn btn-sm dropdown-toggle dropdown-toggle-nocaret" type="button"
                          data-bs-toggle="dropdown">
                          <i class="bi bi-three-dots"></i>
                        </button>
                        <ul class="dropdown-menu">
                          <li><a class="dropdown-item" href="javascript:;"><i class="bi bi-eye-fill me-2"></i>View</a>
                          </li>
                          <li><a class="dropdown-item" href="javascript:;"><i
                                class="bi bi-box-arrow-right me-2"></i>Export</a></li>
                          <li class="dropdown-divider"></li>
                          <li><a class="dropdown-item text-danger" href="javascript:;"><i
                                class="bi bi-trash-fill me-2"></i>Delete</a></li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>#2415</td>
                    <td>$986</td>
                    <td><span
                        class="lable-table bg-primary-subtle text-primary rounded border border-primary-subtle font-text2 fw-bold">Completed<i
                          class="bi bi-check2-all ms-2"></i></span></td>
                    <td><span
                        class="lable-table bg-warning-subtle text-warning rounded border border-warning-subtle font-text2 fw-bold">Pending<i
                          class="bi bi-info-circle ms-2"></i></span></td>
                    <td>Cash on delivery</td>
                    <td>Jun 12, 12:56 PM</td>
                    <td>
                      <div class="dropdown">
                        <button class="btn btn-sm dropdown-toggle dropdown-toggle-nocaret" type="button"
                          data-bs-toggle="dropdown">
                          <i class="bi bi-three-dots"></i>
                        </button>
                        <ul class="dropdown-menu">
                          <li><a class="dropdown-item" href="javascript:;"><i class="bi bi-eye-fill me-2"></i>View</a>
                          </li>
                          <li><a class="dropdown-item" href="javascript:;"><i
                                class="bi bi-box-arrow-right me-2"></i>Export</a></li>
                          <li class="dropdown-divider"></li>
                          <li><a class="dropdown-item text-danger" href="javascript:;"><i
                                class="bi bi-trash-fill me-2"></i>Delete</a></li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>#3526</td>
                    <td>$104</td>
                    <td><span
                        class="lable-table bg-danger-subtle text-danger rounded border border-danger-subtle font-text2 fw-bold">Failed<i
                          class="bi bi-x-lg ms-2"></i></span></td>
                    <td><span
                        class="lable-table bg-success-subtle text-success rounded border border-success-subtle font-text2 fw-bold">Completed<i
                          class="bi bi-check2 ms-2"></i></span></td>
                    <td>Cash on delivery</td>
                    <td>Jun 12, 12:56 PM</td>
                    <td>
                      <div class="dropdown">
                        <button class="btn btn-sm dropdown-toggle dropdown-toggle-nocaret" type="button"
                          data-bs-toggle="dropdown">
                          <i class="bi bi-three-dots"></i>
                        </button>
                        <ul class="dropdown-menu">
                          <li><a class="dropdown-item" href="javascript:;"><i class="bi bi-eye-fill me-2"></i>View</a>
                          </li>
                          <li><a class="dropdown-item" href="javascript:;"><i
                                class="bi bi-box-arrow-right me-2"></i>Export</a></li>
                          <li class="dropdown-divider"></li>
                          <li><a class="dropdown-item text-danger" href="javascript:;"><i
                                class="bi bi-trash-fill me-2"></i>Delete</a></li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>#7845</td>
                    <td>$368</td>
                    <td><span
                        class="lable-table bg-success-subtle text-success rounded border border-success-subtle font-text2 fw-bold">Paid<i
                          class="bi bi-check2 ms-2"></i></span></td>
                    <td><span
                        class="lable-table bg-danger-subtle text-danger rounded border border-danger-subtle font-text2 fw-bold">Failed<i
                          class="bi bi-x-lg ms-2"></i></span></td>
                    <td>Cash on delivery</td>
                    <td>Jun 12, 12:56 PM</td>
                    <td>
                      <div class="dropdown">
                        <button class="btn btn-sm dropdown-toggle dropdown-toggle-nocaret" type="button"
                          data-bs-toggle="dropdown">
                          <i class="bi bi-three-dots"></i>
                        </button>
                        <ul class="dropdown-menu">
                          <li><a class="dropdown-item" href="javascript:;"><i class="bi bi-eye-fill me-2"></i>View</a>
                          </li>
                          <li><a class="dropdown-item" href="javascript:;"><i
                                class="bi bi-box-arrow-right me-2"></i>Export</a></li>
                          <li class="dropdown-divider"></li>
                          <li><a class="dropdown-item text-danger" href="javascript:;"><i
                                class="bi bi-trash-fill me-2"></i>Delete</a></li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>#256</td>
                    <td>$865</td>
                    <td><span
                        class="lable-table bg-warning-subtle text-warning rounded border border-warning-subtle font-text2 fw-bold">Pending<i
                          class="bi bi-info-circle ms-2"></i></span></td>
                    <td><span
                        class="lable-table bg-primary-subtle text-primary rounded border border-primary-subtle font-text2 fw-bold">Completed<i
                          class="bi bi-check2-all ms-2"></i></span></td>
                    <td>Cash on delivery</td>
                    <td>Jun 12, 12:56 PM</td>
                    <td>
                      <div class="dropdown">
                        <button class="btn btn-sm dropdown-toggle dropdown-toggle-nocaret" type="button"
                          data-bs-toggle="dropdown">
                          <i class="bi bi-three-dots"></i>
                        </button>
                        <ul class="dropdown-menu">
                          <li><a class="dropdown-item" href="javascript:;"><i class="bi bi-eye-fill me-2"></i>View</a>
                          </li>
                          <li><a class="dropdown-item" href="javascript:;"><i
                                class="bi bi-box-arrow-right me-2"></i>Export</a></li>
                          <li class="dropdown-divider"></li>
                          <li><a class="dropdown-item text-danger" href="javascript:;"><i
                                class="bi bi-trash-fill me-2"></i>Delete</a></li>
                        </ul>
                      </div>
                    </td>
                  </tr>

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="card mt-4">
        <div class="card-body">
          <h5 class="mb-3 fw-bold">Wishlist<span class="fw-light ms-2">(46)</span></h5>
          <div class="product-table">
            <div class="table-responsive white-space-nowrap">
              <table class="table align-middle">
                <thead class="table-light">
                  <tr>

                    <th>Product Name</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-3">
                        <div class="product-box">
                          <img src="assets/images/top-products/06.png" width="55" class="rounded-3" alt="">
                        </div>
                        <div class="product-info">
                          <a href="javascript:;" class="product-title">Women Pink Floral Printed</a>
                          <p class="mb-0 product-category">Category : Fashion</p>
                        </div>
                      </div>
                    </td>
                    <td>Blue</td>
                    <td>Large</td>
                    <td>2</td>
                    <td>$59</td>
                    <td>189</td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-3">
                        <div class="product-box">
                          <img src="assets/images/top-products/05.png" width="55" class="rounded-3" alt="">
                        </div>
                        <div class="product-info">
                          <a href="javascript:;" class="product-title">Women Pink Floral Printed</a>
                          <p class="mb-0 product-category">Category : Fashion</p>
                        </div>
                      </div>
                    </td>
                    <td>Blue</td>
                    <td>Large</td>
                    <td>2</td>
                    <td>$59</td>
                    <td>189</td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-3">
                        <div class="product-box">
                          <img src="assets/images/top-products/04.png" width="55" class="rounded-3" alt="">
                        </div>
                        <div class="product-info">
                          <a href="javascript:;" class="product-title">Women Pink Floral Printed</a>
                          <p class="mb-0 product-category">Category : Fashion</p>
                        </div>
                      </div>
                    </td>
                    <td>Blue</td>
                    <td>Large</td>
                    <td>2</td>
                    <td>$59</td>
                    <td>189</td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-3">
                        <div class="product-box">
                          <img src="assets/images/top-products/03.png" width="55" class="rounded-3" alt="">
                        </div>
                        <div class="product-info">
                          <a href="javascript:;" class="product-title">Women Pink Floral Printed</a>
                          <p class="mb-0 product-category">Category : Fashion</p>
                        </div>
                      </div>
                    </td>
                    <td>Blue</td>
                    <td>Large</td>
                    <td>2</td>
                    <td>$59</td>
                    <td>189</td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-3">
                        <div class="product-box">
                          <img src="assets/images/top-products/02.png" width="55" class="rounded-3" alt="">
                        </div>
                        <div class="product-info">
                          <a href="javascript:;" class="product-title">Women Pink Floral Printed</a>
                          <p class="mb-0 product-category">Category : Fashion</p>
                        </div>
                      </div>
                    </td>
                    <td>Blue</td>
                    <td>Large</td>
                    <td>2</td>
                    <td>$59</td>
                    <td>189</td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-3">
                        <div class="product-box">
                          <img src="assets/images/top-products/01.png" width="55" class="rounded-3" alt="">
                        </div>
                        <div class="product-info">
                          <a href="javascript:;" class="product-title">Women Pink Floral Printed</a>
                          <p class="mb-0 product-category">Category : Fashion</p>
                        </div>
                      </div>
                    </td>
                    <td>Blue</td>
                    <td>Large</td>
                    <td>2</td>
                    <td>$59</td>
                    <td>189</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <div class="card mt-4">
        <div class="card-body">
          <h5 class="mb-3 fw-bold">Ratings & Reviews<span class="fw-light ms-2">(86)</span></h5>
          <div class="product-table">
            <div class="table-responsive white-space-nowrap">
              <table class="table align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Product Name</th>
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Status</th>
                    <th>Date</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <a href="javascript:;" class="product-title">Women Pink Floral Printed Panelled Pure Cotton</a>
                    </td>
                    <td>
                      <div class="product-rating text-warning">
                        <i class="bi bi-star-half"></i>
                        <i class="bi bi-star-half"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                      </div>
                    </td>
                    <td class="review-desc">This is very awesome product. It has good quality. I suggest everyone to use this
                      product. It is available at very low amount.</td>
                    <td><span
                        class="lable-table bg-success-subtle text-success rounded border border-success-subtle font-text2 fw-bold">Completed<i
                          class="bi bi-check2 ms-2"></i></span></td>
                    <td>Jun 12, 12:56 PM</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="javascript:;" class="product-title">Women Pink Floral Printed Panelled Pure Cotton</a>
                    </td>
                    <td>
                      <div class="product-rating text-warning">
                        <i class="bi bi-star-half"></i>
                        <i class="bi bi-star-half"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                      </div>
                    </td>
                    <td class="review-desc">This is very awesome product. It has good quality. I suggest everyone to use this
                      product. It is available at very low amount.</td>
                    <td><span
                        class="lable-table bg-danger-subtle text-danger rounded border border-danger-subtle font-text2 fw-bold">Failed<i
                          class="bi bi-x-lg ms-2"></i></span></td>
                    <td>Jun 12, 12:56 PM</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="javascript:;" class="product-title">Women Pink Floral Printed Panelled Pure Cotton</a>
                    </td>
                    <td>
                      <div class="product-rating text-warning">
                        <i class="bi bi-star-half"></i>
                        <i class="bi bi-star-half"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                      </div>
                    </td>
                    <td class="review-desc">This is very awesome product. It has good quality. I suggest everyone to use this
                      product. It is available at very low amount.</td>
                    <td><span
                        class="lable-table bg-primary-subtle text-primary rounded border border-primary-subtle font-text2 fw-bold">Completed<i
                          class="bi bi-check2-all ms-2"></i></span></td>
                    <td>Jun 12, 12:56 PM</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="javascript:;" class="product-title">Women Pink Floral Printed Panelled Pure Cotton</a>
                    </td>
                    <td>
                      <div class="product-rating text-warning">
                        <i class="bi bi-star-half"></i>
                        <i class="bi bi-star-half"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                      </div>
                    </td>
                    <td class="review-desc">This is very awesome product. It has good quality. I suggest everyone to use this
                      product. It is available at very low amount.</td>
                    <td><span
                        class="lable-table bg-success-subtle text-success rounded border border-success-subtle font-text2 fw-bold">Completed<i
                          class="bi bi-check2 ms-2"></i></span></td>
                    <td>Jun 12, 12:56 PM</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="javascript:;" class="product-title">Women Pink Floral Printed Panelled Pure Cotton</a>
                    </td>
                    <td>
                      <div class="product-rating text-warning">
                        <i class="bi bi-star-half"></i>
                        <i class="bi bi-star-half"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                      </div>
                    </td>
                    <td class="review-desc">This is very awesome product. It has good quality. I suggest everyone to use this
                      product. It is available at very low amount.</td>
                    <td><span
                        class="lable-table bg-danger-subtle text-danger rounded border border-danger-subtle font-text2 fw-bold">Failed<i
                          class="bi bi-x-lg ms-2"></i></span></td>
                    <td>Jun 12, 12:56 PM</td>
                  </tr>
                  <tr>
                    <td>
                      <a href="javascript:;" class="product-title">Women Pink Floral Printed Panelled Pure Cotton</a>
                    </td>
                    <td>
                      <div class="product-rating text-warning">
                        <i class="bi bi-star-half"></i>
                        <i class="bi bi-star-half"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                      </div>
                    </td>
                    <td class="review-desc">This is very awesome product. It has good quality. I suggest everyone to use this
                      product. It is available at very low amount.</td>
                    <td><span
                        class="lable-table bg-warning-subtle text-warning rounded border border-warning-subtle font-text2 fw-bold">Pending<i
                          class="bi bi-info-circle ms-2"></i></span></td>
                    <td>Jun 12, 12:56 PM</td>
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
  <script>
    new PerfectScrollbar(".customer-notes")
  </script>


</body>
</html>