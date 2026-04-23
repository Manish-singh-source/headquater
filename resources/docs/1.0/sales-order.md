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
3. If you want to check stock from all warehouses, select **All**.
4. Upload the **Customer PO (CSV/XLSX)** file.
5. Click **Submit**.

After submit, the system displays the **Product Details** sheet.

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

## Verify Created Sales Order

After the sales order is created, it appears in the Sales Order list.

From the list page, you can:

- View the created sales order
- Check the order details
- Confirm product quantities
- Review order status
- Delete the order if required

---

## Summary

The sales order process follows these steps:

1. Open **Sales Order**.
2. Click **New Order**.
3. Check product availability using the customer PO file.
4. Download the **Product Details** Excel sheet.
5. Update purchase quantity, block quantity, and vendor code.
6. Save the updated Excel file.
7. Upload the updated file on the Create Order page.
8. Submit the form to create the sales order.
9. View the created order from the Sales Order list.
