# Warehouse Selection for Purchase Order Vendor PI - Implementation Summary

## Overview
Added warehouse selection functionality when uploading Vendor PI from Purchase Order view page. Now users can select which warehouse the received products should be assigned to. Warehouse-specific users will only see received products assigned to their warehouse.

## Problem Statement
Previously, when uploading Vendor PI from Purchase Order view:
- No option to select warehouse when uploading Vendor PI
- All users could see all received products regardless of their warehouse assignment
- No warehouse-based filtering in Received Products section

## Solution Implemented

### 1. Controller Changes

#### PurchaseOrderController.php

**Method: `view()`** (Lines 443-470)
- Added warehouse fetching logic
- Fetches only active warehouses (`status = '1'`)
- Passes `$warehouses` to the view
```php
$warehouses = Warehouse::where('status', '1')
    ->orderBy('name')
    ->get();
```

**Method: `store()`** (Lines 320-353)
- Added `warehouse_id` validation (required, must exist in warehouses table)
- Stores selected `warehouse_id` in VendorPI record
- Added `->withInput()` to validation error redirect for better UX
```php
'warehouse_id' => 'required|integer|exists:warehouses,id',
```
```php
$vendorPi = VendorPI::create([
    'purchase_order_id' => $request->purchase_order_id,
    'vendor_code' => $request->vendor_code,
    'sales_order_id' => $request->sales_order_id,
    'warehouse_id' => $request->warehouse_id,
]);
```

**Method: `storeCustomPurchaseOrder()`** (Lines 224-254)
- Added `warehouse_id` validation (required, must exist in warehouses table)
- Stores selected `warehouse_id` in VendorPI record
- Added `->withInput()` to validation error redirect for better UX
```php
'warehouse_id' => 'required|integer|exists:warehouses,id',
```
```php
$vendorPi = VendorPI::create([
    'purchase_order_id' => $request->purchase_order_id,
    'vendor_code' => $request->vendor_code,
    'warehouse_id' => $request->warehouse_id,
]);
```

#### ReceivedProductsController.php

**Method: `index()`** (Lines 22-52)
- Added warehouse-based filtering for received products
- Warehouse users only see VendorPIs assigned to their warehouse
- Admin users see all VendorPIs
```php
$user = Auth::user();
$userWarehouseId = $user->warehouse_id;

$query = PurchaseOrder::with(['purchaseOrderProducts', 'vendorPI'])
    ->where('status', 'pending')
    ->withCount('purchaseOrderProducts')
    ->whereHas('vendorPI', function ($query) use ($userWarehouseId) {
        $query->where('status', 'pending');
        
        // Filter by warehouse if user is assigned to a specific warehouse
        if ($userWarehouseId) {
            $query->where('warehouse_id', $userWarehouseId);
        }
    });
```

### 2. Model Changes

#### VendorPI.php
**Added warehouse relationship** (Lines 45-48)
```php
public function warehouse()
{
    return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
}
```

### 3. View Changes

#### resources/views/purchaseOrder/view.blade.php

**Modal #approveBackdrop1** (Lines 449-480)
- Added warehouse selection dropdown before file upload field
- Dropdown shows only active warehouses
- Required field with validation
- Bootstrap 5 styling with error feedback
- Maintains old value on validation error
```blade
<div class="col-12 mb-3">
    <label for="warehouse_id" class="form-label">Select Warehouse <span class="text-danger">*</span></label>
    <select name="warehouse_id" id="warehouse_id" class="form-select @error('warehouse_id') is-invalid @enderror" required>
        <option value="">-- Select Warehouse --</option>
        @foreach($warehouses as $warehouse)
            <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                {{ $warehouse->name }}
            </option>
        @endforeach
    </select>
    @error('warehouse_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
```

**Modal #customPO** (Lines 507-534)
- Added warehouse selection dropdown before file upload field
- Same implementation as approveBackdrop1 modal
- Uses unique ID `warehouse_id_custom` to avoid conflicts

## Files Modified

1. `app/Http/Controllers/PurchaseOrderController.php`
   - Updated `view()` method to pass warehouses
   - Updated `store()` method to validate and save warehouse_id
   - Updated `storeCustomPurchaseOrder()` method to validate and save warehouse_id

2. `app/Http/Controllers/ReceivedProductsController.php`
   - Updated `index()` method to filter by warehouse for warehouse users

3. `app/Models/VendorPI.php`
   - Added `warehouse()` relationship

4. `resources/views/purchaseOrder/view.blade.php`
   - Added warehouse dropdown in `#approveBackdrop1` modal
   - Added warehouse dropdown in `#customPO` modal

## Database Schema
**Note:** The `warehouse_id` column already exists in `vendor_p_i_s` table from previous implementation.
- Migration: `database/migrations/2025_11_05_000001_add_warehouse_id_to_vendor_p_i_s_table.php`
- Column: `warehouse_id` (nullable, foreign key to warehouses table)

## User Flow

### 1. Upload Vendor PI from Purchase Order View
1. User navigates to Purchase Order view page
2. Clicks "Add Vendor PI" button
3. Modal opens with:
   - **Warehouse Selection Dropdown** (NEW) - Select target warehouse
   - **Excel File Upload** - Upload vendor PI file
4. User selects warehouse from dropdown
5. User uploads Excel file
6. System validates both warehouse_id and file
7. System stores warehouse_id in VendorPI record

### 2. View Received Products (Warehouse-Filtered)
1. Warehouse user logs in
2. Navigates to Received Products page
3. System automatically filters to show only VendorPIs assigned to their warehouse
4. Admin users see all VendorPIs regardless of warehouse

### 3. Stock Update Flow
1. When products are approved in Received Products:
   - System retrieves warehouse_id from VendorPI record
   - Updates stock in the selected warehouse (already implemented in previous update)
   - Creates new stock entry in selected warehouse if needed

## Benefits

1. **Warehouse-Specific Assignment**: Products are assigned to specific warehouses at the time of PI upload
2. **Role-Based Access**: Warehouse users only see products assigned to their warehouse
3. **Better Organization**: Clear separation of inventory across warehouses
4. **Audit Trail**: Warehouse assignment is tracked in database
5. **Flexibility**: Admin users can still see all received products

## Testing Checklist

- [ ] Upload Vendor PI with warehouse selection (Sales Order linked)
- [ ] Upload Vendor PI with warehouse selection (Custom PO)
- [ ] Verify warehouse_id is saved in vendor_p_i_s table
- [ ] Login as warehouse user and verify filtered received products
- [ ] Login as admin and verify all received products are visible
- [ ] Verify validation errors show properly
- [ ] Verify old() values persist on validation error
- [ ] Verify stock updates in correct warehouse on approval

## Related Files

- Previous Implementation: `WAREHOUSE_SELECTION_FOR_RECEIVED_PRODUCTS.md`
- Stock Update Logic: `app/Http/Controllers/PurchaseOrderController.php` (approveRequest, vendorProductAccept methods)
- Warehouse Model: `app/Models/Warehouse.php`
- User Model: `app/Models/User.php` (warehouse_id relationship)

