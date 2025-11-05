# Complete Working Example - Multi-Warehouse Auto Allocation

## Scenario (Aapka Example)

**Customer Order:**
- SKU: `123`
- Ordered Quantity: `20 units`

**Warehouse Stock:**
- Warehouse W1 (Baroda): `5 units` available
- Warehouse W2 (Kandivali): `10 units` available
- Warehouse W3 (Mumbai): `0 units` available

**Expected Result:**
1. W1 se 5 units allocate ho
2. W2 se 10 units allocate ho
3. Remaining 5 units "Need to Purchase" mark ho
4. Purchase order automatically create ho

---

## Step-by-Step Implementation

### Step 1: Database Setup

```bash
# Run migration
php artisan migrate
```

Yeh `warehouse_allocations` table create karega.

### Step 2: Seed Warehouse Data (Already exists in your system)

```php
// Warehouses table me already data hai:
// ID: 1, Name: Baroda Warehouse 1
// ID: 2, Name: Kandivali Warehouse 2
// ID: 3, Name: Mumbai Warehouse 3
```

### Step 3: Seed Warehouse Stock

```php
// warehouse_stocks table
use App\Models\WarehouseStock;

// Warehouse 1 - Baroda
WarehouseStock::create([
    'warehouse_id' => 1,
    'sku' => '123',
    'original_quantity' => 5,
    'available_quantity' => 5,
    'block_quantity' => 0
]);

// Warehouse 2 - Kandivali
WarehouseStock::create([
    'warehouse_id' => 2,
    'sku' => '123',
    'original_quantity' => 10,
    'available_quantity' => 10,
    'block_quantity' => 0
]);

// Warehouse 3 - Mumbai (no stock)
WarehouseStock::create([
    'warehouse_id' => 3,
    'sku' => '123',
    'original_quantity' => 0,
    'available_quantity' => 0,
    'block_quantity' => 0
]);
```

### Step 4: Create Sales Order with Auto Allocation

```php
<?php

use App\Models\SalesOrder;
use App\Models\SalesOrderProduct;
use App\Services\WarehouseAllocationService;
use Illuminate\Support\Facades\DB;

DB::beginTransaction();
try {
    // 1. Create Sales Order
    $salesOrder = SalesOrder::create([
        'warehouse_id' => 1,  // Primary warehouse
        'customer_group_id' => 1,
        'status' => 'pending'
    ]);
    
    echo "✓ Sales Order Created: ID = {$salesOrder->id}\n";

    // 2. Create Sales Order Product
    $salesOrderProduct = SalesOrderProduct::create([
        'sales_order_id' => $salesOrder->id,
        'sku' => '123',
        'ordered_quantity' => 20,
        'customer_id' => 1,
        'vendor_code' => 1,
        'price' => 100,
        'subtotal' => 2000
    ]);
    
    echo "✓ Sales Order Product Created: ID = {$salesOrderProduct->id}\n";

    // 3. Auto Allocate Stock from Multiple Warehouses
    $allocationService = new WarehouseAllocationService();
    
    $result = $allocationService->autoAllocateStock(
        '123',                      // SKU
        20,                         // Required Quantity
        $salesOrder->id,            // Sales Order ID
        $salesOrderProduct->id      // Sales Order Product ID
    );

    echo "\n=== ALLOCATION RESULT ===\n";
    echo "Success: " . ($result['success'] ? 'Yes' : 'No') . "\n";
    echo "SKU: {$result['sku']}\n";
    echo "Required Quantity: {$result['required_quantity']}\n";
    echo "Total Allocated: {$result['total_allocated']}\n";
    echo "Pending Quantity: {$result['pending_quantity']}\n";
    echo "Need Purchase: " . ($result['need_purchase'] ? 'Yes' : 'No') . "\n";
    echo "Message: {$result['message']}\n";

    echo "\n=== WAREHOUSE BREAKDOWN ===\n";
    foreach ($result['allocations'] as $allocation) {
        echo "Warehouse: {$allocation['warehouse_name']}\n";
        echo "  - Allocated: {$allocation['allocated_quantity']} units\n";
        echo "  - Sequence: {$allocation['sequence']}\n";
        echo "  - Status: {$allocation['status']}\n";
        echo "\n";
    }

    // 4. Create Purchase Order for Shortage
    if ($result['need_purchase']) {
        echo "=== CREATING PURCHASE ORDER ===\n";
        
        $purchaseItems = [
            [
                'sku' => '123',
                'quantity_needed' => $result['pending_quantity'],
                'sales_order_product_id' => $salesOrderProduct->id
            ]
        ];

        $purchaseResult = $allocationService->createPurchaseOrderForShortage(
            $salesOrder->id,
            $purchaseItems,
            1  // Vendor ID
        );

        if ($purchaseResult['success']) {
            echo "✓ Purchase Order Created: ID = {$purchaseResult['purchase_order_id']}\n";
            echo "  - Items: " . count($purchaseResult['items']) . "\n";
            
            // Update sales order status to blocked
            $salesOrder->status = 'blocked';
            $salesOrder->save();
            echo "✓ Sales Order Status: blocked\n";
        }
    }

    DB::commit();
    echo "\n✓ Transaction Committed Successfully!\n";

} catch (\Exception $e) {
    DB::rollBack();
    echo "\n✗ Error: " . $e->getMessage() . "\n";
}
```

