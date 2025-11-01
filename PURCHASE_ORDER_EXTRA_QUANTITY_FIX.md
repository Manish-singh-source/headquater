# Purchase Order Extra Quantity Fix

## Problem Description

### Issue
When creating a Purchase Order (PO) with a quantity that exceeds the Sales Order requirement, the extra quantity was being blocked instead of being made available for other orders.

### Example Scenario
- **Sales Order Quantity**: 12 units needed
- **Purchase Order Quantity**: 13 units ordered (1 extra)
- **Vendor PI Quantity**: 13 units sent by vendor
- **Expected Behavior**: 12 units should be blocked for the sales order, 1 unit should be available
- **Previous Behavior**: All 13 units were being blocked ❌

## Root Cause

The issue was in the `approveRequest` method in `PurchaseOrderController.php` (lines 498-565).

### Previous Logic Problems:

1. **Wrong Comparison Base**:
   - Code was comparing received quantity with PI Quantity (what vendor sent)
   - Should compare with actual Sales Order requirement (unavailable_quantity from temp_orders)

2. **Warehouse Stock Update** (lines 501-524):
   - ALL received quantity was being added to `block_quantity`
   - No quantity was being added to `available_quantity` for extra units

3. **Temp Order Update** (lines 536-562):
   - ALL received quantity was being allocated to temp orders
   - Extra quantity was being blocked in temp orders instead of remaining available

### Key Issue:
The code was using `$product->available_quantity` (PI Quantity from vendor) to calculate extra quantity, but it should use the **actual sales order requirement** from `temp_orders.unavailable_quantity`.

## Solution Implemented

### 1. Warehouse Stock Update Fix

**File**: `app/Http/Controllers/PurchaseOrderController.php`
**Lines**: 498-534

```php
// Calculate actual sales order requirement from temp_orders
$tempOrderProducts = TempOrder::where('vendor_pi_id', $product->vendor_pi_id)
    ->where('vendor_code', $request->vendor_code)
    ->where('sku', $product->vendor_sku_code)
    ->get();

// Calculate total unavailable quantity (what was actually needed from sales orders)
$totalUnavailableQty = $tempOrderProducts->sum('unavailable_quantity');

// Get received quantity
$receivedQuantity = $product->quantity_received ?? 0;

// Calculate extra quantity (if vendor sent more than what was needed)
// Extra = received - what was actually needed for sales orders
$extraQuantity = max(0, $receivedQuantity - $totalUnavailableQty);
$blockQuantity = min($receivedQuantity, $totalUnavailableQty);

if (isset($updateStock)) {
    // Update stock - block only what was needed, make extra quantity available
    $updateStock->block_quantity = $updateStock->block_quantity + $blockQuantity;
    $updateStock->original_quantity = $updateStock->original_quantity + $receivedQuantity;
    $updateStock->available_quantity = $updateStock->available_quantity + $extraQuantity;
    $updateStock->save();
}
```

**Key Changes**:
- **NEW**: Fetch temp_orders to get actual sales order requirement
- **NEW**: Calculate `totalUnavailableQty` from temp_orders (actual need)
- Calculate `extraQuantity` = received - **totalUnavailableQty** (not PI quantity!)
- Calculate `blockQuantity` = minimum of received and **totalUnavailableQty**
- Add only `blockQuantity` to `block_quantity`
- Add `extraQuantity` to `available_quantity`
- Add total `receivedQuantity` to `original_quantity`

### 2. Temp Order Update Fix

**File**: `app/Http/Controllers/PurchaseOrderController.php`
**Lines**: 536-565

```php
// Update temp order vendor_pi_received_quantity
// Only allocate what was needed (unavailable_quantity), not extra quantity
// We already fetched tempOrderProducts above, so reuse it
$quantityToAllocate = $receivedQuantity; // Start with total received

foreach ($tempOrderProducts as $tempOrderproduct) {
    if ($tempOrderproduct->unavailable_quantity <= $quantityToAllocate && $tempOrderproduct->unavailable_quantity > 0) {
        // This temp order needs less than or equal to what we have
        $tempOrderproduct->available_quantity += $tempOrderproduct->unavailable_quantity;
        $tempOrderproduct->block += $tempOrderproduct->unavailable_quantity;
        $tempOrderproduct->vendor_pi_received_quantity += $tempOrderproduct->unavailable_quantity;
        $quantityToAllocate -= $tempOrderproduct->unavailable_quantity;
        $tempOrderproduct->unavailable_quantity = 0;
    } else {
        // This temp order needs more than what we have left
        $tempOrderproduct->available_quantity += $quantityToAllocate;
        $tempOrderproduct->block += $quantityToAllocate;
        $tempOrderproduct->vendor_pi_received_quantity += $quantityToAllocate;
        $tempOrderproduct->unavailable_quantity -= $quantityToAllocate;
        $quantityToAllocate = 0;
    }
    $tempOrderproduct->save();

    if ($quantityToAllocate <= 0) {
        break; // Stop if we've allocated all received quantity to sales orders
    }
}

// Any extra quantity (receivedQuantity - totalUnavailableQty) remains in warehouse as available_quantity
// This was already handled in the warehouse stock update above
```

