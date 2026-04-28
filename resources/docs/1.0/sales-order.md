# Sales Order

The Sales Order module is used to create customer sales orders, check product availability, block available stock, and review all created orders.

---

## Open Sales Orders

To open the Sales Order section, click **Sales Order** from the menu.

After clicking, the system displays the Sales Order list page. From this page, you can check all sales orders created in the system.

![Sales Order View](/docsimages/sales-order-view.png)


### Details shown in the list

- Sales order information
- Order status
- Order date
- Created date
- Action icons


### Available actions

From the action icons, you can:

- View the sales order details
- Check the order information
- Delete the sales order if required

---

## Create New Sales Order

To create a new sales order, click the **New Order** button available at the top right side of the Sales Order list page.

After clicking **New Order**, the system opens the Create Order page.



Before creating the order, you must first check product availability. This helps you understand whether the warehouse has enough quantity to fulfill the customer PO order.

---

## Check Product Availability

Before uploading the customer PO for sales order creation, click **Check Availability**.

The system opens the **Check Availibility Of Products** popup.

![Check Availability Sales](/docsimages/check-avability-sales.png)

### Why availability check is required

Availability check helps you confirm:

- How much quantity is requested in the customer PO
- How much quantity is currently available in the selected warehouse
- How much quantity is unavailable
- Which quantity needs to be purchased from a vendor
- Which available quantity can be blocked for this sales order

---

## Fill Availability Check Details

In the **Check Availibility Of Products** popup, fill in the required details.

### Required fields

1. Select the **Customer Group** created for this order.
2. Select **Warehouse Name**.
3. If you want to check stock from all warehouses, select **All Warehouses**.
4. Upload the **Customer PO (CSV/XLSX)** file.
5. Click **Submit**.

After submit, the system displays the **Product Details** sheet.

---

## Customer PO Excel Required Columns

The customer PO file must contain the required columns in the correct format.

Important required columns include:

- Customer Name
- PO Number
- SKU Code
- Facility Name
- Facility Location
- PO Date
- PO Expiry Date
- HSN
- GST
- Portal Code
- Item Code
- Description
- Basic Rate
- Product Basic Rate
- Basic Rate Confirmation
- Net Landing Rate
- Product Net Landing Rate
- Net Landing Rate Confirmation
- MRP
- Product MRP
- MRP Confirmation
- PO Quantity
- Available Quantity
- Unavailable Quantity
- Case Pack Quantity
- Purchase Order Quantity
- Block
- Vendor Code
- Reason

If any required column is missing, the system will stop the upload and show a missing column error.

The system also checks duplicate SKU records in the uploaded file.

---

## Review Product Details

The **Product Details** sheet shows the availability result for the uploaded customer PO.

You can download this sheet by clicking **Export to Excel**.

### Important columns in the sheet

- **PO Quantity**: Quantity requested in the customer PO
- **Available Quantity**: Quantity currently available in the selected warehouse
- **Unavailable Quantity**: Quantity not available in the warehouse
- **Purchase Order Quantity**: Quantity that needs to be purchased
- **Block Quantity**: Quantity that should be blocked for this sales order
- **Vendor Code**: Vendor from whom the unavailable quantity will be purchased

---

## Update the Availability Excel Sheet

After checking the availability result, update the downloaded Excel sheet.

### Steps

1. Check the **PO Quantity** column.
2. Check how much quantity is available in **Available Quantity**.
3. Check how much quantity is missing in **Unavailable Quantity**.
4. In **Purchase Order Quantity**, enter the quantity that is not available and needs to be purchased.
5. In **Block Quantity**, enter the available quantity that you want to block for this order.
6. In **Vendor Code**, enter the vendor code from whom the missing quantity will be purchased.
7. Save the Excel file.

This updated Excel file will be used while creating the sales order.

![Check Availability Sales View](/docsimages/check-avability-sales-view.png)

---

## All Warehouses Auto Allocation

If **All Warehouses** is selected during availability check or sales order creation, the system checks stock across all active warehouses.

In this case:

- The system calculates total available quantity from multiple warehouses.
- Stock can be allocated from more than one warehouse.
- Warehouse allocation is shown in the product table.
- If stock is still short, the remaining quantity becomes purchase order quantity.
- Purchase orders are created for shortage quantity when required.

Use **All Warehouses** when one warehouse may not have enough stock but other warehouses may have available quantity.

---

## Submit the Sales Order

After preparing the updated Excel file, return to the Create Order page.

### Steps

1. Select the **Customer Group**.
2. Select the **Warehouse**.
3. Upload the updated customer PO Excel file that contains the blocked quantity and purchase quantity.
4. Click **Submit**.

After submit, the system creates the sales order.

![Create Sales Order](/docsimages/create-sales-order.png)

---

## Sales Order Creation Validation

When the sales order is submitted, the system validates the uploaded file.

The system checks:

