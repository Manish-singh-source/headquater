# Invoice

The **Invoice** module is used to generate and download invoices for sales orders that have already been shipped.

---

## Purpose of Invoice

The Invoice process helps the admin:

- Generate invoices automatically from sales orders.
- Create invoices only for shipped products.
- Filter products before invoice generation.
- Generate invoices based on brand or PO number if required.
- View generated sales order invoices.
- Download invoice files with invoice and client details.

---

## Generate Invoice from Sales Order

![Generate Invoice from Sales Order](/docsimages/11.png)

To generate an invoice automatically from a sales order:

1. Open the required **Sales Order**.
2. After opening the sales order, review the product table.
3. Use the filter options shown above the table.
4. Select **Fulfilled (Greater Than)**.
5. Select **Product Status** as **Shipped**.
6. If required, apply additional filters:
   - Select a specific brand to generate an invoice for that brand only.
   - Select a PO number to generate an invoice for a particular PO.
7. Click **Generate Invoice**.

After this step, the system will generate the invoice for the selected shipped products.

---

## Open Invoice

![Open Invoice](/docsimages/12.png)

To open the Invoice section:

1. Open the main menu.
2. Click **Invoice**.
3. The system will show the invoice page.

On the Invoice page, two tabs are available:

- **Sales Order Invoice**
- **Manual Invoice**

Use **Sales Order Invoice** to view invoices generated from sales orders.

---

## View Sales Order Invoice

![View Sales Order Invoice](/docsimages/13.png)

To view a sales order invoice:

1. Open the **Invoice** tab from the menu.
2. Click **Sales Order Invoice**.
3. Open the invoice list for the required sales order.
4. Find the invoice record that needs to be checked.

If one sales order has multiple PO numbers, the invoice records will be displayed in separate rows based on the PO number and generated invoice details.

---

## Download Invoice

![Download Invoice](/docsimages/14.png)

To download the invoice:

1. Click **View Invoice** for the required invoice row.
2. Review the invoice details, including:
   - **Invoice ID**
   - **Client Name**
   - **Address**
3. Click **Download**.

After clicking **Download**, the invoice file will be generated and downloaded.

![Downloaded Invoice](/docsimages/15.png)

After the invoice is generated, the invoice record can be updated from the invoice table.

To update invoice-related details:

1. Open the **Invoice** section from the menu.
2. Go to the invoice table.

![Downloaded Invoice](/docsimages/id2(3).png)
3. Find the required invoice record.
4. Click the **Action** button for that invoice.
5. Select the required update option:
   - **Update Invoice Details** to add or update invoice information.

   ![Downloaded Invoice](/docsimages/id2(2).png)
   - **Update DN Details** to add or update debit note details.

   ![Downloaded Invoice](/docsimages/id2(1).png)

   - **Update Payment Details** to add or update payment information.

   ![Downloaded Invoice](/docsimages/ergerg.png)

6. Fill in the required details in the form.
7. Save the details.

After saving, the updated information will be stored against the selected invoice.

   ![Downloaded Invoice](/docsimages/ergerg1.png)


---

## Summary

The invoice process follows these steps:

1. Open the required **Sales Order**.
2. Apply the required filters above the product table.
3. Select **Fulfilled (Greater Than)**.
4. Select **Product Status** as **Shipped**.
5. Apply brand or PO number filters if required.
6. Click **Generate Invoice**.
7. Open the **Invoice** tab from the menu.
8. Click **Sales Order Invoice**.
9. View the required invoice.
10. Download the invoice file.
