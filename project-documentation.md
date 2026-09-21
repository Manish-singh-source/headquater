# Project Documentation and Developer Handover

_Last reviewed: 2026-09-21. This describes the repository, not necessarily the exact state of the production server. Verify deployment, migrations, secrets, scheduled tasks, and third-party accounts during handover._

## 1. Project Overview

Headquarters is an internal Laravel application for order-to-cash and procure-to-stock operations. Staff manage master data, import customer purchase orders, check and reserve warehouse stock, buy shortages, receive vendor goods, package and approve shipments, generate invoices and GST documents, collect payments, handle returns, and run reports. Spreadsheet uploads/downloads are central to several workflows.

This is a Laravel 12 monolith using PHP 8.2+, Eloquent, Blade, Bootstrap/jQuery/DataTables, and a mix of direct public assets and Vite. Most business operations run synchronously in controllers. The route file is the best index of the application: [`routes/web.php`](routes/web.php). Always follow route -> controller -> model/transaction -> Blade view -> export when changing behavior. Some similarly named controller methods are legacy or not routed.

| Domain | Main routes | Code owner |
| --- | --- | --- |
| Dashboard | `/` | `DashboardController`, `resources/views/layouts/master.blade.php` |
| Staff, roles, permissions | `/staff`, `/role`, `/permission` | `StaffController`, `RoleController`, `PermissionController` |
| Master data | `/customers`, `/customer-groups`, `/vendors`, `/products`, `/sku-mapping`, `/warehouses` | Matching controllers |
| Sales orders | `/order`, `/create-order`, `/view-order/{id}` | `SalesOrderController`, `WarehouseAllocationService` |
| Purchasing/receiving | `/purchase-order`, `/received-products` | `PurchaseOrderController`, `ReceivedProductsController` |
| Packaging/dispatch | `/packaging-list`, `/ready-to-ship` | `PackagingController`, `ReadyToShip` |
| Customer invoices | `/invoices`, `/e-invoices`, `/e-way-bills` | `InvoiceController` |
| Returns/tracking | `/customer-returns`, `/track-order` | `ProductReturnController`, `TrackOrderController` |
| Reports | `/customer-sales-invoices`, `/customer-sales-sku`, `/vendor-purchase-*`, `/inventory-stock-history`, `/gst-report` | `ReportController` |

## 2. Repository and Dependencies

- [`composer.json`](composer.json) requires PHP `^8.2`, Laravel `^12.0`, Simple Excel/OpenSpout, DomPDF, Spatie Permission/Activitylog/Backup/DB Snapshots, QR-code support, Google Drive filesystem, and other UI/dev packages. Use the lockfile for exact installed versions.
- [`package.json`](package.json) and [`vite.config.js`](vite.config.js) define the Vite build. Inputs are `resources/css/app.css` and `resources/js/app.js`. Many screens also load assets directly from `public/assets`, `public/sass`, and CDNs.
- `app/Http/Controllers`: HTTP and much business logic. `app/Services/WarehouseAllocationService.php`: multi-warehouse stock allocation. `app/Models`: Eloquent relationships. `resources/views`: Blade pages and per-page JavaScript.
- `database/migrations`: schema history. `database/seeders`: initial data (read before executing). `routes/web.php`: HTTP routes. [`routes/console.php`](routes/console.php): scheduled commands. `bootstrap/app.php`: route registration and `/up` health endpoint.
- `docs/` contains older focused notes for packaging, e-invoicing, Google Drive backups, and other issues. Useful context, but check every claim against current code. The existing [`README.md`](README.md) says PHP 8.1; the actual Composer requirement is 8.2+.

## 3. Local Setup

