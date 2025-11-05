# Multi-Warehouse Auto Allocation System - Complete Guide

## Overview
Yeh system automatically multiple warehouses se stock allocate karta hai jab customer order place karta hai. Agar ek warehouse me stock kam hai, toh automatically next warehouse se allocate hota hai.

---

## Database Structure

### Table: `warehouse_allocations`
```sql
- id (Primary Key)
- sales_order_id (Foreign Key → sales_orders)
- sales_order_product_id (Foreign Key → sales_order_products)
- warehouse_id (Foreign Key → warehouses)
- sku (Product SKU)
- allocated_quantity (Integer)
- sequence (Integer - allocation order: 1, 2, 3...)
- status (Enum: pending, allocated, fulfilled, cancelled)
- notes (Text - optional)
- timestamps
```

---

## Models & Relationships

### 1. WarehouseAllocation Model
**Location:** `app/Models/WarehouseAllocation.php`

**Relationships:**
- `salesOrder()` - belongs to SalesOrder
- `salesOrderProduct()` - belongs to SalesOrderProduct
- `warehouse()` - belongs to Warehouse
- `product()` - has one Product
- `warehouseStock()` - has one WarehouseStock

**Scopes:**
- `pending()` - status = 'pending'
- `allocated()` - status = 'allocated'
- `fulfilled()` - status = 'fulfilled'
- `cancelled()` - status = 'cancelled'
- `bySku($sku)` - filter by SKU
- `byWarehouse($warehouseId)` - filter by warehouse
- `byOrder($orderId)` - filter by sales order

---

## Service Class: WarehouseAllocationService

**Location:** `app/Services/WarehouseAllocationService.php`

### Main Methods:

#### 1. `autoAllocateStock($sku, $requiredQuantity, $salesOrderId, $salesOrderProductId, $warehouseIds = null)`
Single SKU ke liye multiple warehouses se auto-allocation.

**Parameters:**
- `$sku` - Product SKU code
- `$requiredQuantity` - Total quantity needed
- `$salesOrderId` - Sales Order ID
- `$salesOrderProductId` - Sales Order Product ID
- `$warehouseIds` (optional) - Specific warehouses to check

**Returns:**
```php
[
    'success' => true,
    'sku' => '123',
    'required_quantity' => 20,
    'total_allocated' => 15,
    'pending_quantity' => 5,
    'need_purchase' => true,
    'allocations' => [
        [
            'warehouse_id' => 1,
            'warehouse_name' => 'Baroda Warehouse 1',
            'allocated_quantity' => 5,
            'sequence' => 1,
            'status' => 'allocated'
        ],
        [
            'warehouse_id' => 2,
            'warehouse_name' => 'Kandivali Warehouse 2',
            'allocated_quantity' => 10,
            'sequence' => 2,
            'status' => 'allocated'
        ]
    ],
    'message' => 'Partially allocated. 5 units need to be purchased.'
]
```

#### 2. `allocateSalesOrder($salesOrderId)`
Entire sales order ke liye allocation (multiple SKUs).

**Returns:**
```php
[
    'success' => true,
    'sales_order_id' => 1,
    'allocations' => [...],
    'needs_purchase_order' => true,
    'purchase_order_items' => [
        [
            'sku' => '123',
            'quantity_needed' => 5,
            'sales_order_product_id' => 10
        ]
    ],
    'message' => 'Order partially allocated. Purchase order required.'
]
```

#### 3. `createPurchaseOrderForShortage($salesOrderId, $purchaseItems, $vendorId)`
Shortage ke liye automatic purchase order create karta hai.

#### 4. `getAllocationBreakdown($salesOrderId)`
Sales order ka complete allocation breakdown.

---

## Controller Methods

**Location:** `app/Http/Controllers/SalesOrderController.php`

### 1. Auto Allocate Stock
```php
POST /auto-allocate-stock/{salesOrderId}
Route Name: sales.order.auto.allocate
```

**Example Usage:**
```javascript
// AJAX Call
$.ajax({
    url: '/auto-allocate-stock/1',
    type: 'POST',
    data: {
        _token: '{{ csrf_token() }}'
    },
    success: function(response) {
        console.log(response);
        // Response structure same as service method
    }
});
```

### 2. Get Allocation Breakdown
```php
GET /allocation-breakdown/{salesOrderId}
Route Name: sales.order.allocation.breakdown
```

**Example Response:**
```json
{
    "success": true,
    "sales_order_id": 1,
    "allocations": {
        "SKU123": {
            "sku": "SKU123",
            "product_name": "Product Name",
            "total_allocated": 15,
            "warehouses": [
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
            ]
        }
    }
}
```

### 3. Manual Allocation
```php
POST /manual-allocate
Route Name: sales.order.manual.allocate
```

**Request Body:**
```json
{
    "sales_order_id": 1,
    "sales_order_product_id": 10,
    "sku": "SKU123",
    "warehouse_id": 1,
    "quantity": 5
}
```

---