1. Customer PO file is uploaded.
2. Required Excel columns are present.
3. Duplicate SKU records are not present.
4. Mandatory fields are filled in every row.
5. Customer facility exists in customer master.
6. Vendor code exists in vendor master.
7. SKU exists in product or warehouse stock data.

If customer, vendor, or SKU is not found, the system stores those rows separately and shows download options on the sales order view page.

---

## Verify Created Sales Order

After the sales order is created, it appears in the Sales Order list.

From the list page, you can:

- View the created sales order
- Check the order details
- Confirm product quantities
- Review order status
- Delete the order if required

---

## View Sales Order Details

Open the sales order by clicking the **View** icon from the Sales Order list.

The sales order details page shows:

- Order ID
- Customer group name
- Current status
- Total PO quantity
- Total purchase order quantity
- PO quantity fulfillment status
- Customer PO product table

If any uploaded data is not found in master data, the page also shows download links for:

- **Products SKU Not Found**
- **Customer Not Found**
- **Vendor Not Found**

Download these files, correct the master data or Excel data, and upload again if required.

---

## Customer PO Table

The **Customer PO Table** shows product-level order details.

Important columns include:

- Customer name
- Facility name and location
- HSN and GST
- Item code
- SKU code
- Brand and title
- Basic rate and confirmation
- Net landing rate and confirmation
- PO MRP and product MRP confirmation
- PO number
- PO quantity
- Purchase order quantity
- Vendor PI fulfillment quantity
- Vendor PI received quantity
- Block quantity
- Quantity fulfilled
- Final quantity fulfilled
- Final shipped quantity
- Warehouse allocation
- Invoice status
- Product status

Use this table to track order fulfillment from stock allocation to invoice generation.

---

## Filter Sales Order Products

On the sales order details page, filters are available above the Customer PO table.

Available filters include:

- **Final Quantity Fulfilled**
- **Product Status**
- **Brand**
- **PO Number**

These filters are also used when exporting the update Excel or generating invoices.

---

## Export And Update Sales Order

From the sales order details page, click **Action** and then **Export(Excel)**.

The system downloads an update Excel file for the selected or filtered sales order products.

This file contains fields such as:

- Order No
- SKU Code
- PO Number
- PO Quantity
- Purchase Order Quantity
- Vendor PI Fulfillment Quantity
- Vendor PI Received Quantity
- Block Quantity
- Quantity Fulfilled
- Final Fulfilled Quantity
- Warehouse Allocation
- Invoice Status

Update the required values in the Excel file, especially **Final Fulfilled Quantity**, and upload it back using **Update PO**.

When uploaded, the system updates sales order product quantities and warehouse allocations.

---

## Send To Packaging

After final fulfilled quantities are updated, select the required products and click **Action** > **Send To Packaging**.

When this action is submitted:

1. The sales order status changes to **Ready To Package**.
2. Selected products with final fulfilled quantity greater than `0` move to **Packaging** status.
3. Warehouse allocation product status is updated.
4. The system redirects to the Packaging List flow.

This step sends products from sales order processing to warehouse packaging.

---

## Generate Invoice From Sales Order

Invoices are generated from the sales order details page.

Before generating invoices:

1. Products should be shipped.
2. Warehouse allocation shipping status must be **Shipped**.
3. Already invoiced allocations are skipped.
4. Use filters such as **Brand**, **PO Number**, and **Product Status** if required.

To generate an invoice:

1. Open the sales order details page.
2. Select product rows if required.
3. Apply filters if required.
4. Click **Generate Invoice**.
5. Confirm the action.

The system groups invoice data by warehouse, PO number, and customer facility. It then creates invoice records and marks the related warehouse allocations as invoiced.

After invoice generation, the invoice becomes available in the **Invoice** section.

---

## Status Flow

A sales order can move through these main statuses:

1. **Blocked** after sales order creation and stock blocking.
2. **Ready To Package** after products are sent to packaging.
3. **Packaging** while warehouse packaging is in progress.
4. **Ready To Ship** after warehouse approval flow is completed.
5. **Shipped** after dispatch is completed.
6. **Completed** after final process completion.

The system also tracks product-level and warehouse-allocation-level statuses, so one sales order may contain products at different stages.

---

## Summary

The sales order process follows these steps:

1. Open **Sales Order**.
2. Click **New Order**.
3. Check product availability using the customer PO file.
4. Select a single warehouse or **All Warehouses**.
5. Download the **Product Details** Excel sheet.
6. Update purchase quantity, block quantity, and vendor code.
7. Save the updated Excel file.
8. Upload the updated file on the Create Order page.
9. Submit the form to create the sales order.
10. View the created order from the Sales Order list.
11. Download not-found files if customer, vendor, or SKU data is missing.
12. Export the sales order update Excel if quantities need to be updated.
13. Upload the updated PO file using **Update PO**.
14. Send fulfilled products to packaging.
15. After shipment, generate invoices from shipped products.
