# Purchase Order

The Purchase Order module is used to manage vendor purchase orders that are created for sales order quantities that are not available in stock.

When a sales order is created and some product quantity is unavailable, the system automatically creates a purchase order for the remaining quantity. If the unavailable products belong to different vendors, the system creates separate purchase orders for each vendor.

---

## When Purchase Orders Are Created

After a sales order is created, the system checks the unavailable quantity for each product.

If any quantity is still not available for the sales order:

- A purchase order is created automatically for that sales order.
- The purchase order is linked with the related sales order.
- Products are grouped vendor-wise.
- If multiple vendors are involved, separate purchase orders are created for each vendor.

This helps the purchase team order only the required unavailable quantity from the correct vendor.

---

## Open Purchase Orders

To open the Purchase Order section, click the **Purchase Order** tab from the menu.

After clicking the tab, the system displays all purchase orders that have been created.

![Purchase Order List](/docsimages/purchase-order.png)



### Details shown in the list

The Purchase Order list shows important information such as:

- **Purchase Order ID**
- **Sales Order ID**
- **Vendor Code**
- Purchase order status
- Action icons

Use the **Sales Order ID** to identify which sales order the purchase order belongs to. Then check the matching **Purchase Order ID** created against that sales order.

If the sales order contains unavailable products from multiple vendors, you will see multiple purchase orders for the same sales order, each linked to a different vendor.

---

## View a Purchase Order

To open a purchase order, click the **View** icon from the action column.

After clicking **View**, the system opens the purchase order details page.

On this page, the **Vendor PO Table** is displayed. This table contains the product and quantity details that must be shared with the vendor for purchasing.


![Purchase Order View](/docsimages/purchase-order-view.png)

---

## Export Vendor PO

From the purchase order details page, click **Export to Download**.

The system downloads the Vendor PO data. Share this downloaded file with the vendor so the vendor can prepare and send the proforma invoice (PI).

Before sharing the file, confirm the following details:

- Purchase order number
- Sales order number
- Vendor code
- Product details
- PO quantity

---

## Add Vendor PI

After the vendor sends the PI, check the PI details and update the purchase order with the vendor PI.

To add the vendor PI:

1. Open the required purchase order.
2. Click **Add Vendor PI**.
3. A popup will open.
4. Select the required **Warehouse**.
5. Upload the vendor PI file received from the vendor.
6. Click **Submit**.

### Select Warehouse

The **Select Warehouse** field is required.

Select the warehouse where the ordered PI quantity will be stored after the products are received. This ensures that the received stock is added to the correct warehouse.

### Upload Vendor PI

Upload the PI file received from the vendor. This file should contain the confirmed PI quantity and related product details.

---

## Verify Vendor PI Details

After submitting the vendor PI, the system displays the **Vendor PI Table** on the same purchase order details page.

Review the Vendor PI Table and cross-check the following details:

- PO quantity
- PI quantity
- Product details
- Vendor details
- Selected warehouse

Make sure the **PI Quantity** is entered correctly against the **PO Quantity**.

![PI Received](/docsimages/pi-recieved.png)



---

## Add Vendor GRN

Use this option when you need to upload the vendor GRN against a purchase order.

To add the vendor GRN:

1. Open the required purchase order.
2. Click **Add Vendor GRN** from the top side of the purchase order details page.
3. A popup will open.
4. Upload the GRN file received from the vendor.
5. Click **Submit**.

After submission, the vendor GRN is uploaded and saved against the purchase order.

![Vendor GRN](/docsimages/vendor-grn.png)

---

## Add Vendor Invoice

Use this option when you need to upload the vendor invoice against a purchase order.

To add the vendor invoice:

1. Open the required purchase order.
2. Click **Add Vendor Invoice** from the top side of the purchase order details page.
3. A popup will open.
4. Fill in the required invoice details.
5. Upload the vendor invoice file.
6. Click **Submit**.

After submission, the invoice details are saved against the purchase order.

### Add Payment Details

![Add Payment Details](/docsimages/vendor-payment-etailes.png)

After the vendor invoice is uploaded, the system displays the **Add Payment Details** option.

To add payment details:

1. Click **Add Payment Details**.
2. A popup will open.
3. Enter the **UTR No**.
4. Enter the **Payment Amount**.
5. Select the **Payment Method**.
6. Click **Submit**.

After submission, the payment details are saved against the vendor invoice.

---

## Process Completion

After the vendor PI is uploaded and verified, the purchase order update process is complete.

The purchase order is now ready for the next purchase workflow steps, such as receiving products, warehouse verification, and admin approval.

---

## Summary

The purchase order process follows these steps:

1. Create a sales order.
2. The system checks unavailable quantity.
3. The system automatically creates vendor-wise purchase orders for unavailable quantity.
4. Open the **Purchase Order** tab.
5. Check the **Sales Order ID**, **Purchase Order ID**, and **Vendor Code**.
6. Click the **View** icon for the required purchase order.
7. Review the **Vendor PO Table**.
8. Click **Export to Download** and share the file with the vendor.
9. Receive the vendor PI.
10. Click **Add Vendor PI**.
11. Select the warehouse where the PI quantity will be stored.
12. Upload the vendor PI file.
13. Submit the form.
14. Review the **Vendor PI Table** and confirm that PO quantity and PI quantity are correct.