## Complete Example Scenario

### Scenario:
Customer ne SKU "ABC123" ka order diya - Quantity: 20 units

**Warehouse Stock:**
- Warehouse 1 (Baroda): 5 units available
- Warehouse 2 (Kandivali): 10 units available
- Warehouse 3 (Mumbai): 0 units available

### Step-by-Step Process:

#### Step 1: Create Sales Order
```php
$salesOrder = SalesOrder::create([
    'warehouse_id' => 1,
    'customer_group_id' => 1,
    'status' => 'pending'
]);

$salesOrderProduct = SalesOrderProduct::create([
    'sales_order_id' => $salesOrder->id,
    'sku' => 'ABC123',
    'ordered_quantity' => 20,
    'customer_id' => 1
]);
```

#### Step 2: Auto Allocate
```php
use App\Services\WarehouseAllocationService;

$allocationService = new WarehouseAllocationService();
$result = $allocationService->autoAllocateStock(
    'ABC123',      // SKU
    20,            // Required Quantity
    $salesOrder->id,
    $salesOrderProduct->id
);
```

#### Step 3: Result
```php
[
    'success' => true,
    'sku' => 'ABC123',
    'required_quantity' => 20,
    'total_allocated' => 15,
    'pending_quantity' => 5,
    'need_purchase' => true,
    'allocations' => [
        [
            'warehouse_id' => 1,
            'warehouse_name' => 'Baroda Warehouse 1',
            'allocated_quantity' => 5,
            'sequence' => 1,
            'status' => 'allocated'
        ],
        [
            'warehouse_id' => 2,
            'warehouse_name' => 'Kandivali Warehouse 2',
            'allocated_quantity' => 10,
            'sequence' => 2,
            'status' => 'allocated'
        ]
    ],
    'message' => 'Partially allocated. 5 units need to be purchased.'
]
```

#### Step 4: Database Changes

**warehouse_allocations table:**
| id | sales_order_id | warehouse_id | sku | allocated_quantity | sequence | status |
|----|----------------|--------------|-----|-------------------|----------|---------|
| 1  | 1              | 1            | ABC123 | 5              | 1        | allocated |
| 2  | 1              | 2            | ABC123 | 10             | 2        | allocated |

**warehouse_stocks table:**
| warehouse_id | sku | available_quantity | block_quantity |
|--------------|-----|-------------------|----------------|
| 1            | ABC123 | 0 (was 5)      | 5 (was 0)      |
| 2            | ABC123 | 0 (was 10)     | 10 (was 0)     |

#### Step 5: Purchase Order (if needed)
```php
if ($result['need_purchase']) {
    $purchaseItems = [
        [
            'sku' => 'ABC123',
            'quantity_needed' => 5,
            'sales_order_product_id' => $salesOrderProduct->id
        ]
    ];
    
    $purchaseResult = $allocationService->createPurchaseOrderForShortage(
        $salesOrder->id,
        $purchaseItems,
        $vendorId
    );
}
```

---

## Migration Command

```bash
php artisan migrate
```

This will create the `warehouse_allocations` table.

---

## Testing

### Test 1: Full Allocation
```php
// Warehouse 1: 50 units
// Order: 20 units
// Expected: Fully allocated from Warehouse 1
```

### Test 2: Partial Allocation
```php
// Warehouse 1: 5 units
// Warehouse 2: 10 units
// Order: 20 units
// Expected: 5 from W1, 10 from W2, 5 pending
```

### Test 3: No Stock
```php
// All warehouses: 0 units
// Order: 20 units
// Expected: 0 allocated, 20 pending, purchase order needed
```

---

## Important Notes

1. **Transaction Safety:** Sab operations DB transactions me wrapped hain
2. **Activity Logging:** Har allocation log hota hai using Spatie Activity Log
3. **Stock Updates:** Available quantity automatically block quantity me move hoti hai
4. **Sequence:** Warehouses sequence me process hote hain (you can customize ordering)
5. **Error Handling:** Proper try-catch blocks with rollback

---

## Customization Options

### Change Warehouse Priority
Edit `WarehouseAllocationService.php` line 40:
```php
->orderBy('warehouse_id'); // Change to ->orderBy('priority') or custom logic
```

### Add Warehouse Priority Field
```php
// Migration
Schema::table('warehouses', function (Blueprint $table) {
    $table->integer('priority')->default(0);
});

// Then update service:
->orderBy('priority', 'desc')
```

---

## API Endpoints Summary

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/auto-allocate-stock/{id}` | Auto allocate entire sales order |
| GET | `/allocation-breakdown/{id}` | Get allocation details |
| POST | `/manual-allocate` | Manually allocate from specific warehouse |

---

## Support & Questions

For any issues or questions, check:
1. Activity logs: `activity_log` table
2. Laravel logs: `storage/logs/laravel.log`
3. Database: `warehouse_allocations` table

---

**Created:** 2025-11-05
**Version:** 1.0
**Laravel Version:** 10+

