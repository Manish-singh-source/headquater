



UPDATE sales_order_products
SET final_qty_blocked_at = created_at
WHERE final_qty_blocked_at IS NULL
  AND final_dispatched_quantity IS NOT NULL
  AND final_dispatched_quantity > 0;


START TRANSACTION;

UPDATE sales_order_products
SET final_qty_blocked_at = created_at
WHERE final_qty_blocked_at IS NULL
  AND final_dispatched_quantity IS NOT NULL
  AND final_dispatched_quantity > 0;

UPDATE warehouse_allocations AS wa
INNER JOIN sales_order_products AS sop
    ON sop.id = wa.sales_order_product_id
SET wa.final_qty_blocked_at = sop.final_qty_blocked_at
WHERE sop.final_qty_blocked_at IS NOT NULL
  AND wa.final_dispatched_quantity IS NOT NULL
  AND wa.final_dispatched_quantity > 0
  AND wa.final_qty_blocked_at IS NULL;

COMMIT;





UPDATE sales_order_products AS sop
INNER JOIN (
    SELECT
        subject_id AS sales_order_id,
        MAX(created_at) AS activity_date
    FROM activity_log
    WHERE subject_type = 'App\\Models\\SalesOrder'
      AND description = 'Sales order status changed to Ready to Package'
    GROUP BY subject_id
) AS al
    ON al.sales_order_id = sop.sales_order_id
SET sop.send_to_pkg_at = al.activity_date
WHERE sop.final_dispatched_quantity IS NOT NULL
  AND sop.final_dispatched_quantity > 0
  AND sop.send_to_pkg_at IS NULL;










UPDATE warehouse_allocations AS wa
INNER JOIN sales_order_products AS sop
    ON sop.id = wa.sales_order_product_id
INNER JOIN (
    SELECT
        subject_id AS sales_order_id,
        MAX(created_at) AS activity_date
    FROM activity_log
    WHERE subject_type = 'App\\Models\\SalesOrder'
      AND description = 'Sales order status changed to Ready to Package'
    GROUP BY subject_id
) AS al
    ON al.sales_order_id = sop.sales_order_id
SET wa.send_to_pkg_at = al.activity_date
WHERE wa.final_dispatched_quantity IS NOT NULL
  AND wa.final_dispatched_quantity > 0
  AND wa.send_to_pkg_at IS NULL;











Part 2 : 


UPDATE sales_order_products
SET dispatched_quantity = final_dispatched_quantity
WHERE dispatched_quantity = 0
  AND final_dispatched_quantity IS NOT NULL
  AND final_dispatched_quantity != 0;

UPDATE warehouse_allocations
SET allocated_quantity = final_dispatched_quantity
WHERE allocated_quantity = 0
  AND final_dispatched_quantity IS NOT NULL
  AND final_dispatched_quantity != 0;


UPDATE sales_order_products
SET dispatched_quantity = final_dispatched_quantity
WHERE dispatched_quantity < final_dispatched_quantity
  AND final_dispatched_quantity IS NOT NULL;












I checked `DashboardController@index()` and its view [index.blade.php](C:/xampp/htdocs/headquater/resources/views/index.blade.php:19). Here is the frontend/business explanation of each rendered section.

**Access**
Only users with role `Super Admin` or `Super Admin 2` can see this analytics dashboard. Other users only see a welcome message saying they do not have permission.

**Filters**
The dashboard has three filters:

`Start Date` and `End Date`: These decide the reporting period. If no date is selected, the controller uses the current month by default.

`Select Brands`: This list comes from the `products` table, using all non-empty unique product brands. Selecting brands filters most brand-based dashboard data.

Important note: `Warehouse Inventory` is filtered by brand only, not by date.

**Sales Analytics**
`Total Sales`: This is the sum of `invoice_details.total_price` for all invoices where `invoice_type = sales_order` and `invoice_date` is inside the selected date range.