1. Install PHP 8.2+, Composer, Node/npm, and a database supported by the configured connection. Match production versions where possible.
2. Run `composer install` and `npm ci`.
3. Copy `.env.example` to `.env`. Set `APP_URL`, local database credentials, session/cache/queue drivers, mail, and any required integration credentials. Never commit `.env`.
4. Run `php artisan key:generate` **only for a new local environment**. Never regenerate a live `APP_KEY` without a recovery plan.
5. Use a disposable local database; run `php artisan migrate:status`, then `php artisan migrate`. Do not use `migrate:fresh`, `db:wipe`, or unreviewed seeders on production.
6. Run `php artisan storage:link` if public-disk files are needed. Make `storage/` and `bootstrap/cache/` writable.
7. Run `npm run dev` and `php artisan serve` locally, or `npm run build` for compiled deployment assets. `composer run dev` also starts a queue listener and log viewer.
8. Create a local user and roles safely. **Do not run `DatabaseSeeder` blindly**: it creates fixed administrator accounts/passwords and assumes specific user IDs. Read it and its called seeders first; rotate any real accounts created from it.

`.env.example` defaults to SQLite, database-backed session/cache/queue, and log mail. These are template values, not evidence of production settings. Confirm production DB engine, PHP extensions, queue worker, scheduler, and web server separately.

| Purpose | Environment keys/source |
| --- | --- |
| App | `APP_ENV`, `APP_DEBUG`, `APP_URL`, `APP_KEY` |
| Database | `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` |
| Session/cache/queue | `SESSION_DRIVER`, `CACHE_STORE`, `QUEUE_CONNECTION` |
| E-invoicing | `EINVOICE_API_URL`, `EINVOICE_API_USERNAME`, `EINVOICE_API_PASSWORD`; `config/services.php` |
| Seller GSTIN fallback | `DEFAULT_COMPANY_GSTIN` is read in `InvoiceController`; validate the correct legal entity |
| Google Drive backup | `GOOGLE_DRIVE_CLIENT_ID`, `GOOGLE_DRIVE_CLIENT_SECRET`, `GOOGLE_DRIVE_REFRESH_TOKEN`, `GOOGLE_DRIVE_FOLDER`; optional `GOOGLE_DRIVE_ACCESS_TOKEN` |
| Mail/logs/cloud | Standard Laravel `MAIL_*`, `LOG_*`, and optional `AWS_*` keys |

Transfer actual secrets through an approved secrets manager, not this file or a ticket.

## 4. Data Model Map

Use migrations for exact columns and indexes. This table explains record ownership and major relationships.

| Tables/models | Meaning |
| --- | --- |
| `users`, roles, permissions | Staff accounts; users can be tied to `warehouse_id`. Spatie Permission supplies roles/abilities. |
| `customers`, `customer_groups`, `customer_group_members` | Buyer/facility records and grouping, addresses, contact and GST details. |
| `vendors`, `products`, `product_mappings`, `sku_mappings` | Suppliers and catalog identities. Customer SKU, item code, and internal SKU may differ. |
| `warehouses`, `warehouse_stocks`, `warehouse_stock_logs` | Locations and SKU stock. Available, blocked, and received quantities are distinct. |
| `sales_orders`, `sales_order_products`, `temp_orders` | Order header, lines, and imported PO fields such as dates, number, and block quantity. |
| `warehouse_allocations` | Per-order-line, per-warehouse reservation and packaging/approval/shipping progress. One line may have multiple allocations. |
| `purchase_orders`, `purchase_order_products`, `vendor_p_i_s`, `vendor_p_i_products` | Procurement and vendor PI/fulfillment linked to demand. |
| `purchase_invoices`, `purchase_grns`, `vendor_payments` | Supplier invoice, receipt, and payment records. |
| `invoices`, `invoice_details`, `payments` | Customer billing, line quantities/tax/prices, and collections. An order can have multiple invoices. |
| `appointments`, `dns`, `e_invoices`, `ewaybills`, `eway_transport_details` | Delivery/GRN/POD/DN metadata and statutory documents. |
| `customer_returns`, `vendor_return_products`, `warehouse_product_issues`, `product_issues` | Returns and quantity exceptions. |
| `packaging_upload_batches`, `packaging_upload_changes` | Audit of spreadsheet packaging submissions/corrections. |
| `notifications`, `activity_log` | In-app notifications and activity history. |

