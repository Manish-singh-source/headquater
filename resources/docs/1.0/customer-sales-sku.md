# Customer Sales SKU Level

The **Customer Sales SKU Level** report is used to check customer sales history product-wise.

This page breaks customer sales data into SKU rows so the admin can review customer, warehouse, PO, SKU, dispatched quantity, invoice amount, and purchase cost details for each product.

---

## Purpose of Customer Sales SKU Level

This report helps the admin review customer sales movement at product level.

From this report, users can:

- View customer sales records SKU-wise.
- Check customer group, warehouse, customer name, and invoice number.
- Review PO number and PO SKU.
- Check product title, brand, and HSN.
- Compare ordered quantity and dispatched quantity.
- Review box count, weight, unit price, taxable amount, GST amount, and invoice amount.
- Check purchase order quantity, purchase rate, purchase GST, and total purchase amount.
- Filter records by date, customer, warehouse, payment status, customer group, and PO number.
- Generate and download the filtered Excel report.

---

## Open Customer Sales SKU Level

To open this report:

1. Open the main menu.
2. Go to **Reports**.
3. Open **Customer Sales**.
4. Click **SKU Level**.

The system will open the `/customer-sales-sku` page and show customer sales SKU records.

---

## Summary Cards

At the top of the page, the system shows SKU-level summary cards.

The summary includes:

- **Total Invoices**
- **Total Customers**
- **Total Taxable Amount**
- **Total Invoice Amount**
- **Total Purchase Order**
- **Total Purchase Order Quantity**
- **Total Purchase Order Amount**

These values help the admin compare sales invoice value with purchase order quantity and purchase amount.

---

## Filter Customer Sales SKU Records

Use the filter section to search specific SKU-level sales records.

Available filters are:

1. **From Date**: Shows sales orders from this date.
2. **To Date**: Shows sales orders up to this date.
3. **Customer Name**: Shows records for selected customers.
4. **Warehouse**: Shows records for selected warehouses.
5. **Payment Status**: Shows records by paid, partial, or unpaid status.
6. **Customer Group**: Shows records for selected customer groups.
7. **PO No**: Shows selected PO records.

To apply filters:

1. Select the required filter values.
2. Click **Apply Filter**.
3. The table and summary values will update based on the selected filters.

To remove filters, click **Reset Filters**.

---

## Customer Sales SKU Records

The report table shows product-level sales details.

Important columns include:

- Sales order number
- Customer group name
- Warehouse name
- Customer name
- Invoice number
- Customer phone number
- Customer email
- Customer city and state
- PO number
- PO SKU
- Title
- Brand
- HSN
- Ordered quantity
- Dispatched quantity
- Box count
- Weight
- Unit price
- Taxable amount
- GST
- GST amount
- Invoice amount
- Purchase order quantity
- Purchase rate
- Purchase subtotal
- Purchase GST
- Purchase GST amount
- Total purchase amount

Use this table when product-wise customer sales and purchase cost comparison is required.

---

## Generate Report

To download the SKU-level report:

1. Apply filters if required.
2. Click **Generate Report**.
3. The system downloads an Excel report.

If filters are selected, the downloaded report contains only the filtered records. If no filter is selected, the report contains all available customer sales SKU-level records.

---

## Difference Between Invoice Level And SKU Level

Use **Invoice Level** when you want sales details grouped by invoice.

Use **SKU Level** when you want sales details split by product SKU and warehouse allocation.

For example:

- If one invoice contains multiple products, **Invoice Level** shows the invoice as one sales record.
- The same sales order in **SKU Level** shows separate rows for each SKU and warehouse allocation.

---

## When To Use This Report

Use **Customer Sales SKU Level** when you need to check customer sales product-wise.

This report is useful for:

- SKU-wise customer sales review
- Warehouse-wise dispatch checking
- Ordered quantity and dispatched quantity comparison
- Invoice amount and purchase amount comparison
- Product-wise margin review
- SKU-level Excel report download

---

## Summary

The Customer Sales SKU Level process follows these steps:

1. Open **Reports** from the menu.
2. Click **Customer Sales**.
3. Open **SKU Level**.
4. Review summary cards.
5. Apply date, customer, warehouse, payment status, customer group, or PO filters if required.
6. Check SKU-level sales records.
7. Click **Generate Report** to download the Excel file.
