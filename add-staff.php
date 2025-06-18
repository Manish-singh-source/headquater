<body>

    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>

    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">
            <div class="row">

                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <h5 class="card-title mb-0">
                                                Role Access
                                            </h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12 col-md-6">
                                            <label for="marital" class="form-label">Role
                                                <span class="text-danger">*</span></label>
                                            <select class="form-control" name="marital" id="marital">
                                                <option selected="" disabled="" value="">-- Select --</option>
                                                <option value="Admin">Admin</option>
                                                <option value="Sales Person">Sales Person</option>
                                                <option value="Operation Manager">Operation Manager</option>
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="marital" class="form-label">Status
                                                <span class="text-danger">*</span></label>
                                            <select class="form-control" name="marital" id="marital">
                                                <option selected="" disabled="" value="">-- Select --</option>
                                                <option value="Active">Active</option>
                                                <option value="Inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header border-bottom-dashed">
                                    <div class="row g-4 align-items-center">
                                        <div class="col-sm">
                                            <h5 class="card-title mb-0">
                                                Personal Information
                                            </h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12 col-md-6">
                                            <label for="firstname" class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" name="firstname" id="firstname" class="form-control" value="" required="" placeholder="Enter First Name">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="lastname" class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" name="lastname" id="lastname" class="form-control" value="" required="" placeholder="Enter Last Name">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="phone" class="form-label">Phone number <span class="text-danger">*</span></label>
                                            <input type="text" required="" name="phone" id="phone" class="form-control" value="" placeholder="Enter Phone number">
                                        </div>


                                        <div class="col-12 col-md-6">
                                            <label for="email" class="form-label">E-mail address <span class="text-danger">*</span></label>
                                            <input type="email" name="email" id="email" class="form-control" value="" placeholder="Enter Email id" required="">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="dob" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                            <input type="date" name="dob" id="dob" class="form-control" value="" placeholder="Enter Date of Birth" required="">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                            <select class="form-control" name="gender" id="gender">
                                                <option selected="" disabled="" value="">-- Select --</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="marital" class="form-label">Marital Status
                                                <span class="text-danger">*</span></label>
                                            <select class="form-control" name="marital" id="marital">
                                                <option selected="" disabled="" value="">-- Select --</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="card pb-4">
                                <div class="card-header border-bottom-dashed">
                                    <h5 class="card-title mb-0">
                                        Address Details
                                    </h5>
                                </div>

                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12 col-md-6">
                                            <label for="current-address" class="form-label">Current Address <span class="text-danger">*</span></label>
                                            <textarea name="current-address" id="current-address" class="form-control" value="" required="" placeholder="Enter Current Address"></textarea>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="permanent-address" class="form-label">Permanent Address </label>
                                            <textarea name="permanent-address" id="permanent-address" class="form-control" value="" placeholder="Enter Permanent Address"></textarea>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="city" class="form-label">City<span class="text-danger">*</span></label>
                                            <input type="text" required="" name="city" id="city" class="form-control" value="" placeholder="Enter City">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="state" class="form-label">State <span class="text-danger">*</span></label>
                                            <input type="text" name="state" id="state" class="form-control" value="" placeholder="Enter State" required="">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="country" class="form-label">Country <span class="text-danger">*</span></label>
                                            <input type="text" name="country" id="country" class="form-control" value="" required="" placeholder="Enter Country">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="pincode" class="form-label">Pincode<span class="text-danger">*</span></label>
                                            <input type="text" name="pincode" id="pincode" class="form-control" value="" required="" placeholder="Enter Pincode">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header border-bottom-dashed">
                                    <h5 class="card-title mb-0">
                                        Job Details:
                                    </h5>
                                </div>

                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12 col-md-6">
                                            <label for="emp_id" class="form-label">Employee ID <span class="text-danger">*</span></label>
                                            <input type="text" name="emp_id" id="emp_id" class="form-control" value="" required="" placeholder="Enter Employee ID">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="Department" class="form-label">Department/Role <span class="text-danger">*</span></label>
                                            <select class="form-control" name="Department" id="Department">
                                                <option selected="" disabled="" value="">-- Select --</option>
                                                <option value="">Sales</option>
                                                <option value="">Support</option>
                                                <option value="">Management</option>
                                            </select>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="designation" class="form-label">Designation/Position <span class="text-danger">*</span></label>
                                            <input type="text" name="designation" id="designation" class="form-control" value="" required="" placeholder="Enter Designation/Position">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="join_date" class="form-label">Joining Date <span class="text-danger">*</span></label>
                                            <input type="date" name="join_date" id="join_date" class="form-control" value="" required="" placeholder="Enter Joining Date">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="shift_timing" class="form-label">Shift Timing <span class="text-danger">*</span></label>
                                            <select class="form-control" name="shift_timing" id="shift_timing">
                                                <option selected="" disabled="" value="">-- Select --</option>
                                                <option value="">Morning Shift</option>
                                                <option value="">Afternoon Shift</option>
                                                <option value="">Night Shift</option>
                                            </select>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="work_location" class="form-label">Work Location <span class="text-danger">*</span></label>
                                            <input type="text" name="work_location" id="work_location" class="form-control" value="" required="" placeholder="Enter Work Location">
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <label for="supervisor" class="form-label">Supervisor/Manager Name <span class="text-danger">*</span></label>
                                            <input type="text" name="supervisor" id="supervisor" class="form-control" value="" required="" placeholder="Enter Supervisor/Manager Name">
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <!-- <div class="text-start mb-3">
                            <button type="submit" class="btn btn-success w-sm waves ripple-light">
                                Submit
                            </button>
                        </div> -->
                        </div>

                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header border-bottom-dashed">
                                    <h5 class="card-title mb-0">
                                        Identity Proof
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="">
                                            <label for="gov_id" class="form-label">Government ID Type <span class="text-danger">*</span></label>
                                            <select class="form-control" name="gov_id" id="gov_id">
                                                <option selected="" disabled="" value="">-- Select --</option>
                                                <option value="">Aadhar Card</option>
                                                <option value="">Pan Card</option>
                                            </select>
                                        </div>

                                        <div class="">
                                            <label for="card_no" class="form-label">ID Number <span class="text-danger">*</span></label>
                                            <input type="text" name="card_no" id="card_no" class="form-control" value="" required="" placeholder="Enter ID Number">
                                        </div>

                                        <div class="">
                                            <label for="document_image" class="form-label">Upload ID Document (Image/PDF) <span class="text-danger">*</span></label>
                                            <input type="file" name="document_image" id="document_image" class="form-control" value="" required="" placeholder="Upload ID Document">
                                        </div>

                                    </div>
                                </div>

                            </div>

                            <div class="card">
                                <div class="card-header border-bottom-dashed">
                                    <h5 class="card-title mb-0">
                                        Other Information:
                                    </h5>
                                </div>

                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="">
                                            <label for="profile_pic" class="form-label">Upload Profile Photo <span class="text-danger">*</span></label>
                                            <input type="file" name="profile_pic" id="profile_pic" class="form-control" value="" required="" placeholder="Upload Profile Photo">
                                        </div>

                                        <div class="">
                                            <label for="emergency_contact_name" class="form-label">Emergency Contact Name <span class="text-danger">*</span></label>
                                            <input type="text" name="emergency_contact_name" id="emergency_contact_name" class="form-control" value="" required="" placeholder="Enter Emergency Contact Name">
                                        </div>

                                        <div class="">
                                            <label for="emergency_contact_number" class="form-label">Emergency Contact Number <span class="text-danger">*</span></label>
                                            <input type="text" name="emergency_contact_number" id="emergency_contact_number" class="form-control" value="" required="" placeholder="Enter Emergency Contact Number">
                                        </div>

                                        <div class="">
                                            <label for="emp_status" class="form-label">Employment Status <span class="text-danger">*</span></label>
                                            <select class="form-control" name="emp_status" id="emp_status">
                                                <option selected="" disabled="" value="">-- Select --</option>
                                                <option value=""> Active</option>
                                                <option value="">Inactive</option>
                                                <option value="">Resigned</option>
                                                <option value="">Terminated</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="card">
                                <div class="card-header border-bottom-dashed">
                                    <h5 class="card-title mb-0">
                                        Salary Details:
                                    </h5>
                                </div>

                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="">
                                            <label for="salary_amount" class="form-label">Salary Amount <span class="text-danger">*</span></label>
                                            <input type="text" name="salary_amount" id="salary_amount" class="form-control" value="" required="" placeholder="Enter Salary Amount">
                                        </div>

                                        <div class="">
                                            <label for="payment-mode" class="form-label">Payment Mode <span class="text-danger">*</span></label>
                                            <select class="form-control" name="payment-mode" id="payment-mode">
                                                <option selected="" disabled="" value="">-- Select --</option>
                                                <option value=""> Bank Transfer</option>
                                                <option value="">Cash</option>
                                                <option value="">Cheque</option>
                                            </select>
                                        </div>

                                        <div class="">
                                            <label for="bank_account_number" class="form-label">Bank Account Number <span class="text-danger">*</span></label>
                                            <input type="text" name="bank_account_number" id="bank_account_number" class="form-control" value="" required="" placeholder="Enter Bank Account Number">
                                        </div>

                                        <div class="">
                                            <label for="bank_name" class="form-label">Bank Name <span class="text-danger">*</span></label>
                                            <input type="text" name="bank_name" id="bank_name" class="form-control" value="" required="" placeholder="Enter Bank Name">
                                        </div>

                                        <div class="">
                                            <label for="ifsc_code" class="form-label">IFSC Code <span class="text-danger">*</span></label>
                                            <input type="text" name="ifsc_code" id="ifsc_code" class="form-control" value="" required="" placeholder="Enter IFSC Code">
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="text-start mb-3">
                                <button type="submit" class="btn btn-success w-sm waves ripple-light">
                                    Submit
                                </button>
                            </div>
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