Key model navigation: `SalesOrder->orderedProducts/invoices`, `SalesOrderProduct->tempOrder/warehouseAllocations`, `Invoice->details/payments/appointment/dns/customer/warehouse`. See [`app/Models`](app/Models). Never assume one order = one invoice or one order line = one warehouse allocation.

## 5. End-to-End Business Flow

### 5.1 Master data

Set up/verify customer group, customer/facility name, vendor, SKU/product mapping, and warehouse stock before importing orders. Spreadsheet identity matching can depend on exact names and codes. Investigate corresponding master-data controllers when an import reports an unknown customer, SKU, or vendor.

### 5.2 Sales order and stock reservation

1. `POST /check-products-stock` parses the uploaded order using Simple Excel, validates required columns and duplicate business identities, looks up SKU/customer data, and produces a processed CSV under `public/uploads`. `GET /download-block-order-csv` downloads its processed result.
2. `POST /store-order` creates order/line/PO records from the processed sheet. Quantity fields include PO quantity, requested block, available quantity, and purchase shortage. Do not conflate them.
3. A fixed warehouse or `auto` allocation may be selected. `WarehouseAllocationService::autoAllocateStock` checks active warehouses with positive SKU stock, creates allocations, moves reserved units from available to blocked stock, and tracks any unmet quantity as pending/purchase demand.
4. `GET /view-order/{id}` is the order detail. `POST /products-download-po-excel` exports an update workbook; `PUT /update-order` imports edited block/fulfillment values. It validates headers and quantities, may create new lines, and adjusts stock/allocations. Test repeated-SKU and multi-warehouse cases before changing it.
5. The update workbook has optional `Warehouse Preference`. For an auto-allocation order, a named preference resolves an active warehouse with that SKU, releases existing allocations, then reallocates the requested block **only** there. Insufficient stock rolls the transaction back. Without a preference, increasing block quantity requests only the extra quantity across active warehouses. See `SalesOrderController::update` and `findPreferredWarehouseStock`.
6. Separate `/release-blocked-quantity/{id}`, `/manual-allocate`, and `/auto-allocate-stock/{id}` routes support release/allocation. Stock counters, allocation rows, and logs must stay consistent.

An imported line's identity can include order, facility/customer, SKU, item code, and PO number. `findSalesOrderProductForImport` handles existing lines; spreadsheet position is not a durable identifier.

### 5.3 Purchasing and receiving

`PurchaseOrderController` handles purchase orders, custom orders, vendor PI approval/rejection, purchase invoices, GRNs, and vendor payment recording. `ReceivedProductsController` handles received quantities/status and spreadsheet downloads. `WarehouseAllocationService::createPurchaseOrderForShortage` is another shortage helper; confirm whether the current UI route invokes it before editing it.

### 5.4 Packaging, approval, and dispatch

1. `POST /send-to-packaging` changes order status to `ready_to_package` and eligible lines/allocations to packaging state.
2. `GET /download-packing-products-excel` exports a warehouse-aware sheet. `POST /update-packaging-products` imports `pi_excel` for `salesOrderId`. It validates exact headers and quantities, records `packaging_upload_batches` and `packaging_upload_changes`, and supports `warehouse_submit` or admin-only `admin_correction`. Orders already `ready_to_ship` or `shipped` reject updates.
3. `PUT /change-packaging-status-to-ready-to-ship` moves warehouse submissions to approval pending or allows admin approval. Product status and approval status are separate fields.
4. `ReadyToShip` owns shipping views/actions, warehouse invoice generation, and issue/return acceptance. `SalesOrderController::generateInvoice` is another invoice-generation path. Check the actual UI action before modifying billing logic.

Order header, line, and allocation status values are not one centralized state machine. Follow each transition in the owning controller, particularly for partial or multi-warehouse orders. The older `docs/packaging.md` may not reflect the current upload implementation.

### 5.5 Invoices and collections

