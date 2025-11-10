# Invoice Grouping Changes - Summary

## Requirement
Sales Order se invoice generate karte waqt **warehouse_id**, **po_number**, aur **facility_name** ke basis par invoices ko group karna hai. Agar ye teeno fields same hain, to ek hi invoice bane.

## Changes Made

### File: `app/Http/Controllers/SalesOrderController.php`

#### 1. Eager Loading Update (Line 1319-1321)
**Pehle:**
```php
$salesOrderDetails = SalesOrderProduct::with(['tempOrder', 'customer', 'product'])
    ->where('sales_order_id', $salesOrder->id);
```

**Ab:**
```php
$salesOrderDetails = SalesOrderProduct::with(['tempOrder', 'customer', 'product', 'warehouseAllocations.warehouse'])
    ->where('sales_order_id', $salesOrder->id);
```

**Reason:** Warehouse allocation data ko load karne ke liye taaki hum warehouse_id se group kar sakein.

---

#### 2. Grouping Logic Update (Line 1348-1391)
**Pehle:**
- Grouping Key: `facility_name|po_number`
- Optional: `brand`, `client_name`

**Ab:**
- Grouping Key: `warehouse_id|po_number|facility_name`
- Optional: `brand`, `client_name`

**Key Changes:**
1. **Warehouse-based grouping:** Har warehouse allocation ke liye alag group banaya
2. **Multi-warehouse support:** Agar ek product multiple warehouses se allocate hai, to har warehouse ke liye alag invoice banega
3. **Data structure change:** Ab `$invoicesGroup` mein array of arrays store ho raha hai:
   ```php
   [
       'detail' => $detail,        // SalesOrderProduct object
       'allocation' => $allocation  // WarehouseAllocation object
   ]
   ```

**Code:**
```php
// Group by each warehouse allocation
foreach ($allocations as $allocation) {
    $warehouseId = $allocation->warehouse_id;

    // Build dynamic grouping key: warehouse_id + po_number + facility_name
    $groupKey = $warehouseId . '|' . $poNumber . '|' . $facilityName;

    if ($request->filled('brand')) {
        $brand = $detail->product->brand ?? '';
        $groupKey .= '|' . $brand;
    }

    if ($request->filled('client_name')) {
        $clientName = $detail->customer->client_name ?? '';
        $groupKey .= '|' . $clientName;
    }

    // Store detail with allocation info
    $invoicesGroup[$groupKey][] = [
        'detail' => $detail,
        'allocation' => $allocation,
    ];
}
```

---

#### 3. Invoice Creation Logic Update (Line 1404-1496)

**Major Changes:**

##### a) Warehouse ID extraction from groupKey
```php
// Extract warehouse_id from groupKey (first part before |)
$groupParts = explode('|', $groupKey);
$warehouseId = (int) $groupParts[0];
```

##### b) Customer and PO Number extraction
```php
// Get customer_id and po_number from first item
$firstItem = $invoiceData[0];
$customerId = $firstItem['detail']->customer_id;
$poNumber = $firstItem['detail']->tempOrder->po_number ?? '';
```

##### c) Invoice warehouse_id assignment
**Pehle:**
```php
$invoice->warehouse_id = $salesOrder->warehouse_id ?? 0;
```

**Ab:**
```php
$invoice->warehouse_id = $warehouseId; // Specific warehouse from grouping
```

##### d) Allocated quantity usage
**Pehle:**
```php
$quantity = (int) $detail->ordered_quantity;
```

**Ab:**
```php
$quantity = (int) $allocation->allocated_quantity;
```

**Reason:** Multi-warehouse allocation mein ek product ka quantity multiple warehouses mein split ho sakta hai.

##### e) Invoice Details warehouse_id
```php
$invoiceDetail->warehouse_id = $warehouseId;
```

##### f) Status Update Logic
**Pehle:** Har detail ke liye immediately status update
**Ab:** Sabhi invoices create hone ke baad, check karte hain ki product ke saare allocations invoice ho gaye hain ya nahi

```php
// Update sales order product status after all invoices are created
foreach ($salesOrderDetails as $detail) {
    // Check if all allocations for this product have been invoiced
    $allAllocationsInvoiced = true;
    foreach ($detail->warehouseAllocations as $allocation) {
        // Check if this allocation was included in any generated invoice
        $found = false;
        foreach ($invoicesGroup as $groupKey => $items) {
            foreach ($items as $item) {
                if ($item['detail']->id === $detail->id && $item['allocation']->id === $allocation->id) {
                    $found = true;
                    break 2;
                }
            }
        }
        if (!$found) {
            $allAllocationsInvoiced = false;
            break;
        }
    }

    // Update status only if all allocations are invoiced
    if ($allAllocationsInvoiced) {
        $detail->status = 'dispatched';
        $detail->invoice_status = 'completed';
        $detail->save();
    }
}
```

---

## Example Scenario

### Input Data:
| Product | Customer | Facility Name | PO Number | Warehouse | Allocated Qty |
|---------|----------|---------------|-----------|-----------|---------------|
| SKU001  | C1       | Facility A    | PO123     | W1        | 50            |
| SKU001  | C1       | Facility A    | PO123     | W2        | 30            |
| SKU002  | C1       | Facility A    | PO123     | W1        | 100           |
| SKU003  | C1       | Facility B    | PO123     | W1        | 20            |
| SKU004  | C1       | Facility A    | PO456     | W1        | 40            |

### Generated Invoices:

**Invoice 1:** (Warehouse W1 + PO123 + Facility A)
- SKU001: 50 units
- SKU002: 100 units

**Invoice 2:** (Warehouse W2 + PO123 + Facility A)
- SKU001: 30 units

**Invoice 3:** (Warehouse W1 + PO123 + Facility B)
- SKU003: 20 units

**Invoice 4:** (Warehouse W1 + PO456 + Facility A)
- SKU004: 40 units

---

## Benefits

1. **Multi-warehouse support:** Ek sales order ke products agar multiple warehouses se dispatch ho rahe hain, to har warehouse ke liye alag invoice banega
2. **Proper grouping:** Same warehouse, same PO, same facility ke products ek invoice mein group honge
3. **Accurate quantity:** Allocated quantity use ho rahi hai instead of ordered quantity
4. **Status tracking:** Product status tab hi 'dispatched' hoga jab uske saare allocations invoice ho jayenge

---

## Testing Recommendations

1. **Single warehouse order:** Verify ki ek warehouse se saare products ek invoice mein aa rahe hain
2. **Multi-warehouse order:** Verify ki multiple warehouses se products alag-alag invoices mein aa rahe hain
3. **Multiple PO numbers:** Verify ki different PO numbers ke liye alag invoices ban rahe hain
4. **Multiple facilities:** Verify ki different facilities ke liye alag invoices ban rahe hain
5. **Partial allocation:** Verify ki agar kuch allocations select nahi kiye, to product status 'dispatched' na ho

---

## Database Impact

### Tables Affected:
1. **invoices:** `warehouse_id` field ab specific warehouse ID store karega
2. **invoice_details:** `warehouse_id` field ab populate hoga
3. **sales_order_products:** Status update logic change hua hai

### No Schema Changes Required
Saare existing fields already present hain, sirf logic change hua hai.

