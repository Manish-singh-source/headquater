# Invoices Page Update - Tabbed Interface

## Overview
Updated the `/invoices` route to display all invoices (both Manual and Sales Order) with a tabbed interface.

## Changes Made

### 1. Controller Update
**File:** `app/Http/Controllers/InvoiceController.php`

**Method:** `index()`

**Changes:**
- Changed from fetching `SalesOrder` to fetching all `Invoice` records
- Separated invoices into two collections:
  - `$manualInvoices` - Invoices with `invoice_type = 'manual'`
  - `$salesOrderInvoices` - Invoices with `invoice_type = 'sales_order'`
- Eager loaded relationships: warehouse, customer, salesOrder, appointment, dns, payments

**Code:**
```php
public function index()
{
    // Fetch all invoices with relationships
    $invoices = Invoice::with(['warehouse', 'customer', 'salesOrder', 'appointment', 'dns', 'payments'])
        ->orderBy('created_at', 'desc')
        ->get();

    // Separate manual and sales order invoices
    $manualInvoices = $invoices->where('invoice_type', 'manual');
    $salesOrderInvoices = $invoices->where('invoice_type', 'sales_order');

    return view('invoice.index', compact('invoices', 'manualInvoices', 'salesOrderInvoices'));
}
```

### 2. View Update
**File:** `resources/views/invoice/index.blade.php`

**Changes:**
- Added "Create Manual Invoice" button in breadcrumb area
- Implemented Bootstrap 5 tabs with two sections:
  - **Manual Invoices Tab** (Active by default)
  - **Sales Order Invoices Tab**
- Both tabs show the same table structure with columns:
  - Invoice No
  - PO No
  - Customer Name
  - Due Date
  - Amount
  - Paid Amount
  - Due Amount
  - Action
- Removed "Order Id" column from manual invoices (not applicable)
- Added action buttons for both invoice types:
  - View (Eye icon)
  - Appointment (Calendar icon)
  - Update DN (Document icon)
  - Update Payment (Card icon)
- Added success/error message alerts
- Tab counters show number of invoices in each category

### 3. Modals Partial
**File:** `resources/views/invoice/partials/modals.blade.php` (NEW)

**Purpose:** Reusable modal components for both invoice types

**Modals Included:**
1. **Appointment Modal** - Update appointment date, upload POD and GRN
2. **DN Modal** - Update DN amount, reason, and receipt
3. **Payment Modal** - Update UTR number, payment amount, method, and status

**Benefits:**
- DRY principle - No code duplication
- Easy maintenance - Update modals in one place
- Consistent UI across both invoice types

## Features

### Tab Interface
- **Manual Invoices Tab:**
  - Shows all invoices created through manual invoice form
  - Displays invoice count in tab header
  - Active by default
  
- **Sales Order Invoices Tab:**
  - Shows all invoices generated from sales orders
  - Displays invoice count in tab header

### Action Buttons
All invoices (both types) have the same action buttons:

1. **View Button (Eye Icon):**
   - Links to invoice details page
   - Route: `invoices-details/{id}`

2. **Appointment Button (Calendar Icon):**
   - Shows only if appointment data is incomplete
   - Opens modal to update appointment date, POD, GRN
   - Route: `invoices.appointment.update`

3. **Update DN Button (Document Icon):**
   - Shows only if DN is not created
   - Opens modal to add DN amount, reason, receipt
   - Route: `invoice.dn.update`

4. **Update Payment Button (Card Icon):**
   - Always visible
   - Opens modal to add/update payment details
   - Route: `invoice.payment.update`

### Data Display

**Manual Invoices:**
- Uses `paid_amount` and `balance_due` fields from invoice table
- Falls back to calculated values from payments relationship if fields are null

**Sales Order Invoices:**
- Calculates paid amount from payments relationship
- Calculates due amount as: `total_amount - payments.sum('amount')`

## UI/UX Improvements

1. **Better Organization:**
   - Clear separation between manual and sales order invoices
   - Easy switching between invoice types

2. **Visual Indicators:**
   - Tab counters show number of invoices
   - Active tab highlighted
   - Icons for better visual recognition

3. **Consistent Actions:**
   - Same action buttons for both invoice types
   - Conditional display based on data availability

4. **Responsive Design:**
   - Bootstrap 5 responsive tables
   - Mobile-friendly tabs
   - Proper spacing and alignment

5. **Empty States:**
   - Friendly messages when no invoices found
   - Centered text with muted color

## Routes Used

- `GET /invoices` - Main invoices listing page
- `GET /invoices/manual/create` - Create manual invoice
- `GET /create-invoice` - Create sales order invoice
- `GET /invoices-details/{id}` - View invoice details
- `POST /invoice-appointment-update/{id}` - Update appointment
- `POST /invoice-dn-update/{id}` - Update DN
- `POST /invoice-payment-update/{id}` - Update payment

## Database Fields Used

### Manual Invoices:
- `invoice_type` = 'manual'
- `paid_amount` - Direct field
- `balance_due` - Direct field
- `sales_order_id` - NULL

### Sales Order Invoices:
- `invoice_type` = 'sales_order'
- `sales_order_id` - NOT NULL
- Paid amount calculated from `payments` relationship
- Balance calculated as difference

## Testing Checklist

- [ ] Navigate to `/invoices` route
- [ ] Verify "Create Manual Invoice" button is visible
- [ ] Click Manual Invoices tab - should show manual invoices
- [ ] Click Sales Order Invoices tab - should show sales order invoices
- [ ] Verify tab counters are correct
- [ ] Test View button - should open invoice details
- [ ] Test Appointment modal - should open and submit
- [ ] Test DN modal - should open and submit
- [ ] Test Payment modal - should open and submit
- [ ] Verify empty states when no invoices exist
- [ ] Test responsive design on mobile
- [ ] Verify success/error messages display correctly

## Migration Required

Before testing, run the migration to add new fields to invoices table:

```bash
php artisan migrate
```

This will add:
- `invoice_type` column
- `paid_amount` column
- `balance_due` column
- `subtotal` column
- `tax_amount` column
- `discount_amount` column
- `payment_mode` column
- `payment_status` column
- `notes` column
- Make `sales_order_id` nullable

## Notes

1. **Backward Compatibility:**
   - Existing sales order invoices will work without changes
   - Default `invoice_type` is 'sales_order'

2. **Data Integrity:**
   - Manual invoices store payment data in invoice table
   - Sales order invoices calculate from payments relationship
   - Both methods supported for flexibility

3. **Future Enhancements:**
   - Add filters (by date, customer, status)
   - Add search functionality
   - Add bulk actions (delete, export)
   - Add pagination for large datasets
   - Add sorting by columns

## Support

For issues:
- Check Laravel logs: `storage/logs/laravel.log`
- Verify migration ran successfully
- Check browser console for JavaScript errors
- Ensure Bootstrap 5 is loaded correctly

