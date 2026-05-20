# Route Functionality: `update.packing.products`

## Route Definition

- Path: `POST /update-packaging-products`
- Name: `update.packing.products`
- Controller: `PackagingController@updatePackagingProducts`
- Route file: [web.php](C:/xampp/htdocs/headquater/routes/web.php:296)
- Controller method: [PackagingController.php](C:/xampp/htdocs/headquater/app/Http/Controllers/PackagingController.php:552)
- Form source: [view.blade.php](C:/xampp/htdocs/headquater/resources/views/packagingList/view.blade.php:179)

This route is inside `auth` middleware group, so only logged-in users can call it.

## Request Inputs

Required request fields:

- `pi_excel`: uploaded file (`xlsx`, `xls`, or `csv`)
- `salesOrderId`: valid existing `sales_orders.id`

Validation is done at method start. On validation failure it redirects back with errors.

## Expected File Structure

The uploaded file is parsed with `SimpleExcelReader` and must contain all required headers exactly.  
Required headers include:

- `Customer Name`, `SKU Code`, `Facility Name`, `Facility Location`
- `PO Date`, `PO Expiry Date`, `HSN`, `Item Code`, `Description`
- `GST`, `Basic Rate`, `Net Landing Rate`, `MRP`
- `PO Quantity`, `Purchase Order Quantity`
- `Vendor PI Fulfillment Quantity`, `Vendor PI Received Quantity`
- `Warehouse Name`, `Warehouse Allocation`, `Purchase Order No`
- `Total Dispatch Qty`, `Final Dispatch Qty`, `Case Pack Quantity`, `Box Count`, `Weight`

If headers are missing, transaction is rolled back and request fails with a missing-column message.

## High-Level Flow

1. Begin DB transaction.
2. Detect user mode:
   - `isAdmin = Super Admin/Admin OR user has no warehouse_id`
   - otherwise warehouse-user mode.
3. Read rows from uploaded file.
4. For each row:
   - Check mandatory fields are present and non-empty (for string fields).
   - Find `Customer` by `Facility Name`.
   - Find `SalesOrderProduct` using:
     - `customer_id`
     - `sales_order_id = salesOrderId`
     - `sku = SKU Code`
     - related `temp_orders.po_number = Purchase Order No`
   - If product found, call `updateSalesOrderProduct(...)`.
5. If no rows were successfully matched/processed, rollback and return error.
6. Otherwise commit, log activity, and redirect to packaging view page with success count.

## Row Matching Logic

A row updates only when all these match:

- same sales order (`salesOrderId`)
- customer resolved from `Facility Name`
- same SKU (`SKU Code`)
- same PO number (`Purchase Order No` against `temp_orders.po_number`)

If customer or order row does not match, that row is silently skipped.

## What `updateSalesOrderProduct(...)` Does

Method reference: [PackagingController.php](C:/xampp/htdocs/headquater/app/Http/Controllers/PackagingController.php:681)

Key behavior:

1. Reads:
   - `Final Dispatch Qty` (int)
   - `Box Count` (float)
   - `Weight` (float)
   - optional `Issue Units` and `Issue Reason`
2. If `Final Dispatch Qty == 0`, row is ignored (`return` early).
3. Updates allocations differently by user type:
   - Admin:
     - If multiple warehouse allocations exist, distributes `Final Dispatch Qty`, `Box Count`, `Weight` proportionally by each allocation’s `allocated_quantity`.
     - Sets each allocation `product_status = packaged`.
   - Warehouse user:
     - Updates only their own warehouse allocation (`warehouse_id == user warehouse`).
     - Sets allocation `final_final_dispatched_quantity`, `box_count`, `weight`, `product_status = packaged`.
4. Updates parent `sales_order_products` row:
   - `status = packaged`
   - `product_status = packaged`
   - `final_final_dispatched_quantity`, `box_count`, `weight`
     - admin: set directly from file values
     - warehouse user: aggregate sums from all allocations
5. Issue handling:
   - If `dispatched_quantity > finalDispatchQty`: marks `Shortage`.
   - If `dispatched_quantity < finalDispatchQty`: marks `Exceed`.
   - If equal: normal save.
   - For shortage, creates `warehouse_product_issues` record (only once for no-allocation/admin path).

## Success/Failure Responses

Success:

- Redirects to `packing.products.view` for same order
- Flash message: `"Packaging products updated successfully. X records processed."`
- Activity log entry with `sales_order_id` and `records_updated`

Failure:

- Validation errors: redirect back with form errors.
- Missing headers / empty mandatory fields / no valid rows: rollback + error message.
- Exception: rollback + `"Error processing file: ..."` and error logged.

## Important Edge Notes

1. Mandatory-field check treats empty strings as invalid, but numeric `0` values still pass.
2. `Issue Units` and `Issue Reason` are read in helper method but are not in required header list.
3. Rows can be present in file but not counted if customer/order/PO mapping does not match DB.
4. `insertCount` increments when a row is matched and helper is called, even if helper returns early for `Final Dispatch Qty = 0`.
