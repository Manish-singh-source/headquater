# Purchase Workflow

This workflow explains the full process from vendor purchase order creation through invoice generation.
---

## Workflow Steps

1. A vendor purchase order (PO) is created either automatically by the system or manually by the user.
2. The vendor PO is exported from the system and sent to the vendor.
3. After the vendor sends the proforma invoice (PI), update the **PI Quantity** column in the Excel file and upload the updated file back into the system.
4. The warehouse person logs in, checks the received products, and downloads the Excel file.
5. The warehouse person updates the **Quantity Received** column in Excel, uploads the file into the system, and clicks **Submit** for admin approval.
6. The admin reviews and approves the purchase order.
7. After approval, the received quantity is updated in the warehouse stock.
8. If the purchase order was auto-generated, the product quantity is automatically blocked for the related sales order.
9. Export the sales order, update the **Final Fulfilled Quantity**, and upload the updated sales order file into the system.
10. Send the sales order for admin approval by clicking **Mark my products ready to ship**.
11. The admin opens the **Packaging List** tab, views the related sales order, and approves the ready-to-ship request.
12. The admin checks the **Ready to Ship** tab, where all approved sales orders ready for shipping are listed.
13. The admin opens the sales order details page, where all clients for that sales order are listed.
14. The admin selects a client and views that client's shipment details.
15. The admin changes the shipment status to **Shipped**.
16. The admin reviews the sales order details and applies filters to identify products eligible for invoice generation:

- Only products with status **Shipped**
- Only products whose **Final Quantity** is greater than `0`

17. The admin clicks the **Action** button, opens the dropdown, and selects **Generate Invoice**.
18. The invoice is generated and becomes available in the **Invoice** tab.

---

## Summary

This process ensures that:

- Vendor purchase quantities are recorded accurately
- Warehouse stock is updated only after admin approval
- Ready-to-ship products are verified before shipment
- Invoices are generated only for shipped products with fulfilled quantity
