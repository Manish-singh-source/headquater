-- ============================================================================
-- Analytics Dashboard - Database Optimization Script
-- ============================================================================
-- Purpose: Add indexes to improve query performance for the analytics dashboard
-- Date: 2025-10-14
-- Version: 1.0.0
-- ============================================================================

-- IMPORTANT: Review existing indexes before running this script
-- Run: SHOW INDEX FROM table_name; for each table to check existing indexes

-- ============================================================================
-- PRODUCTS TABLE INDEXES
-- ============================================================================
-- Index on brand column for filtering
-- Check if exists first: SHOW INDEX FROM products WHERE Key_name = 'idx_products_brand';
CREATE INDEX IF NOT EXISTS idx_products_brand ON products(brand);

-- Composite index for brand and active status (if status column exists)
-- CREATE INDEX IF NOT EXISTS idx_products_brand_status ON products(brand, status);

-- ============================================================================
-- INVOICES TABLE INDEXES
-- ============================================================================
-- Index on invoice_date for date range filtering
CREATE INDEX IF NOT EXISTS idx_invoices_date ON invoices(invoice_date);

-- Composite index for date and status
-- CREATE INDEX IF NOT EXISTS idx_invoices_date_status ON invoices(invoice_date, status);

-- ============================================================================
-- INVOICE_DETAILS TABLE INDEXES
-- ============================================================================
-- Index on invoice_id for joins
CREATE INDEX IF NOT EXISTS idx_invoice_details_invoice_id ON invoice_details(invoice_id);

-- Index on product_id for joins with products
CREATE INDEX IF NOT EXISTS idx_invoice_details_product_id ON invoice_details(product_id);

-- ============================================================================
-- SALES_ORDERS TABLE INDEXES
-- ============================================================================
-- Index on status for filtering
CREATE INDEX IF NOT EXISTS idx_sales_orders_status ON sales_orders(status);

-- Index on created_at for date filtering
CREATE INDEX IF NOT EXISTS idx_sales_orders_created_at ON sales_orders(created_at);

-- Composite index for date and status
CREATE INDEX IF NOT EXISTS idx_sales_orders_date_status ON sales_orders(created_at, status);

-- ============================================================================
-- SALES_ORDER_PRODUCTS TABLE INDEXES
-- ============================================================================
-- Index on sales_order_id for joins
CREATE INDEX IF NOT EXISTS idx_sales_order_products_order_id ON sales_order_products(sales_order_id);

-- Index on product_id for joins with products
CREATE INDEX IF NOT EXISTS idx_sales_order_products_product_id ON sales_order_products(product_id);

-- ============================================================================
-- PURCHASE_ORDERS TABLE INDEXES
-- ============================================================================
-- Index on created_at for date filtering
CREATE INDEX IF NOT EXISTS idx_purchase_orders_created_at ON purchase_orders(created_at);

-- Index on status (if exists)
-- CREATE INDEX IF NOT EXISTS idx_purchase_orders_status ON purchase_orders(status);

-- ============================================================================
-- PURCHASE_ORDER_PRODUCTS TABLE INDEXES
-- ============================================================================
-- Index on purchase_order_id for joins
CREATE INDEX IF NOT EXISTS idx_purchase_order_products_order_id ON purchase_order_products(purchase_order_id);

-- Index on product_id for joins with products
CREATE INDEX IF NOT EXISTS idx_purchase_order_products_product_id ON purchase_order_products(product_id);

-- ============================================================================
-- APPOINTMENTS TABLE INDEXES
-- ============================================================================
-- Index on invoice_id for joins
CREATE INDEX IF NOT EXISTS idx_appointments_invoice_id ON appointments(invoice_id);

-- Index on pod for filtering
CREATE INDEX IF NOT EXISTS idx_appointments_pod ON appointments(pod);

-- Index on grn for filtering
CREATE INDEX IF NOT EXISTS idx_appointments_grn ON appointments(grn);

-- Index on created_at for date filtering
CREATE INDEX IF NOT EXISTS idx_appointments_created_at ON appointments(created_at);

-- ============================================================================
-- PAYMENTS TABLE INDEXES
-- ============================================================================
-- Index on invoice_id for joins
CREATE INDEX IF NOT EXISTS idx_payments_invoice_id ON payments(invoice_id);

-- Index on payment_status for filtering
CREATE INDEX IF NOT EXISTS idx_payments_status ON payments(payment_status);

-- Index on payment_date for date filtering
CREATE INDEX IF NOT EXISTS idx_payments_date ON payments(payment_date);

-- Composite index for date and status
CREATE INDEX IF NOT EXISTS idx_payments_date_status ON payments(payment_date, payment_status);

-- ============================================================================
-- WAREHOUSE_STOCKS TABLE INDEXES
-- ============================================================================
-- Index on product_id for joins
CREATE INDEX IF NOT EXISTS idx_warehouse_stocks_product_id ON warehouse_stocks(product_id);

-- Index on available_quantity for filtering
CREATE INDEX IF NOT EXISTS idx_warehouse_stocks_quantity ON warehouse_stocks(available_quantity);

-- ============================================================================
-- VERIFICATION QUERIES
-- ============================================================================
-- Run these queries to verify indexes were created successfully

-- Check products indexes
-- SHOW INDEX FROM products;

-- Check invoices indexes
-- SHOW INDEX FROM invoices;

-- Check invoice_details indexes
-- SHOW INDEX FROM invoice_details;

