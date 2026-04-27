# Track Order

The **Track Order** page is used to search and track a sales order by order number. It helps the user check order status, customer details, quantity fulfillment, and product-level PO details.

---

## Open Track Order

![Track Order](/docsimages/tr1.png)

To open the Track Order page:

1. Open the main menu.
2. Click **Track Order**.
3. The system will show the **Track Sales Order** page.

The Track Order page is available from the `/track-order` URL.

---

## Search Sales Order

![Track Order Search](/docsimages/tr2.png)

To track a sales order:

1. Enter the sales order number in **Track Order Id**.
2. Click **Track**.
3. The system will search for the entered sales order.
4. If the order is found, the system will open the order tracking details page.
5. If the order is not found, the system will show an error message.

---

## View Order Summary

![Track Order Summary](/docsimages/tr3.png)

The order details page shows the main sales order summary.

This section includes:

1. **Order Id**
2. **Customer Group Name**
3. **Status**
4. **Total PO Quantity**
5. **Total Purchase Order Quantity**
6. **PO Quantity Status**

The **PO Quantity Status** shows whether the order quantity is fulfilled or still needs fulfillment.

---

## Check Missing Data

The order tracking page can also show missing data alerts when imported order data does not match master records.

Possible missing data alerts:

1. **Products SKU Not Found**
2. **Customer Not Found**
3. **Vendor Not Found**

If any missing data is available, click **Download** to download the related Excel file and review the missing records.

---

## View Customer PO Table

![Customer PO Table](/docsimages/tr4.png)

The **Customer PO Table** shows product-level order details.

The table includes:

1. Customer name.
2. Facility name and location.
3. HSN and GST.
4. Item code and SKU code.
5. Brand and title.
6. Basic rate and product basic rate.
7. Rate confirmation.
8. Net landing rate and confirmation.
9. PO MRP and product MRP.
10. MRP confirmation.
11. PO number.
12. PO quantity.
13. Purchase order quantity.
14. Block quantity.
15. Quantity fulfilled.

Use this table to check whether each product line has been fulfilled correctly.

---

## Order Status Meaning

The tracking page can show different order statuses.

Common statuses:

1. **Pending** means the order has been created but not processed further.
2. **Blocked** means the order is on hold.
3. **Ready To Package** means the order is ready for packaging.
4. **Ready To Ship** means the order is ready for shipment.
5. **Shipped** means the order has been shipped.
6. **Completed** means the order process is completed.

---

## Summary

The Track Order process follows these steps:

1. Open **Track Order** from the menu.
2. Enter the sales order number.
3. Click **Track**.
4. Review the order summary.
5. Check quantity fulfillment status.
6. Download missing data files if available.
7. Review product-level details in the **Customer PO Table**.
