# Received Products

The **Received Products** tab is used by the warehouse team to manage products received from vendors against purchase orders.

When a vendor sends products for a purchase order, the warehouse team can check the received vendor order, update the received quantity, record any shortage or issue, and send the received product details for admin approval.

---

## Purpose of Received Products

The Received Products section helps the warehouse team track vendor deliveries before the stock is added to the warehouse.

From this section, the warehouse team can:

- View vendor purchase orders that are ready for warehouse receiving.
- Check which vendor has delivered products.
- Review the ordered product details.
- Update the actual quantity received from the vendor.
- Record issue units if the vendor sends less quantity or if any product has a problem.
- Upload the updated Excel file for admin approval.

The product quantity is added to warehouse stock only after admin approval.

---

## Open Received Products

To open this section, click **Received Products** from the menu.

![Received Products List](/docsimages/received-products-list.png)

The system displays the vendor PI records that are available for warehouse receiving. The warehouse team can use this list to check which vendor order has arrived and how much quantity needs to be stored in the warehouse.

---

## View Vendor Order

To check a received vendor order:

1. Open the **Received Products** tab.
2. Find the required vendor order.
3. Click the **View** icon from the action column.

After clicking **View**, the system opens the received product details page.

![Received Products View](/docsimages/received-products-view.png)

---

## Update PI Products Table

On the received product details page, the system displays the **Update PI Products** table.

This table shows the PI product details, including:

- Purchase order number
- Vendor SKU code
- Product title
- MRP
- PO quantity
- PI quantity
- Quantity received
- Issue units
- Issue description

Use this table to verify what quantity was ordered, what quantity was confirmed in the vendor PI, and what quantity has actually arrived at the warehouse.

---

## Export Received Products Excel

Before updating received quantities, download the Excel file from the system.

To download the file:

1. Open the received product details page.
2. Click **Export to Excel**.
3. The system downloads the received products Excel sheet.

This Excel file must be updated with the actual received quantity before uploading it back into the system.

---

## Update Quantity Received

Open the downloaded Excel file and update the **Quantity Received** column.

Enter the actual quantity received by the warehouse for each product.

For example:

- If the PI quantity is `100` and the warehouse received `100`, enter `100` in **Quantity Received**.
- If the PI quantity is `100` and the warehouse received only `90`, enter `90` in **Quantity Received**.

---

## Add Issue Units

If the vendor sends less quantity or there is any product issue, update the issue details in the same Excel sheet.

Use the following columns:

- **Issue Units**: Enter the shortage quantity or issue quantity.
- **Issue Description**: Enter the reason or description of the issue.

For example, if the PI quantity is `100` and only `90` units are received, enter:

- **Quantity Received**: `90`
- **Issue Units**: `10`
- **Issue Description**: `10 units short received from vendor`

After updating the Excel file, save it. The received product Excel file is now ready for upload.

---

## Upload Updated PI Products

After preparing the Excel file:

1. Open the required record from **Received Products**.
2. Click **Update PI Products**.
3. Upload the updated vendor PI Excel file.
4. Submit the form.

After upload, the system saves the received product details and sends a notification to the admin.

---

## Admin Approval

![Received Products Accept](/docsimages/received-products-accept.png)

After the warehouse team uploads the received product Excel file, the admin receives a notification that product quantity has arrived in the warehouse.

The admin must open the related purchase order and review the received quantity.

If the admin accepts the received quantity:

- The received product quantity is added to the warehouse stock.
- The stock becomes available in the selected warehouse.
- Any recorded issue details remain available for tracking.

If the admin does not approve the received quantity:

- The quantity is not added to warehouse stock.
- The product stock remains unchanged.

This approval step ensures that warehouse stock is updated only after admin verification.

---

## Summary

The Received Products process follows these steps:

1. Vendor sends products against a purchase order.
2. Warehouse team opens **Received Products**.
3. Warehouse team views the required vendor order.
4. The **Update PI Products** table is checked.
5. Warehouse team clicks **Export to Excel**.
6. Warehouse team updates **Quantity Received** in the Excel sheet.
7. If any product is short or has an issue, **Issue Units** and **Issue Description** are updated.
8. Warehouse team uploads the updated Excel file from **Update PI Products**.
9. Admin receives a notification.
10. Admin reviews and accepts the received quantity from the purchase order.
11. After admin approval, the product quantity is added to warehouse stock.
