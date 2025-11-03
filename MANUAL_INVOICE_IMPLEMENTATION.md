# Manual Invoice Feature Implementation

## Overview
This document describes the implementation of the manual invoice creation feature for your Laravel application. This feature allows users to create invoices directly without requiring a sales order, similar to POS or accounting software like Slick.

## Features Implemented

### 1. Manual Invoice Creation
- Direct customer selection (existing customers)
- Multiple product selection with real-time stock validation
- Warehouse selection with stock checking
- Auto-calculation of subtotal, tax, discount, and total
- Payment tracking (mode, amount received, balance due)
- Auto-generated invoice numbers (format: INV-YYYYMM-###)
- Payment status tracking (paid/partial/unpaid)

### 2. Stock Management
- Real-time stock validation from warehouse_stocks table
- Automatic stock deduction on invoice creation
- Prevents overselling with validation

### 3. Payment Tracking
- Multiple payment modes (Cash, Bank Transfer, Cheque, UPI, Card)
- Partial payment support
- Automatic payment status calculation
- Payment records creation

## Files Created/Modified

### 1. Database Migration
**File:** `database/migrations/2025_11_03_000001_update_invoices_for_manual_creation.php`

**Changes:**
- Made `sales_order_id` nullable
- Added fields: `subtotal`, `tax_amount`, `discount_amount`, `paid_amount`, `balance_due`
- Added fields: `payment_mode`, `payment_status`, `invoice_type`, `notes`

**Run Migration:**
```bash
php artisan migrate
```

### 2. Models Updated

#### Invoice Model (`app/Models/Invoice.php`)
- Added new fillable fields for manual invoices
- Added date casting for `invoice_date`

#### Payment Model (`app/Models/Payment.php`)
- Added fillable fields
- Added invoice relationship

#### InvoiceDetails Model (`app/Models/InvoiceDetails.php`)
- Added missing fillable fields

### 3. Controller Methods

**File:** `app/Http/Controllers/InvoiceController.php`

**New Methods:**
1. `createManualInvoice()` - Display the manual invoice creation form
2. `storeManualInvoice()` - Process and save the manual invoice
3. `getProducts()` - AJAX endpoint to search products
4. `checkStock()` - AJAX endpoint to check warehouse stock

**Features:**
- Complete validation for all inputs
- Database transactions for data integrity
- Activity logging
- Stock validation and deduction
- Auto invoice number generation
- Payment record creation

### 4. Views

**File:** `resources/views/invoice/create-manual-invoice.blade.php`

**Features:**
- Bootstrap 5 responsive design
- Dynamic product row addition/removal
- Real-time calculations (JavaScript)
- Stock availability display
- Payment summary sidebar
- Form validation with error display
- CSRF protection

**Updated:** `resources/views/invoice/invoices.blade.php`
- Added "Create Manual Invoice" button

### 5. Routes

**File:** `routes/web.php`

**New Routes:**
```php
Route::get('/invoices/manual/create', 'createManualInvoice')->name('invoices.manual.create');
Route::post('/invoices/manual/store', 'storeManualInvoice')->name('invoices.manual.store');
Route::post('/invoices/get-products', 'getProducts')->name('invoices.get-products');
Route::post('/invoices/check-stock', 'checkStock')->name('invoices.check-stock');
```

## Usage Instructions

### Creating a Manual Invoice

1. **Navigate to Invoices**
   - Go to the Invoices page from the sidebar menu
   - Click "Create Manual Invoice" button (green button)

2. **Fill Invoice Details**
   - Select Customer (required)
   - Select Warehouse (required)
   - Set Invoice Date (defaults to today)
   - Enter PO Number (optional)

3. **Add Products**
   - Click "Add Product" button to add product rows
   - Select product from dropdown
   - System automatically shows available stock
   - Enter quantity (validates against stock)
   - Enter/modify unit price
   - Add discount amount (optional)
   - Add tax amount (optional)
   - Total is calculated automatically

4. **Payment Information**
   - Select payment mode (Cash, Bank Transfer, etc.)
   - Enter amount received
   - Balance due is calculated automatically
   - Payment status updates automatically

5. **Submit**
   - Click "Create Invoice" to save
   - System validates stock availability
   - Deducts stock from warehouse
   - Creates payment record if amount > 0
   - Redirects to invoice details page

## Database Schema Changes

### invoices Table (New Columns)
```sql
subtotal DECIMAL(10,2) DEFAULT 0
tax_amount DECIMAL(10,2) DEFAULT 0
discount_amount DECIMAL(10,2) DEFAULT 0
paid_amount DECIMAL(10,2) DEFAULT 0
balance_due DECIMAL(10,2) DEFAULT 0
payment_mode VARCHAR(255) NULL
payment_status ENUM('paid','partial','unpaid') DEFAULT 'unpaid'
invoice_type ENUM('sales_order','manual') DEFAULT 'sales_order'
notes TEXT NULL
sales_order_id (now nullable)
```

## Validation Rules

### Invoice Creation
- `customer_id`: required, must exist in customers table
- `warehouse_id`: required, must exist in warehouses table
- `invoice_date`: required, valid date
- `po_number`: optional, max 255 characters
- `products`: required array, minimum 1 product
- `products.*.product_id`: required, must exist
- `products.*.quantity`: required, numeric, minimum 0.01
- `products.*.unit_price`: required, numeric, minimum 0
- `products.*.discount`: optional, numeric, minimum 0
- `products.*.tax`: optional, numeric, minimum 0
- `payment_mode`: optional, max 255 characters
- `paid_amount`: optional, numeric, minimum 0

### Stock Validation
- Checks available quantity in warehouse_stocks
- Prevents invoice creation if insufficient stock
- Shows error message with available quantity

## Invoice Number Format

**Format:** `INV-YYYYMM-###`

**Examples:**
- `INV-202511-001` (First invoice of November 2025)
- `INV-202511-002` (Second invoice of November 2025)
- `INV-202512-001` (First invoice of December 2025)

**Logic:**
- Year-Month prefix ensures unique numbering per month
- 3-digit sequential number (001-999)
- Auto-increments based on last invoice of the month

## Payment Status Logic

- **Paid**: `paid_amount >= total_amount`
- **Partial**: `paid_amount > 0 AND paid_amount < total_amount`
- **Unpaid**: `paid_amount = 0`

## Activity Logging

All manual invoice creations are logged using Spatie Activity Log:
```php
activity()->performedOn($invoice)->causedBy(Auth::user())->log('Manual invoice created');
```

## Error Handling

All methods include:
- Try-catch blocks
- Database transactions (rollback on error)
- Validation with user-friendly messages
- Error logging to Laravel log files
- Redirect with error messages and input preservation

## Testing Checklist

- [ ] Run migration successfully
- [ ] Create manual invoice with single product
- [ ] Create manual invoice with multiple products
- [ ] Test stock validation (try to order more than available)
- [ ] Test payment calculations (full payment, partial, no payment)
- [ ] Verify stock deduction after invoice creation
- [ ] Test invoice number auto-generation
- [ ] Verify payment record creation
- [ ] Check activity log entries
- [ ] Test form validation (empty fields, invalid data)
- [ ] Test with different warehouses
- [ ] Verify invoice details page displays correctly

## Future Enhancements (Optional)

1. Edit manual invoices
2. Delete/void manual invoices with stock restoration
3. Add customer creation from invoice form
4. Product search with autocomplete
5. Barcode scanning for products
6. Print invoice directly from creation page
7. Email invoice to customer
8. Multiple payment records per invoice
9. Credit note support
10. Recurring invoices

## Support

For issues or questions, check:
- Laravel logs: `storage/logs/laravel.log`
- Browser console for JavaScript errors
- Database for data integrity

## Notes

- All monetary values use DECIMAL(10,2) for precision
- Stock updates are atomic (within transaction)
- Invoice numbers are unique (database constraint)
- Manual invoices are marked with `invoice_type = 'manual'`
- Compatible with existing sales order invoices