---

## Expected Output

```
✓ Sales Order Created: ID = 1
✓ Sales Order Product Created: ID = 1

=== ALLOCATION RESULT ===
Success: Yes
SKU: 123
Required Quantity: 20
Total Allocated: 15
Pending Quantity: 5
Need Purchase: Yes
Message: Partially allocated. 5 units need to be purchased.

=== WAREHOUSE BREAKDOWN ===
Warehouse: Baroda Warehouse 1
  - Allocated: 5 units
  - Sequence: 1
  - Status: allocated

Warehouse: Kandivali Warehouse 2
  - Allocated: 10 units
  - Sequence: 2
  - Status: allocated

=== CREATING PURCHASE ORDER ===
✓ Purchase Order Created: ID = 1
  - Items: 1
✓ Sales Order Status: blocked

✓ Transaction Committed Successfully!
```

---

## Database State After Execution

### Table: `warehouse_allocations`

| id | sales_order_id | sales_order_product_id | warehouse_id | sku | allocated_quantity | sequence | status | notes |
|----|----------------|------------------------|--------------|-----|-------------------|----------|---------|-------|
| 1  | 1              | 1                      | 1            | 123 | 5                 | 1        | allocated | Auto-allocated 5 units from warehouse Baroda Warehouse 1 |
| 2  | 1              | 1                      | 2            | 123 | 10                | 2        | allocated | Auto-allocated 10 units from warehouse Kandivali Warehouse 2 |

### Table: `warehouse_stocks` (Updated)

| id | warehouse_id | sku | original_quantity | available_quantity | block_quantity |
|----|--------------|-----|-------------------|-------------------|----------------|
| 1  | 1            | 123 | 5                 | **0** (was 5)     | **5** (was 0)  |
| 2  | 2            | 123 | 10                | **0** (was 10)    | **10** (was 0) |
| 3  | 3            | 123 | 0                 | 0                 | 0              |

### Table: `sales_orders`

| id | warehouse_id | customer_group_id | status | created_at |
|----|--------------|-------------------|---------|------------|
| 1  | 1            | 1                 | **blocked** | 2025-11-05 |

### Table: `sales_order_products`

| id | sales_order_id | sku | ordered_quantity | purchase_ordered_quantity | price | subtotal |
|----|----------------|-----|------------------|---------------------------|-------|----------|
| 1  | 1              | 123 | 20               | 5                         | 100   | 2000     |

### Table: `purchase_orders`

