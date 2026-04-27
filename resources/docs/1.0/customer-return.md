# Customer Return

The **Customer Return** page is used to create and manage products returned by customers. Returned products are uploaded through an Excel file and added back to warehouse stock.

---

## Open Customer Return

![Customer Return](/docsimages/cr1.png)

To open the Customer Return page:

1. Open the main menu.
2. Click **Customer Return**.
3. The system will show the **Customer Product Returns List** page.

The Customer Return page is available from the `/customer-returns` URL.

---

## View Customer Returns

![Customer Returns List](/docsimages/cr2.png)

The **Products Table** shows all customer return records.

The table includes the following details:

1. **Sales Order Id**
2. **Brand Title**
3. **SKU Code**
4. **MRP**
5. **GST**
6. **HSN**
7. **Return Quantity**
8. **Return Reason**
9. **Return Description**
10. **Return Status**
11. **Action**

The sales order number is clickable and opens the related sales order details.

---

## Create Customer Return

![Create Customer Return](/docsimages/cr3.png)

To create a customer return:

1. Open the **Customer Return** page.
2. Click **New Order**.
3. Select the required **Sales Order**.
4. Select the **Warehouse Name**.
5. Upload the **Products List** file in CSV, XLS, or XLSX format.
6. Click **Submit**.

After submitting the form, the system will read the uploaded file and create customer return records.

---

## Excel File Requirements

The uploaded Excel file must contain valid return product data.

Required data:

1. **SKU Code**
2. **Return Quantity**

Important rules:

1. The SKU code must exist in the product list.
2. The return quantity must be greater than zero.
3. Duplicate SKU codes are not allowed in the same file.
4. Warehouse stock must exist for the selected SKU and warehouse.

When the return is created successfully, the returned quantity is added back to the selected warehouse stock.

---

## View Customer Return Details

![Customer Return Details](/docsimages/customer-return-details.png)

To view customer return details:

1. Open the **Customer Return** list.
2. Find the required return record.
3. Click the **View** action button.
4. The system will show the customer return details page.

The details page shows:

1. Sales order number.
2. Brand title.
3. SKU code.
4. MRP, GST, and HSN.
5. Return quantity.
6. Return reason.
7. Return description.
8. Return status.

---

## Update Customer Return Status

![Update Customer Return Status](/docsimages/customer-return-status.png)

To update the return status:

1. Open the customer return details page.
2. Use the **Change Status** dropdown.
3. Select the required status.
4. The system will save the selected status automatically.

Available statuses:

1. **Pending**
2. **Completed**

Once the return status is completed, it cannot be changed again from the dropdown.

---

## Delete Customer Return

To delete a customer return:

1. Open the **Customer Return** list.
2. Find the required return record.
3. Click the **Delete** action button.
4. Confirm the delete action.

When a customer return is deleted, the system reverses the stock adjustment for that return quantity.

---

## Summary

The Customer Return process follows these steps:

1. Open **Customer Return** from the menu.
2. Click **New Order**.
3. Select the sales order.
4. Select the warehouse.
5. Upload the return products Excel file.
6. Submit the form.
7. Review the created return records in the list.
8. Open a return record to view details.
9. Update the return status if required.
10. Delete the return record only if it was created incorrectly.
