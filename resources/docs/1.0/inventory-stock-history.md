# Inventory Stock

The **Inventory Stock** report is used to check warehouse-wise stock availability, blocked quantity, stock value, and stock status.

This page helps the admin review which products are available in each warehouse and which products are low stock or out of stock.

---

## Purpose of Inventory Stock

The Inventory Stock report helps users:

- View stock by warehouse.
- Check product brand, brand title, category, and SKU.
- Review original quantity, available quantity, and hold quantity.
- Check taxable value, GST value, and total stock value.
- Identify normal stock, low stock, and out of stock products.
- Filter inventory records by warehouse, category, brand, SKU, date, and status.
- Generate and download the filtered inventory report.

---

## Open Inventory Stock

To open this report:

1. Open the main menu.
2. Go to **Reports**.
3. Click **Inventory Stock**.

The system will open the `/inventory-stock-history` page and show inventory stock records.

---

## Summary Cards

At the top of the page, the system shows inventory summary cards.

The summary includes:

- **Total Stock**
- **Total Available Stock**
- **Total Blocked Stock**
- **Total Stock Value**
- **Low Stock**
- **Out of Stock**

These values help the admin quickly understand stock position across warehouses.

---

## Filter Inventory Stock

Use the filter section to search specific inventory records.

Available filters are:

1. **Warehouse**: Shows stock from selected warehouses.
2. **Category**: Shows products from selected categories.
3. **Brand**: Shows products from selected brands.
4. **SKU**: Shows selected SKU records.
5. **From Date**: Shows stock records created from this date.
6. **To Date**: Shows stock records created up to this date.
7. **Status**: Shows records by stock status.

Available status options are:

- **Normal**: Available quantity is greater than `10`.
- **Low Stock**: Available quantity is between `1` and `10`.
- **Out of Stock**: Available quantity is `0`.

To apply filters:

1. Select the required filter values.
2. Click **Apply Filter**.
3. The table and summary values will update based on the selected filters.

To remove filters, click **Reset Filters**.

---

## Inventory Stock Records

The report table shows product stock details.

Important columns include:

- Warehouse
- Brand
- Brand title
- Category
- SKU
- PCS/Set
- Sets/CTN
- MRP
- Original quantity
- Available quantity
- Hold quantity
- Taxable value
- GST
- GST value
- Total value
- Status
- Date

Use this table to review stock availability and stock value product-wise.

---

## Stock Quantity Meaning

The quantity columns show different stock positions:

- **Original Quantity** shows the total stock received or stored for the SKU.
- **Available Quantity** shows stock that is currently available for use.
- **Hold Quantity** shows stock blocked or reserved for orders.

This helps the admin compare total stock, free stock, and blocked stock.

---

## Generate Report

To download the inventory stock report:

1. Apply filters if required.
2. Click **Generate Report**.
3. The system downloads a CSV report.

If filters are selected, the downloaded report contains only the filtered records. If no filter is selected, the report contains all available inventory stock records.

---

## When To Use This Report

Use **Inventory Stock** when you need to check current product stock in warehouses.

This report is useful for:

- Warehouse-wise stock checking
- Available and blocked stock comparison
- Low stock review
- Out of stock review
- SKU-wise stock value checking
- Inventory report download

---

## Summary

The Inventory Stock report process follows these steps:

1. Open **Reports** from the menu.
2. Click **Inventory Stock**.
3. Review the summary cards.
4. Apply warehouse, category, brand, SKU, date, or status filters if required.
5. Check the inventory stock table.
6. Click **Generate Report** to download the CSV file.