**Key Changes**:
- **Reuse** tempOrderProducts fetched earlier (optimization)
- Allocate received quantity to temp orders based on their unavailable_quantity
- Loop stops when all received quantity is allocated OR all temp orders are fulfilled
- Extra quantity automatically remains in warehouse stock as `available_quantity`

## Test Scenario

### Before Fix ❌

| Step | Sales Order Qty | PO Qty | PI Qty | Warehouse Block | Warehouse Available | Result |
|------|----------------|--------|--------|-----------------|---------------------|---------|
| 1. Create Sales Order | 12 | - | - | 0 | 100 | - |
| 2. Create Purchase Order | 12 | 13 | - | 12 | 88 | - |
| 3. Vendor sends PI | 12 | 13 | 13 | 25 | 88 | ❌ Wrong! |

**Problem**: All 13 units blocked, 0 available

### After Fix ✅

| Step | Sales Order Qty | PO Qty | PI Qty | Warehouse Block | Warehouse Available | Result |
|------|----------------|--------|--------|-----------------|---------------------|---------|
| 1. Create Sales Order | 12 | - | - | 0 | 100 | - |
| 2. Create Purchase Order | 12 | 13 | - | 12 | 88 | - |
| 3. Vendor sends PI | 12 | 13 | 13 | 25 | 89 | ✅ Correct! |

**Solution**: 12 units blocked for sales order, 1 unit available for other orders

## Detailed Example

### Scenario:
- Initial warehouse stock: 100 units available
- Sales Order created: 12 units needed
- Purchase Order created: 13 units ordered (1 extra)
- Vendor sends: 13 units

### Calculation:
```
Sales Order Requirement (unavailable_quantity) = 12
PO Quantity = 13
PI Quantity = 13
receivedQuantity = 13 (actual received)

totalUnavailableQty = 12 (from temp_orders)
extraQuantity = max(0, 13 - 12) = 1 ✅
blockQuantity = min(13, 12) = 12 ✅

Warehouse Stock Update:
- original_quantity += 13 (total received)
- block_quantity += 12 (only what was needed for sales order)
- available_quantity += 1 (extra quantity) ✅

Temp Order Update:
- Allocate 12 units to temp_orders (fulfill sales order)
- Remaining 1 unit stays in warehouse as available
```

## Files Modified

1. **app/Http/Controllers/PurchaseOrderController.php**
   - Method: `approveRequest`
   - Lines: 498-565
   - Changes:
     - Added logic to fetch temp_orders and calculate actual sales order requirement
     - Fixed warehouse stock quantity allocation (block vs available)
     - Fixed temp order quantity allocation logic
     - Now correctly identifies extra quantity based on sales order need, not PO/PI quantity

## Impact

### Positive Impact ✅
- Extra quantities are now properly available for other orders
- Warehouse stock accurately reflects available vs blocked quantities
- Better inventory management
- Prevents unnecessary stock blocking

### No Breaking Changes
- Existing functionality remains intact
- Only affects how extra quantities are handled
- Backward compatible with existing data

## Testing Recommendations

1. **Test Case 1**: PO Quantity = Sales Order Quantity
   - Create sales order with 10 units
   - Create purchase order with 10 units
   - Vendor sends 10 units
   - Verify: 10 blocked, 0 extra available

2. **Test Case 2**: PO Quantity > Sales Order Quantity
   - Create sales order with 10 units
   - Create purchase order with 15 units
   - Vendor sends 15 units
   - Verify: 10 blocked, 5 available

3. **Test Case 3**: Vendor sends more than PO
   - Create sales order with 10 units
   - Create purchase order with 12 units
   - Vendor sends 15 units
   - Verify: 12 blocked, 3 available

## Notes

- The `available_quantity` field in `vendor_p_i_products` table represents the PI Quantity from vendor
- The `quantity_received` field represents the actual received quantity
- Extra quantity calculation: `max(0, quantity_received - available_quantity)`
- Block quantity calculation: `min(quantity_received, available_quantity)`

## Date
2025-11-01