`InvoiceController` handles listing, PDF/view, manual invoices, edit/delete, payments, appointment/GRN/POD, debit notes, and bulk spreadsheet updates. `invoices.invoice_type` distinguishes sales-order invoices from other types; reports often filter `sales_order`. Invoice detail rows supply quantities, prices, and tax. Payment state may exist both on invoices and in `payments`; inspect synchronization before changing calculations.

E-invoice/E-Way Bill generation and cancellation call Masters India through `InvoiceController` and `config/services.php`. Warehouse/customer GSTIN, address, state, and provider account mapping matter. Use sandbox credentials and disposable invoices for development. After a timeout, inspect provider and local document state before retrying to avoid duplicate external actions.

### 5.6 Returns and tracking

`ProductReturnController` handles customer returns. Vendor return/accept paths are in `PurchaseOrderController` and `ReadyToShip`. `TrackOrderController` provides order search and detail. A return can affect stock and financial records; trace both effects before changing it.

## 6. Reports, Filters, and Spreadsheet Contracts

`ReportController` owns vendor purchase reports, inventory stock history, customer sales reports, and GST reports. Excel/PDF downloads use separate routes. Preserve filter names, date interpretation, row granularity, and export parity.

- `GET /customer-sales-invoices` renders filters and aggregate cards; `POST /customer-sales-invoices` serves DataTables pages. The request carries `draw`, `start`, `length`, `order`, `search`, and filters. The controller counts/filter/sorts invoices, fetches only the requested IDs with relations, and renders `resources/views/partials/customer-sales-invoice-rows.blade.php`. Totals use database aggregates. Excel/PDF export routes remain separate.
- `GET /customer-sales-sku` plus `POST /customer-sales-sku` paginate SKU/allocation rows on the server. A single line with multiple allocations may produce several rows. Both reports POST DataTables data because many GET filter parameters can exceed URL limits (HTTP 414).
- `GET /gst-report` groups invoice/detail data by tax rate for report/Excel output. Check GST and place-of-supply logic before relying on it for statutory filings.
- Imports require exact headers. Use application-exported templates; `/excel-file-formats` lists formats, while controller validation is authoritative. The order update sheet requires, among others, `Order No`, `SKU Code`, `PO Number`, `PO Quantity`, `Block Quantity`, `Final Fulfilled Quantity`, `Warehouse Allocation`, and `Invoice Status`.

For a DataTables Ajax warning, inspect browser Network status/body, Laravel log, CSRF token, POST route, and filter payload. HTTP 414 means URL too long; HTTP 419 usually means expired session/CSRF; HTTP 500 needs server logs. Diagnose slow reports with SQL plans/indexes rather than hydrating all rows.

## 7. Access Control and Security

- Most business routes are inside `auth` middleware in `routes/web.php`. Login, registration, password reset, location lookup, and profile routes are outside it. Confirm whether public registration/profile endpoints are intended in production.
- Spatie roles/permissions control sidebar `@can` items. `AppServiceProvider` grants `Super Admin` and `Super Admin 2` Gate bypass. **Hiding a menu item is not route authorization**; the broad route group checks login, while per-action checks vary. Audit sensitive endpoints when adding features.
- Warehouse users have `warehouse_id`; some controllers explicitly scope their data, but there is no universal global scope. Check each route's warehouse isolation.
- `/test-notifications` and `/test-notification-delete` are outside the `auth` route group. Review, protect, or remove before exposing production.
- `DatabaseSeeder` includes fixed accounts/passwords. Rotate accounts created from it, disable unused accounts, and never publish their passwords.
- `InvoiceController` has fallback/hard-coded GSTIN values in some API paths. Verify the legal entity/provider account before issuing or cancelling real documents.
- `public/uploads` and subdirectories contain potentially sensitive customer, pricing, POD/GRN, and DN files. They are web-accessible; define access controls and retention. Keep `APP_DEBUG=false` in production and protect logs, dumps, PDFs, and backups.

## 8. Operations, Backup, and Deployment

