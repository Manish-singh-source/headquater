# Packaging List

The **Packaging List** page is used after a sales order is sent to packaging. Warehouse users update final dispatch details for their assigned products, then send those packaged products to the admin for ready-to-ship approval.

---

## Purpose of Packaging List

The Packaging List process helps the team:

- View sales orders that are ready for packaging or ready to ship.
- Allow each warehouse user to work only on that warehouse's assigned allocation.
- Export packaging product details to Excel.
- Update final dispatch quantity, box count, and weight through Excel.
- Move packaged products to admin approval.
- Mark products and the sales order as **Ready To Ship** after approval.

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

After this step, the sales order status becomes **Ready To Package** and the selected product allocations move into packaging flow.

---

## Open Packaging List

To open the packaging order:

![Packaging List](/docsimages/packging-list.png)

1. Click **Packaging List** from the menu.
2. Use the status tabs if required.
3. Find the required sales order.
4. Click the view action to open the packaging product list.

Available tabs:

- **All Orders**: Shows orders in **Ready To Package**, **Ready To Ship**, and **Shipped** stages.
- **Ready To Package**: Shows orders with products in **Packaging**, **Packaged**, or **Ready to Ship Approval Pending**.
- **Ready To Ship**: Shows orders with completed ready-to-ship allocations.

Warehouse users see only the products assigned to their own warehouse. Admin and Super Admin users can see all warehouse allocations.

---

## Packaging Product Status

Inside a packaging order, products can show these statuses:

- **Packaging**: Product has been sent to the warehouse for packaging.
- **Packaged**: Warehouse has uploaded final dispatch details.
- **Ready to Ship Approval Pending**: Warehouse has requested admin approval.
- **Ready to Ship**: Admin has approved the warehouse allocation.
- **Shipped**: Product has moved forward from ready-to-ship/shipping flow.

The product status filter on the packaging details page can be used before export or review.

---

## Customer PO Table

The packaging details page shows the **Customer PO Table** with product and allocation details.

Main columns shown:

- Customer Name
- SKU Code
- Facility Name and Facility Location
- PO Date and PO Expiry Date
- HSN, Item Code, Description, GST, Basic Rate, Net Landing Rate, and MRP
- PO Quantity and Purchase Order Quantity
- Vendor PI Fulfillment Quantity and Vendor PI Received Quantity
- Warehouse Name and Warehouse Allocation
- Purchase Order No
- Total Dispatch Qty
- Final Dispatch Qty
- Case Pack Quantity
- Box Count
- Weight
- Status

For Super Admin, warehouse allocation values are shown warehouse-wise. For warehouse users, only their own warehouse quantity is shown.

---

## Export Packaging Excel

After opening the packaging list record:

1. Select a product status filter if required.
2. Click **Export to Excel**.
3. Download the **Packaging-Products.xlsx** file.
4. Open the downloaded file.

The export supports these product status filters:

- Packaging
- Packaged
- Ready to Ship Approval Pending
- Ready to Ship
- Shipped

If no status is selected, the export includes packaging, packaged, approval pending, and completed products.

---

## Packaging Excel Columns

The uploaded Excel must keep these required columns:

- Customer Name
- SKU Code
- Facility Name
- Facility Location
- PO Date
- PO Expiry Date
- HSN
- Item Code
- Description
- GST
- Basic Rate
- Net Landing Rate
- MRP
- PO Quantity
- Purchase Order Quantity
- Vendor PI Fulfillment Quantity
- Vendor PI Received Quantity
- Warehouse Name
- Warehouse Allocation
- Purchase Order No
- Total Dispatch Qty
- Final Dispatch Qty
- Case Pack Quantity
- Box Count
- Weight

Do not remove or rename these columns. The upload will fail if any required column is missing or empty.

---

## Update Packaging Details in Excel

In the downloaded packaging Excel sheet, update the packaging details.

Update these columns:

- **Final Dispatch Qty**: Enter the final quantity that will be dispatched.
- **Box Count**: Enter the number of boxes used for packaging.
- **Weight**: Enter the final package weight.

Important rules:

- **Final Dispatch Qty** must be greater than `0` for the row to be processed.
- For warehouse users, only that user's warehouse allocation is updated.
- For admin users, if multiple warehouse allocations exist, final dispatch quantity, box count, and weight are distributed against the allocations.
- After a valid upload, product status becomes **Packaged**.

If **Final Dispatch Qty** is less than dispatched quantity, the system records a **Shortage** issue. If **Final Dispatch Qty** is greater than dispatched quantity, the system records an **Exceed** issue.

---

## Upload Updated Packaging Excel

After saving the updated packaging Excel sheet:

1. Open the same packaging list record.
2. Click **Update PO**.
3. Upload the updated Excel sheet.
4. Submit the file.

Accepted file formats are `.xlsx`, `.csv`, and `.xls`.

The system validates the sales order, required headers, required row values, facility name, SKU code, and purchase order number. If valid rows are found, the packaging details are updated.

---

## Mark Products Ready to Ship

After uploading the updated packaging Excel sheet:

1. Go to the bottom of the packaging list page.
2. Click **Mark My Products Ready to Ship**.
3. The packaged products are sent to admin for approval.

For warehouse users, this step changes warehouse allocations from **Packaged** to **Ready to Ship Approval Pending**. The products are not final ready-to-ship until the admin approves them.

---

## Admin Approval

![Packaging Admin Approval](/docsimages/packging-admin.png)

After the warehouse team marks products ready to ship:

1. The admin opens the **Packaging List** tab.
2. The admin opens the related order.
3. Pending warehouse approval cards appear at the top of the page.
4. The admin can approve one warehouse or approve all pending warehouses.

After approval:

- Allocation approval status becomes **Approved**.
- Allocation product status becomes **Ready to Ship**.
- Product status becomes **Ready To Ship** when all required allocations for that product are approved.
- Sales order status becomes **Ready To Ship** when all products in the order are ready to ship.
- If all products are ready, the system redirects the admin to the Ready to Ship detail page.

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
10. Use **All Orders**, **Ready To Package**, or **Ready To Ship** tabs as needed.
11. Warehouse team opens the assigned packaging order.
12. Export the packaging Excel sheet.
13. Update **Final Dispatch Qty**, **Box Count**, and **Weight**.
14. Upload the updated packaging Excel sheet using **Update PO**.
15. Warehouse user clicks **Mark My Products Ready to Ship**.
16. Admin approves the pending warehouse request.
17. After all required products are approved, the sales order moves to **Ready To Ship**.
