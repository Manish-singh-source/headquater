<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Invoice</title>
  <style>
    * {
      font-family: Arial, sans-serif;
      box-sizing: border-box;
    }
    body {
      margin: 0;
      padding: 40px;
      font-size: 14px;
      color: #333;
    }
    .invoice-box {
      width: 100%;
    }
    .header {
      margin-bottom: 20px;
    }
    .header h2 {
      margin: 0;
      font-size: 22px;
      color: #333;
    }
    .header p {
      margin: 2px 0;
      font-size: 13px;
    }
    .invoice-meta {
      text-align: right;
      font-size: 13px;
    }
    .invoice-meta p {
      margin: 2px 0;
    }
    .section {
      margin-top: 20px;
      margin-bottom: 20px;
    }
    .two-col {
      width: 100%;
      display: table;
    }
    .two-col > div {
      display: table-cell;
      width: 50%;
      vertical-align: top;
      padding-right: 20px;
    }
    .two-col > div:last-child {
      padding-right: 0;
    }
    .label {
      font-weight: bold;
      margin-bottom: 5px;
    }
    .item-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      margin-bottom: 20px;
      box-shadow: 0 0 5px rgba(0,0,0,0.05);
    }
    .item-table th {
      background: #f9f9f9;
      font-weight: bold;
      text-align: center;
      padding: 8px;
      border: 1px solid #ddd;
    }
    .item-table td {
      border: 1px solid #ddd;
      padding: 8px;
    }
    .item-table tfoot td {
      font-weight: bold;
      background: #fafafa;
    }
    .bank-details, .summary-table {
      font-size: 13px;
    }
    .summary-table {
      width: 100%;
      margin-top: 10px;
      border-collapse: collapse;
    }
    .summary-table td {
      padding: 6px;
    }
    .summary-table .label {
      font-weight: bold;
    }
    .signature {
      text-align: right;
      margin-top: 60px;
    }
    .signature img {
      width: 120px;
      margin-bottom: 10px;
    }
    .footer {
      margin-top: 40px;
      font-size: 13px;
    }
  </style>
</head>

<body>
  <div class="invoice-box">
    <div class="two-col">
      <div class="header">
        <h2>Headquater</h2>
        <p>Office No. 501, 5th Floor, Ghanshyam Enclave,</p>
        <p>Next To Laljipada Police Station, Kandivali (W), Mumbai - 400067</p>
        <p>+91 123 456 7895 | headqater@gmail.com</p>
        <p>GSTIN : 2748949191HR</p>
        <p><strong>Contact Name:</strong> Manish</p>
      </div>
      <div class="invoice-meta">
        <p><strong>Invoice No:</strong> <span style="color:#007bff;">#INV0001</span></p>
        <p><strong>Created Date:</strong> Sep 24, 2024</p>
        <p><strong>Due Date:</strong> Sep 30, 2024</p>
        <p><strong>PO Number:</strong> 54J1DTCFW</p>
        <p><strong>Place of Supply:</strong> KA (29)</p>
      </div>
    </div>

    <div class="two-col section">
      <div>
        <p class="label">Bill To</p>
        <p><strong>XYZ</strong></p>
        <p>501 Ghanshyam Enclave, Kandivali (W), Mumbai - 400067</p>
        <p><strong>GSTIN:</strong> 2748949191HR</p>
      </div>
      <div>
        <p class="label">Ship To</p>
        <p><strong>ABC</strong></p>
        <p>501 Ghanshyam Enclave, Kandivali (W), Mumbai - 400067</p>
        <p><strong>GSTIN:</strong> 2748949191HR</p>
      </div>
    </div>

    <table class="item-table">
      <thead>
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
          <td>Yera Pantry/Cookie/Snacks Round Glass Jar With Blue Lid 580 ml Set of 4</td>
          <td>7015845</td>
          <td>4 SET</td>
          <td>360</td>
          <td>1598</td>
          <td>256</td>
          <td>1560</td>
        </tr>
        <tr>
          <td>2</td>
          <td>Yera Pantry/Cookie/Snacks Round Glass Jar With Blue Lid 580 ml Set of 4</td>
          <td>7015845</td>
          <td>4 SET</td>
          <td>360</td>
          <td>1598</td>
          <td>256</td>
          <td>1560</td>
        </tr>
        <tr>
          <td>3</td>
          <td>Yera Pantry/Cookie/Snacks Round Glass Jar With Blue Lid 580 ml Set of 4</td>
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
          <td colspan="3">Total</td>
          <td>12 SET</td>
          <td></td>
          <td>4785</td>
          <td>768</td>
          <td>4680</td>
        </tr>
      </tfoot>
    </table>

    <div class="two-col section">
      <div class="bank-details">
        <p><strong>Account Holder Name:</strong> Technofra</p>
        <p><strong>Bank Name:</strong> RBL</p>
        <p><strong>Account Number:</strong> 75519687</p>
        <p><strong>Branch Name:</strong> Kandivali</p>
        <p><strong>IFSC Code:</strong> RBL185181</p>
      </div>
      <div>
        <table class="summary-table">
          <tr><td class="label">Total Taxable Value:</td><td>INR</td></tr>
          <tr><td class="label">Total Taxable Amount:</td><td>INR</td></tr>
          <tr><td class="label">Rounded Off:</td><td>(-) 0.47</td></tr>
          <tr><td class="label">Total Value (in figure):</td><td>INR</td></tr>
          <tr><td class="label">Total Value (in Word):</td><td>Five thousand Seven Seventy Five</td></tr>
        </table>
      </div>
    </div>

    <div class="footer">
      <p><strong>Terms and Conditions</strong></p>
      <p>Please pay within 15 days from the date of invoice, overdue interest @ 14% will be charged on delayed payments.</p>
      <p><strong>TOTAL SET:</strong> QTY 12 &nbsp;&nbsp;&nbsp; <strong>TOTAL BOX COUNT:</strong></p>
    </div>

    <div class="signature">
      <img src="https://upload.wikimedia.org/wikipedia/commons/5/5a/Signature_example.svg" alt="signature">
      <p><strong>Manish</strong><br>Assistant Manager</p>
    </div>
  </div>
</body>

</html>