-- Check sales_orders indexes
-- SHOW INDEX FROM sales_orders;

-- Check sales_order_products indexes
-- SHOW INDEX FROM sales_order_products;

-- Check purchase_orders indexes
-- SHOW INDEX FROM purchase_orders;

-- Check purchase_order_products indexes
-- SHOW INDEX FROM purchase_order_products;

-- Check appointments indexes
-- SHOW INDEX FROM appointments;

-- Check payments indexes
-- SHOW INDEX FROM payments;

-- Check warehouse_stocks indexes
-- SHOW INDEX FROM warehouse_stocks;

-- ============================================================================
-- PERFORMANCE TESTING QUERIES
-- ============================================================================
-- Use EXPLAIN to test query performance before and after adding indexes

-- Test sales query performance
-- EXPLAIN SELECT 
--     products.brand,
--     SUM(invoice_details.total_price) as total_sales
-- FROM invoices
-- JOIN invoice_details ON invoices.id = invoice_details.invoice_id
-- JOIN products ON invoice_details.product_id = products.id
-- WHERE invoices.invoice_date BETWEEN '2025-01-01' AND '2025-12-31'
--     AND products.brand IN ('Brand1', 'Brand2')
-- GROUP BY products.brand;

-- Test purchase query performance
-- EXPLAIN SELECT 
--     products.brand,
--     SUM(purchase_orders.total_amount) as total_purchases
-- FROM purchase_orders
-- JOIN purchase_order_products ON purchase_orders.id = purchase_order_products.purchase_order_id
-- JOIN products ON purchase_order_products.product_id = products.id
-- WHERE purchase_orders.created_at BETWEEN '2025-01-01' AND '2025-12-31'
--     AND products.brand IN ('Brand1', 'Brand2')
-- GROUP BY products.brand;

-- Test order status query performance
-- EXPLAIN SELECT 
--     products.brand,
--     COUNT(DISTINCT sales_orders.id) as total_orders,
--     SUM(CASE WHEN sales_orders.status = 'pending' THEN 1 ELSE 0 END) as open_orders
-- FROM sales_orders
-- JOIN sales_order_products ON sales_orders.id = sales_order_products.sales_order_id
-- JOIN products ON sales_order_products.product_id = products.id
-- WHERE sales_orders.created_at BETWEEN '2025-01-01' AND '2025-12-31'
-- GROUP BY products.brand;

-- ============================================================================
-- MAINTENANCE RECOMMENDATIONS
-- ============================================================================
-- 1. Run ANALYZE TABLE periodically to update statistics
--    ANALYZE TABLE products, invoices, invoice_details, sales_orders, 
--                  sales_order_products, purchase_orders, purchase_order_products,
--                  appointments, payments, warehouse_stocks;

-- 2. Monitor slow queries using slow query log
--    SET GLOBAL slow_query_log = 'ON';
--    SET GLOBAL long_query_time = 2; -- Log queries taking more than 2 seconds

-- 3. Check index usage
--    SELECT * FROM sys.schema_unused_indexes;

-- 4. Optimize tables periodically
--    OPTIMIZE TABLE products, invoices, invoice_details, sales_orders,
--                   sales_order_products, purchase_orders, purchase_order_products,
--                   appointments, payments, warehouse_stocks;

-- ============================================================================
-- ROLLBACK SCRIPT (if needed)
-- ============================================================================
-- Uncomment and run these commands to remove indexes if needed

-- DROP INDEX IF EXISTS idx_products_brand ON products;
-- DROP INDEX IF EXISTS idx_invoices_date ON invoices;
-- DROP INDEX IF EXISTS idx_invoice_details_invoice_id ON invoice_details;
-- DROP INDEX IF EXISTS idx_invoice_details_product_id ON invoice_details;
-- DROP INDEX IF EXISTS idx_sales_orders_status ON sales_orders;
-- DROP INDEX IF EXISTS idx_sales_orders_created_at ON sales_orders;
-- DROP INDEX IF EXISTS idx_sales_orders_date_status ON sales_orders;
-- DROP INDEX IF EXISTS idx_sales_order_products_order_id ON sales_order_products;
-- DROP INDEX IF EXISTS idx_sales_order_products_product_id ON sales_order_products;
-- DROP INDEX IF EXISTS idx_purchase_orders_created_at ON purchase_orders;
-- DROP INDEX IF EXISTS idx_purchase_order_products_order_id ON purchase_order_products;
-- DROP INDEX IF EXISTS idx_purchase_order_products_product_id ON purchase_order_products;
-- DROP INDEX IF EXISTS idx_appointments_invoice_id ON appointments;
-- DROP INDEX IF EXISTS idx_appointments_pod ON appointments;
-- DROP INDEX IF EXISTS idx_appointments_grn ON appointments;
-- DROP INDEX IF EXISTS idx_appointments_created_at ON appointments;
-- DROP INDEX IF EXISTS idx_payments_invoice_id ON payments;
-- DROP INDEX IF EXISTS idx_payments_status ON payments;
-- DROP INDEX IF EXISTS idx_payments_date ON payments;
-- DROP INDEX IF EXISTS idx_payments_date_status ON payments;
-- DROP INDEX IF EXISTS idx_warehouse_stocks_product_id ON warehouse_stocks;
-- DROP INDEX IF EXISTS idx_warehouse_stocks_quantity ON warehouse_stocks;

-- ============================================================================
-- END OF SCRIPT
-- ============================================================================

