# Vendor Purchase SKU Level

The **Vendor Purchase SKU Level** report is used to check vendor purchase history product-wise.

This page breaks vendor purchase data into SKU rows so the admin can review PO quantity, PI quantity, received quantity, tax value, invoice upload status, GRN upload status, approval status, and warehouse details for each SKU.

---

## Purpose of Vendor Purchase SKU Level

This report helps the admin review vendor purchase movement at item level.

From this report, users can:

- View vendor purchase records SKU-wise.
- Check which SKU belongs to which purchase order and vendor.
- Compare PO quantity, PI quantity, and PI received quantity.
- Review item name, HSN/SAC, rate, discount, taxable value, and GST values.
- Check whether invoice and GRN files are uploaded.
- Check PI approval status.
- Filter records by date, purchase order number, vendor, and SKU.
- Generate and download the filtered SKU-level report.

---

## Open Vendor Purchase SKU Level

To open this report:

1. Open the main menu.
2. Go to **Reports**.
3. Open **Vendor Purchase History**.
4. Click **SKU Level**.

The system will open the `/vendor-purchase-sku` page and show vendor purchase SKU records.

---

## Summary Cards

At the top of the page, the system shows SKU-level summary cards.

The summary includes:

- **Total Vendor Orders**
- **Total SKU**
- **Total PO Quantity**
- **Total PI Quantity**
- **Total PI Received Quantity**
- **Total Taxable Amount**
- **Total Amount**

These values help the admin quickly compare ordered quantity, PI quantity, received quantity, and purchase amount.

---

## Filter Vendor Purchase SKU Records

Use the filter section to search specific SKU records.

Available filters are:

1. **From Date**: Shows purchase orders created from this date.
2. **To Date**: Shows purchase orders created up to this date.
3. **Purchase Order No**: Shows selected purchase order records.
4. **Vendor Name**: Shows records for selected vendors.
5. **SKU**: Shows records for selected SKU codes.

To apply filters:

1. Select the required filter values.
2. Click **Apply Filter**.
3. The table and summary values will update based on the selected filters.

To remove filters, click **Reset Filters**.

---

## Vendor Purchase SKU Records

The report table shows product-level purchase details.

Important columns include:

- Purchase order number
- Purchase order date
- Vendor name
- SKU
- Item name
- HSN/SAC
- PO created date
- PI received date
- PO quantity
- PI quantity
- PI received quantity
- UoM
- Rate
- Discount
- Taxable value
- GST, CGST, SGST, and IGST
- GST amount
- Total amount
- Cess and cess amount
- Invoice uploaded status
- GRN uploaded status
- Shipping charges
- Approval status
- Warehouse

Use this table when product-wise purchase tracking is required.

---

## Generate Report

To download the SKU-level report:

1. Apply filters if required.
2. Click **Generate Report**.
3. The system downloads a CSV report.

If filters are selected, the downloaded report contains only the filtered records. If no filter is selected, the report contains all available vendor purchase SKU-level records.

---

## When To Use This Report

Use **Vendor Purchase SKU Level** when you need to check purchase data product-wise.

This report is useful for:

- SKU-wise purchase quantity checking
- PO quantity and received quantity comparison
- Vendor product reconciliation
- SKU-wise tax and total amount review
- Warehouse-wise received stock checking
- Invoice and GRN upload verification by product

---

## Difference Between Invoice Level And SKU Level

Use **Invoice Level** when you want purchase details grouped by purchase order or invoice.

Use **SKU Level** when you want purchase details split by product SKU.

For example:

- If one purchase order has five products, **Invoice Level** shows one purchase order row.
- The same purchase order in **SKU Level** shows product rows for each SKU.

---

## Summary

The Vendor Purchase SKU Level process follows these steps:

1. Open **Reports** from the menu.
2. Click **Vendor Purchase History**.
3. Open **SKU Level**.
4. Review summary cards.
5. Apply date, purchase order, vendor, or SKU filters if required.
6. Check SKU-level purchase records.
7. Click **Generate Report** to download the CSV file.
