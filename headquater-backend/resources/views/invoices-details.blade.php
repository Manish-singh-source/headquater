@extends('layouts.master')
@section('main-content')

    <!--start main wrapper-->
    <main class="main-wrapper">
        <div class="main-content">

            <div class="div d-flex my-2">
                <div class="col">
                    <h5 class="mb-3">INV0001</h5>
                </div>
                <div class="col text-end">
                    <a href="packaging-list.php" class="btn btn-success w-sm waves ripple-light" onclick="window.print()">
                        Print
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-between align-items-center border-bottom mb-3">
                        <div class="col-md-6">
                            <div class="mb-2 d-flex ">
                                <img src="assets/images/logo-icon.png" width="45" class="img-fluid" alt="logo">
                                <h4 class="ms-2">Headquater</h4>
                            </div>
                            <p class="mb-1">Office No. 501, 5th Floor, Ghanshyam Enclave, Next To Laljipada Police Station, Laljipada, Link Road, Kandivali (West), Mumbai - 400067. Maharashtra - India</p>
                            <p class="mb-1">+91 123 456 7895</p>
                            <p class="mb-1">headqater@gmail.com</p>
                            <p class="mb-1"> <b>GSTIN :</b> 274894919H1R </p>
                            <p class="mb-1"> <b>Contact Name :</b> Manish </p>
                        </div>
                        <div class="col-md-6">
                            <div class=" text-end mb-3">
                                <h5 class="text-gray mb-1">Invoice No <span class="text-primary">#INV0001</span></h5>
                                <p class="mb-1 fw-medium">Created Date : <span class="text-dark">Sep 24, 2024</span> </p>
                                <p class="mb-1 fw-medium">Due Date : <span class="text-dark">Sep 30, 2024</span> </p>
                                <p class="mb-1 fw-medium">PO Number : <span class="text-dark">541JDTCFW</span> </p>
                                <p class="mb-1 fw-medium">Place of Supply : <span class="text-dark">KA (29)</span> </p>
                            </div>
                        </div>
                    </div>
                    <div class="row border-bottom mb-3">
                        <div class="col-md-6">
                            <p class="text-dark mb-2 fw-semibold">Bill To</p>
                            <div>
                                <h5 class="mb-1">XYZ</h5>
                                <p class="mb-1">501 Ghanshyam Enclave Laljipada Kandivali (West), Mumbai - 400067</p>
                                <p class="mb-1"> <b>GSTIN :</b> 274894919H1R </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <p class="text-dark mb-2 fw-semibold">Ship To</p>
                            <div>
                                <h5 class="mb-1">ABC</h5>
                                <p class="mb-1">501 Ghanshyam Enclave Laljipada Kandivali (West), Mumbai - 400067</p>
                                <p class="mb-1"> <b>GSTIN :</b> 274894919H1R </p>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="customer-table">
                                <div class="table-responsive white-space-nowrap">
                                    <table id="example2" class="table table-striped">
                                        <thead class="table-light">
                                            <tr>
                                                <th>S.No</th>
                                                <th>Item Description</th>
                                                <th>HSN/SAC</th>
                                                <th>QTY</th>
                                                <th>Price</th>
                                                <th>Taxable Value</th>
                                                <th>IGST</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td class="w-50">Yera Pantry/Cookie/Snacks Round Glass Jar With Blue Lid 580 ml Set of 4
                                                </td>
                                                <td>7015845</td>
                                                <td>4 SET</td>
                                                <td>360</td>
                                                <td>1598</td>
                                                <td>256</td>
                                                <td>1560</td>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td class="w-50">Yera Pantry/Cookie/Snacks Round Glass Jar With Blue Lid 580 ml Set of 4
                                                </td>
                                                <td>7015845</td>
                                                <td>4 SET</td>
                                                <td>360</td>
                                                <td>1598</td>
                                                <td>256</td>
                                                <td>1560</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td class="w-50">Yera Pantry/Cookie/Snacks Round Glass Jar With Blue Lid 580 ml Set of 4
                                                </td>
                                                <td>7015845</td>
                                                <td>4 SET</td>
                                                <td>360</td>
                                                <td>1598</td>
                                                <td>256</td>
                                                <td>1560</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3"></td>
                                                <td>12 SET</td>
                                                <td>4785</td>
                                                <td>4785</td>
                                                <td>768</td>
                                                <td>4680</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row border-bottom mb-3">
                        <div class="col-md-5">
                            <div class="d-flex">
                                <p class="me-2">Account Holder Name : </p>
                                <p class="text-dark fw-medium">Technofra</p>
                            </div>
                            <div class="d-flex">
                                <p class="me-2">Bank Name : </p>
                                <p class="text-dark fw-medium">RBL</p>
                            </div>
                            <div class="d-flex">
                                <p class="me-2">Account Number : </p>
                                <p class="text-dark fw-medium">75519687</p>
                            </div>
                            <div class="d-flex">
                                <p class="me-2">Branch Name : </p>
                                <p class="text-dark fw-medium"> Kandivali</p>
                            </div>
                            <div class="d-flex">
                                <p class="me-2">IFSC Code : </p>
                                <p class="text-dark fw-medium">RBL185181</p>
                            </div>
                        </div>
                        <div class="col-md-5 ms-auto mb-3">
                            <div class="d-flex justify-content-between align-items-center border-bottom mb-2 pe-3">
                                <p class="mb-0">Total Taxable Value</p>
                                <p class="text-dark fw-medium mb-2">INR </p>
                            </div>
                            <div class="d-flex justify-content-between align-items-center border-bottom mb-2 pe-3">
                                <p class="mb-0">Total Taxable Amount</p>
                                <p class="text-dark fw-medium mb-2">INR</p>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2 pe-3">
                                <p class="mb-0">Rounded Off</p>
                                <p class="text-dark fw-medium mb-2">(-) 0.47</p>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2 pe-3">
                                <p class="mb-0">Total Value (in figure)</p>
                                <p class="text-dark fw-medium mb-2">INR</p>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2 pe-3">
                                <p class="mb-0">Total Value (in Word)</p>
                                <p class="text-dark fw-medium mb-2">Five thousand Seven Seventy Five</p>
                            </div>
                        </div>
                    </div>
                    <div class="row align-items-center border-bottom mb-3">
                        <div class="col-md-7">
                            <div>
                                <div class="mb-3">
                                    <h6 class="mb-1">Terms and Conditions</h6>
                                    <p>Please pay within 15 days from the date of invoice, overdue interest @ 14% will be charged on delayed payments.</p>
                                </div>
                                <div class="mb-3">
                                    <span>TOTAL SET :</span> <span>QTY 12</span>
                                    <span>TOTAL BOX COUNT :</span> <span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="text-center">
                                <img src="data:image/png;base64,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" class="img-fluid w-25" alt="sign">
                                <h6 class="fs-14 fw-medium pe-3">Manish</h6>
                                <p>Assistant Manager</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
    <!--end main wrapper-->

    @endsection
