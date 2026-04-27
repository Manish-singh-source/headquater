# Vendor Return

The **Vendor Return** page is used to review vendor return products and decide whether the products should be accepted into warehouse stock or returned back to the vendor.

---

## Open Vendor Return

![Vendor Return](/docsimages/vr.png)

To open the Vendor Return page:

1. Open the main menu.
2. Click **Vendor Return**.
3. The system will open the **Products Return** page.

The Vendor Return page is available from the `/return-accept` URL.

---

## View Vendor Return Products

![Vendor Return Products](/docsimages/vr1.png)

The **Products Table** shows all vendor return product records.

The table includes the following details:

1. **Purchase Order Id**
2. **Brand Title**
3. **SKU Code**
4. **MRP**
5. **GST**
6. **HSN**
7. **Quantity Requirement**
8. **Available Quantity**
9. **Purchase Rate**
10. **Received Quantity**
11. **Issue**
12. **Issue Items**
13. **Issue Reason**
14. **Status**
15. **Action**

The purchase order number is clickable and opens the related purchase order details.

---

## Filter Vendor Return Status

![Vendor Return Status Tabs](/docsimages/vr2.png)

The Vendor Return page provides status tabs to filter records.

Available tabs:

1. **All** shows all vendor return records.
2. **Pending** shows records that still need action.
3. **Accepted** shows records that have been accepted into warehouse stock.
4. **Returned** shows records that have been returned to the vendor.

Click a tab to view records for that status.

---

## Return Products To Vendor

![Return Vendor Products](/docsimages/vr3.png)

To return products back to the vendor:

1. Open the **Vendor Return** page.
2. Go to the **Pending** tab.
3. Find the required product row.
4. Click the **Return** action button.
5. The system will mark the record as **Returned**.

After this action, the product will be treated as returned to the vendor.

---

## Accept Vendor Return Products

![Accept Vendor Products](/docsimages/vr4.png)

To accept vendor return products into stock:

1. Open the **Vendor Return** page.
2. Go to the **Pending** tab.
3. Find the required product row.
4. Click the **Accept** action button.
5. The system will mark the record as **Accepted**.
6. The returned quantity will be added back to warehouse stock.

After this action, the accepted quantity will be available in warehouse inventory.

---

## Important Notes

1. Only **Pending** records show the **Return** and **Accept** action buttons.
2. Once a record is accepted or returned, the action column will show `--`.
3. Accepted records update warehouse stock.
4. Returned records are marked as returned to the vendor.

---

## Summary

The Vendor Return process follows these steps:

1. Open **Vendor Return** from the menu.
2. Review the products in the table.
3. Use the status tabs to filter records.
4. Open the **Pending** tab.
5. Click **Return** to return products to the vendor.
6. Click **Accept** to add returned products back into warehouse stock.
7. Review processed records in the **Accepted** or **Returned** tabs.