`routes/console.php` schedules `backup:run --only-db` daily at **04:00 application timezone**. `config/backup.php` targets the `google` disk, defined in `config/filesystems.php`. This does not prove the production scheduler runs or that backups succeed. Verify the server's scheduler, Drive OAuth ownership, archive arrival/retention, and a tested restore. This backup source is database-only; back up uploads/private files separately. `docs/google-drive-backup.md` has further notes, but check it against current config.

`.env.example` sets a database queue and Composer's dev script starts `queue:listen`; production worker supervision must be checked separately. Inspect `jobs`/`failed_jobs`, scheduler logs, and service configuration.

### Safe release checklist

1. Back up database and required files, confirm a restore point and rollback procedure.
2. Compare deployed commit, PHP/DB versions, `.env`, and `php artisan migrate:status`.
3. Deploy locked dependencies and built assets together with code. Review every pending migration before `php artisan migrate --force`; never run `migrate:fresh` or unreviewed seeders on production.
4. The `2026_09_21_000000_index_invoice_details_sales_order_product.php` migration adds an index on `invoice_details.sales_order_product_id`. Earlier handover discussion found it pending on one production server; **recheck current status**. It is additive, but index creation can lock a large table and needs a backup/maintenance window.
5. Clear/rebuild Laravel caches as appropriate. Deploy both GET and POST report routes with their Blade/Ajax changes.
6. Verify login, order detail, invoice, report pagination, a non-mutating export, backups, logs, and API connectivity. Do not create a real GST document as a smoke test.
7. Monitor `storage/logs`, web/PHP logs, failed jobs, and latency after release.

## 9. Troubleshooting Map

| Symptom | First code/data to inspect |
| --- | --- |
| Missing SKU/customer/vendor on import | `SalesOrderController::checkProductsStock/store`; master data and processed CSV |
| Wrong blocked/available stock | `WarehouseAllocationService`, `SalesOrderController::update`, stock/allocation/log rows |
| Preferred warehouse update fails | Active warehouse name, SKU stock row, available quantity, `findPreferredWarehouseStock`; transaction rolls back on shortage |
| Packaging import rejects row | `PackagingController::updatePackagingProducts`; exact headers, line identity, quantity constraints, upload mode, batch/change records |
| Invoice or shipment missing | `PackagingController::changeStatusToReadyToShip`, `ReadyToShip`, `SalesOrderController::generateInvoice`, allocation statuses |
| E-invoice or E-Way Bill fails | `InvoiceController`, `config/services.php`, provider response, GSTIN mapping, local statutory records |
| Slow or failed report | `ReportController`, Blade DataTables Ajax, Network response, SQL plan/indexes, logs |
| Sidebar count differs from listing | `AppServiceProvider` layout view composer has broad counters; list filters can differ |
| Route available despite hidden menu | `routes/web.php`, controller authorization, Spatie permissions, Gate bypass |

Change process: trace the full route-to-database flow; write a focused test; exercise fixed and auto warehouse modes, single/multiple allocations, repeated SKUs, partial shipments, rollback, export parity, and audit logging. Current test suite has only example unit/feature tests. The example feature test expects `GET /` to return 200, while the route is authenticated, so it may get a login redirect.

## 10. Information the Next Developer Still Needs

The repository does not reveal production server ownership, live credentials, database size, scheduler/worker supervision, backup success, or provider account ownership. Transfer the following through approved internal channels:

- Production/staging URLs, hosting access, deployment method, branch/commit, release approver, and rollback contact.
- Database engine/version and access, current migration status, backup/restore procedure, upload retention, and known data anomalies.
- Google Drive, Masters India, mail/DNS/SSL, and any cloud account owners and renewal process. Secrets belong in a secrets manager.
- User/role owner, warehouse assignments, business contacts for order/finance/warehouse incidents, open incidents, and manual reconciliation steps.
- A walkthrough of one order from customer PO upload through allocation, procurement/receiving if needed, packaging approval, shipment, invoice, payment, and reporting.

Start with `routes/web.php`, then the owning controller, model, migration, and Blade view. Use this guide as a map; use the code and a safe staging environment as the final authority.
