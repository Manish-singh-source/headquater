# Packaging List

The **Packaging List** tab is used to send final allocated sales order quantities to the warehouse team for packaging. The warehouse team updates the dispatch quantity, box count, and weight, then sends the packed order back to the admin for approval.

---

## Purpose of Packaging List

The Packaging List process helps the team:

- Confirm the final allocated quantity for a sales order.
- Prepare the final allocation sheet from the Sales Order section.
- Send only eligible products to packaging.
- Allow each warehouse team to package its assigned products.
- Update final dispatch quantity, box count, and weight through Excel.
- Send packed products to the admin for approval.

---

## Prepare the Final Allocation Sheet

First, prepare the final allocation quantity from the **Sales Order** tab.

To prepare the final allocation sheet:

1. Open the **Sales Order** tab from the menu.
2. Open the sales order for which final allocation quantity needs to be checked and sent for packaging.
3. Click the **Action** button.
4. Click **Export to Excel** and download the Excel sheet.
5. Open the downloaded Excel sheet.
6. Update the **Final Fulfilled Quantity** column.
7. Enter the final quantity that must be sent for order packaging.
8. Save the Excel sheet.

After saving the file, return to the sales order page.

---

## Upload the Final Allocation Sheet

After updating the **Final Fulfilled Quantity** column:

1. Open the required sales order.
2. Click the **Action** button.
3. Click **Update PO**.
4. Upload the updated Excel sheet.
5. Submit the file.

After upload, the final allocation sheet is ready. The order can now be sent for packaging.

![Packaging Allocation](/docsimages/packging-allocation.png)

---

## Filter Products Before Sending to Packaging

Before sending products to packaging, apply filters in the **Sales Order** section.

Required filters:

- **Fulfilled**: Greater than `0`
- **Product Status**: Pending

Optional filters:

- **Brand**: Use this filter if only one brand needs to be sent for packaging.
- **PO Number**: Use this filter if only one PO number needs to be sent for packaging.

These filters help send only the required products to the packaging team.

---

## Send Products to Packaging

After applying the required filters:

1. Click the **Action** button.
2. Click **Send to Packaging**.
3. The selected products will be sent to the **Packaging List** tab.

After this step, the order will appear in the **Packaging List** menu.

---

## Open Packaging List

To open the packaging order:

![Packaging List](/docsimages/packging-list.png)

1. Click **Packaging List** from the menu.
2. Find the required sales order.
3. Open the packaging list record.

The warehouse team will use this section from their own login. For example, if there are two warehouses, **Warehouse 1** and **Warehouse 2**, each warehouse team will see and package only the products assigned to their warehouse.

---

## Export Packaging Excel

After opening the packaging list record:

1. Click **Export to Excel**.
2. Download the packaging Excel sheet.
3. Open the downloaded file.

The warehouse team must update the dispatch and package details in this Excel sheet.

---

## Update Packaging Details in Excel

In the downloaded packaging Excel sheet, update the required packaging details.

Update these columns:

- **Final Dispatch Qty**: Enter the final quantity that will be dispatched.
- **Box Count**: Enter the number of boxes used for packaging.
- **Weight**: Enter the final package weight.

After filling in the details, save the Excel sheet.

---

## Upload Updated Packaging Excel

After saving the updated packaging Excel sheet:

1. Open the same packaging list record.
2. Click **Update PO**.
3. Upload the updated Excel sheet.
4. Submit the file.

The packaging details are now updated in the system.

---

## Mark Products Ready to Ship

After uploading the updated packaging Excel sheet:

1. Go to the end of the packaging list page.
2. Click **Mark My Product Ready to Ship**.
3. The order will be sent to the admin for approval.

---

## Admin Approval

![Packaging Admin Approval](/docsimages/packging-admin.png)

After the warehouse team marks the products ready to ship:

1. The admin opens the **Packaging List** tab.
2. The admin opens the related order.
3. The admin reviews the packaging details.
4. The admin approves the ready-to-ship request.

After admin approval, the order packaging process is completed.

---

## Summary

The Packaging List process follows these steps:

1. Open **Sales Order**.
2. Open the required sales order.
3. Export the sales order Excel sheet.
4. Update **Final Fulfilled Quantity**.
5. Upload the updated file using **Update PO**.
6. Apply filters for **Fulfilled greater than 0** and **Product Status Pending**.
7. Use optional filters such as **Brand** or **PO Number** if required.
8. Click **Send to Packaging** from the **Action** button.
9. Open **Packaging List** from the menu.
10. Warehouse team opens the assigned packaging order.
11. Export the packaging Excel sheet.
12. Update **Final Dispatch Qty**, **Box Count**, and **Weight**.
13. Upload the updated packaging Excel sheet using **Update PO**.
14. Click **Mark My Product Ready to Ship**.
15. Admin opens **Packaging List** and approves the request.
16. After approval, the order packaging process is completed.