| id | sales_order_id | warehouse_id | vendor_id | status | created_at |
|----|----------------|--------------|-----------|---------|------------|
| 1  | 1              | 1            | 1         | pending | 2025-11-05 |

### Table: `purchase_order_products`

| id | purchase_order_id | sales_order_id | sku | ordered_quantity |
|----|-------------------|----------------|-----|------------------|
| 1  | 1                 | 1              | 123 | 5                |

---

## JSON API Response Example

```json
{
    "success": true,
    "sku": "123",
    "required_quantity": 20,
    "total_allocated": 15,
    "pending_quantity": 5,
    "need_purchase": true,
    "allocations": [
        {
            "warehouse_id": 1,
            "warehouse_name": "Baroda Warehouse 1",
            "allocated_quantity": 5,
            "sequence": 1,
            "status": "allocated"
        },
        {
            "warehouse_id": 2,
            "warehouse_name": "Kandivali Warehouse 2",
            "allocated_quantity": 10,
            "sequence": 2,
            "status": "allocated"
        }
    ],
    "message": "Partially allocated. 5 units need to be purchased."
}
```

---

## How to Use in Your Existing Code

### Option 1: Replace Existing Logic in SalesOrderController

Find your current `store()` method around line 122 and add this after creating sales order product:

```php
// After line 379 in your current SalesOrderController
$saveOrderProduct->save();

// ADD THIS CODE:
$allocationService = new WarehouseAllocationService();
$allocationResult = $allocationService->autoAllocateStock(
    $sku,
    $record['PO Quantity'],
    $salesOrder->id,
    $saveOrderProduct->id
);

// Update purchase order logic based on allocation result
if ($allocationResult['need_purchase']) {
    // Your existing purchase order creation logic
    // But use $allocationResult['pending_quantity'] instead of $shortQty
}
```

### Option 2: Create New Route for Testing

```php
// In routes/web.php
Route::get('/test-auto-allocation', function() {
    // Use the code from Step 4 above
});
```

Then visit: `http://localhost/test-auto-allocation`

---

## Verification Queries

```sql
-- Check allocations
SELECT 
    wa.id,
    wa.sales_order_id,
    w.name as warehouse_name,
    wa.sku,
    wa.allocated_quantity,
    wa.sequence,
    wa.status
FROM warehouse_allocations wa
JOIN warehouses w ON wa.warehouse_id = w.id
WHERE wa.sales_order_id = 1
ORDER BY wa.sequence;

-- Check warehouse stock changes
SELECT 
    w.name as warehouse_name,
    ws.sku,
    ws.original_quantity,
    ws.available_quantity,
    ws.block_quantity
FROM warehouse_stocks ws
JOIN warehouses w ON ws.warehouse_id = w.id
WHERE ws.sku = '123';

-- Check purchase order
SELECT 
    po.id,
    po.sales_order_id,
    po.status,
    pop.sku,
    pop.ordered_quantity
FROM purchase_orders po
JOIN purchase_order_products pop ON po.id = pop.purchase_order_id
WHERE po.sales_order_id = 1;
```

---

## Testing Different Scenarios

### Scenario 1: Full Stock Available
```php
// W1: 50 units, Order: 20 units
// Expected: 20 from W1, 0 pending, no purchase order
```

### Scenario 2: Partial Stock
```php
// W1: 5, W2: 10, Order: 20
// Expected: 5 from W1, 10 from W2, 5 pending, purchase order created
```

### Scenario 3: No Stock
```php
// All warehouses: 0, Order: 20
// Expected: 0 allocated, 20 pending, purchase order created
```

### Scenario 4: Multiple Warehouses
```php
// W1: 3, W2: 5, W3: 7, Order: 20
// Expected: 3 from W1, 5 from W2, 7 from W3, 5 pending
```

---

**Ready to Use!** 🚀

Aap ab is system ko apne existing code me integrate kar sakte hain.

