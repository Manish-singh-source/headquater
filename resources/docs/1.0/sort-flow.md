# Sort Work Flow

This page explains the complete short workflow from master setup to sales order, purchase order, warehouse receiving, packaging, shipping, invoice, e-invoice, and e-way bill.

---

## Required Master Setup

Before starting any order flow, the master data must be ready.

Required setup:

1. Create **Customer Group** in the Master section.
2. Create **Vendor** in the Master section.
3. Upload **Products**.
4. Complete **SKU Mapping**.
5. Add **Warehouse**.

These records are required because sales order, purchase order, warehouse receiving, packaging, and invoice flow depend on this master data.

---

## Sales Order Received

When a customer PO or sales order is received:

1. Open the **Sales Order** section.
2. Upload or create the sales order.
3. Check product availability.
4. Download the sales order Excel sheet if required.
5. Update the final quantity in the sheet.
6. Upload the final sheet to create or update the sales order.

After this step, the sales order is created in the system.

---

## Automatic Purchase Order

If the sales order quantity is not fully fulfilled from available stock, the system creates a purchase order automatically for the shortage quantity.

After the purchase order is created:

1. Open the **Purchase Order** section.
2. Check the generated purchase order.
3. Download the vendor PO.
4. Send the PO to the vendor.

---

## Vendor PI Upload

After the vendor confirms the final quantity:

1. Open the related purchase order.
2. Upload the vendor PI with final quantity details.
3. Submit the file.

After PI upload, the product becomes available in the warehouse team's received product flow.

---

## Warehouse Product Receiving

The warehouse team receives the product in their menu.

Warehouse receiving steps:

1. Open **Received Products** from the warehouse login.
2. Open the received product list.
3. Download the Excel file.
4. Check the product quantity in Excel.
5. Add the final received quantity.
6. Upload the updated file.

After upload, the admin receives a notification/message that an order has reached the warehouse.

---

## Admin Approval for Received Products

After the warehouse team uploads the received quantity:

1. Admin opens the **Purchase Order** section.
2. The related order is highlighted for approval.
3. Admin opens the order.
4. Admin reviews the warehouse received quantity.
5. Admin approves the received product.

After approval, the product quantity is actually added or updated in warehouse stock.

---

## Update Sales Order After Stock Received

After warehouse stock is updated:

1. Open the **Sales Order** section.
2. Open the related sales order.
3. Check the received quantity.
4. Download the Excel file.
5. Update the final quantity.
6. Upload the updated Excel file.

Now the final fulfilled quantity is ready to send for packaging.

---

## Send Products to Packaging

To send products for packaging:

1. Open the sales order.
2. Apply filter where quantity is greater than `0`.
3. Select **Product Status** as **Pending**.
4. Select the required products.
5. Click **Send to Packaging**.

Only products with quantity greater than `0` and pending status should be sent to packaging.

---

## Warehouse Packaging Flow

After products are sent to packaging:

1. Each warehouse user opens their own login.
2. Open **Packaging List** from the dashboard/menu.
3. Open the assigned packaging order.
4. Check the product list.
5. Download the Excel file.
6. Add final packaging quantity, box count, and weight.
7. Upload the updated file.
8. Click **Mark My Products Ready to Ship**.

This sends the packaging request to the admin for approval.

---

## Admin Packaging Approval

After the warehouse team submits packaging details:

1. Admin opens the **Packaging List** section.
2. Admin opens the related order.
3. Admin reviews the pending warehouse approval.
4. Admin approves the request.

After admin approval, the order moves out of Packaging List and appears in **Ready to Ship**.

---

## Ready to Ship and Shipped

In the **Ready to Ship** section:

1. Open the ready-to-ship order.
2. Review the order details.
3. Change the order status to **Shipped**.

After this step, the order is shipped.

---

## Generate Invoice

After the order is shipped:

1. Open the **Sales Order** section.
2. Open the related order.
3. Apply filter where quantity is greater than `0`.
4. Select **Order Status** as **Shipped**.
5. Select the required products.
6. Click **Generate Invoice**.

After this step, the invoice is generated for the order.

---

## Check and Download Invoice

To check the generated invoice:

1. Open the **Invoice** tab.
2. Find the generated invoice.
3. Open or review the invoice details.
4. Download the invoice if required.

---

## Generate E-Invoice and E-Way Bill

After invoice generation:

1. Open the invoice.
2. Click the **E-Invoice** button.
3. Generate the e-invoice.
4. After e-invoice generation, generate the **E-Way Bill**.

After e-invoice and e-way bill generation, the flow is complete.

---

## Complete Flow Summary

1. Create required master data.
2. Receive and create sales order.
3. Check stock availability.
4. Create automatic purchase order for shortage quantity.
5. Download vendor PO and send it to vendor.
6. Upload vendor PI after vendor final quantity confirmation.
7. Warehouse receives products and uploads final received quantity.
8. Admin approves received products.
9. Warehouse stock quantity gets updated.
10. Open sales order and update final fulfilled quantity.
11. Send pending products with quantity greater than `0` to packaging.
12. Warehouse updates final packaging details.
13. Warehouse sends products for ready-to-ship approval.
14. Admin approves packaging request.
15. Order moves to Ready to Ship.
16. Change status to Shipped.
17. Generate invoice from sales order.
18. Check and download invoice from Invoice tab.
19. Generate e-invoice.
20. Generate e-way bill.
