# E-Invoice

The **E-Invoice** page is used to review invoice details, generate an E-Invoice, download the E-Invoice PDF, generate an E-Way Bill, and review E-Way Bill details.


---

## Open E-Invoice Details


To open the E-Invoice flow:

1. Open the **Invoices** section.
2. Open the required invoice record.
3. Click the invoice details action.
4. The system will open the invoice details page.

The invoice details page is available from the `/invoices-details/{id}` URL.

---

## Invoice Details

The **Invoice Details** section shows the basic invoice information.

This section includes:

1. **Invoice Id**
2. **Invoice Download** button
3. **Client Name**
4. **Address**

If an E-Invoice has not been generated yet, the **Generate E-Invoice** button is shown beside the invoice download option.

---

## Generate E-Invoice


To generate an E-Invoice:

1. Open the invoice details page.
2. Review the invoice id, client name, and address.
3. Click **Generate E-Invoice**.
4. The system will send the invoice data to the E-Invoice API.
5. If the API responds successfully, the system will store the E-Invoice details.

After successful generation, the E-Invoice section will show the IRN and acknowledgement details.

---

## E-Invoice Details


After the E-Invoice is generated, the **E-Invoice Details** table is shown.

The table includes:

1. **E-Invoice IRN**
2. **Ack No**
3. **E-Invoice Status**
4. **E-Invoice PDF**
5. **Cancel Before**
6. **Action**

Use **Download** under E-Invoice PDF to download the E-Invoice PDF.

---

## Cancel E-Invoice


To cancel an E-Invoice:

1. Open the invoice details page.
2. Find the active E-Invoice row.
3. Click **Cancel E-Invoice**.
4. Select the **Cancel Reason**.
5. Enter the **Cancel Remark**.
6. Click **Cancel E-Invoice**.

Important notes:

1. E-Invoice cancellation is available only for active E-Invoices.
2. If an E-Way Bill is already generated for the E-Invoice, the E-Invoice cannot be cancelled directly.
3. Cancellation is allowed only within the allowed cancellation time.

---

## Generate E-Way Bill


To generate an E-Way Bill:

1. Open the invoice details page.
2. Generate the E-Invoice first.
3. In the E-Invoice Details table, click **Generate E-Way Bill**.
4. Enter the **Transporter Name**.
5. Enter the **Transporter ID (GSTIN)**.
6. Click **Generate E-Way Bill**.

After successful generation, the E-Way Bill details will be shown on the same page.

---

## E-Way Bill Details


The **E-Way Bill Details** table shows the generated E-Way Bill information.

The table includes:

1. **E-Invoice IRN**
2. **E-Way Bill No**
3. **E-Way Bill Date**
4. **Valid Till**
5. **E-Way Bill PDF**
6. **Cancel Before**
7. **Action**

Use **Download** under E-Way Bill PDF to download the E-Way Bill PDF.

---

## Cancel E-Way Bill

To cancel an E-Way Bill:

1. Open the invoice details page.
2. Go to the **E-Way Bill Details** section.
3. Click **Cancel E-Way Bill**.
4. Confirm the cancellation.

If the cancellation time has passed, the page will show **Cannot Cancel**.

---

## Summary

The E-Invoice flow follows these steps:

1. Open the invoice details page from `/invoices-details/{id}`.
2. Review the invoice details.
3. Download the normal invoice if required.
4. Click **Generate E-Invoice**.
5. Review IRN, Ack No, status, and PDF download.
6. Generate the E-Way Bill from the E-Invoice row.
7. Review E-Way Bill number, date, validity, and PDF download.
8. Cancel E-Invoice or E-Way Bill only when cancellation is allowed.