Important note: in the current code, this total does not apply the selected brand filter. So if the user selects one brand, the “Sales by Brand” table filters correctly, but “Total Sales” may still show sales for all brands in the selected period.

`Sales Trend`: This line chart shows month-wise sales inside the selected date range. For each month, it sums `invoice_details.total_price`, grouped by product brand.

`Sales by Brand`: This table shows each brand and its total sales. Data comes from `invoices`, `invoice_details`, and `products`. It joins invoice details to products, groups by `products.brand`, and sums `invoice_details.total_price`.

**Purchase Analytics**
`Total Purchases`: This is calculated from received vendor PI products. The formula is:

`quantity_received * purchase_rate * (1 + gst / 100)`

Only vendor PI products whose related purchase order has status `completed` are included.

Important note: the view displays this value divided by `2`:  
`$purchaseData['total_amount_overall'] / 2`

So the frontend number is half of the calculated purchase total.

`Purchase Trend`: This chart shows month-wise purchase value, grouped by brand. It uses the same purchase formula and also displays the value divided by `2`.

`Purchases by Brand`: This table shows each brand and its total purchase value. Data comes from `vendor_p_i_products`, joined with `products` by SKU. It includes completed purchase orders only, then groups by brand.

**Dispatch Status**
This section uses sales orders created inside the selected date range. If brands are selected, it filters sales orders that have ordered products under those brands.

`LR Pending`: Count of sales orders where none of the related invoices has `lr_number`, `lr_doc`, or `lr_file`.

`Appointment Received & GRN Pending`: Count of sales orders where at least one related invoice has an appointment date, but GRN is not uploaded/available on the appointment.

`Appointment Pending`: Count of sales orders where no related invoice has an appointment date.

`Dispatch Chart`: Pie chart showing the same three counts visually.

**Delivery Confirmation**
This section compares total sales orders against invoices that have POD uploaded.

`Total POD`: Actually this is total sales orders in the selected date range, after brand filter if selected. It is not counting POD documents directly.

`POD Received`: Count of invoices whose related appointment has a non-empty `pod` field.

`POD Not Received`: Calculated as:

`Total POD - POD Received`

So business-wise, it means estimated pending POD count based on total sales orders minus invoices with POD.

`Delivery Chart`: Doughnut chart showing POD Received vs POD Not Received.

**GRN Status**
This section uses sales orders created inside the selected date range, filtered by brand if selected.

`Total GRN`: Total count of sales orders in the selected date range. Despite the title, it is not total GRN documents.

`GRN Done`: Count of sales orders that have at least one invoice whose appointment has a non-empty `grn` field.

`GRN Not Done`: Calculated as:

`Total GRN - GRN Done`

`GRN Chart`: Horizontal bar chart showing GRN Done vs GRN Not Done.

**Payment Status**
This section uses sales invoices where `invoice_type = sales_order` and `invoice_date` is inside the selected date range. Brand filter is applied through invoice details and product brand.

`Total Invoice Value`: Sum of all invoice detail `total_price` values for matching sales invoices.

`Paid Value`: Sum of payments received against those invoices, but capped so paid amount cannot exceed the invoice total. For each invoice:

`min(invoice total, sum of invoice payments)`

`Unpaid Value`: Remaining balance across invoices. For each invoice:

`max(0, invoice total - sum of invoice payments)`

`Payment Trend`: Month-wise sum of `payments.amount` where the payment date is inside each month and the payment belongs to a sales-order invoice.

**Warehouse Inventory**
This section comes from `warehouse_stocks` joined with `products` by SKU.

`Total Inventory Units`: Sum of `warehouse_stocks.available_quantity` for products with a valid brand. If a brand filter is selected, it only counts stock for those brands.

`Inventory by Brand`: Shows available stock units grouped by product brand.

Important note: the controller also calculates inventory value using:

`available_quantity * products.mrp`

But the view currently comments out/hides the inventory value columns, so users only see unit counts.