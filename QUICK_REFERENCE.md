# Multi-Warehouse Auto Allocation - Quick Reference Card

## 🚀 Quick Start (3 Steps)

### 1. Run Migration
```bash
php artisan migrate
```

### 2. Use in Controller
```php
use App\Services\WarehouseAllocationService;

$service = new WarehouseAllocationService();
$result = $service->autoAllocateStock('SKU123', 20, $orderId, $productId);
```

### 3. Check Result
```php
if ($result['need_purchase']) {
    // Create purchase order for $result['pending_quantity']
}
```

---

## 📋 Service Methods

### Method 1: Single SKU Allocation
```php
autoAllocateStock($sku, $quantity, $orderId, $productId, $warehouseIds = null)
```

**Example:**
```php
$result = $service->autoAllocateStock('ABC123', 20, 1, 10);
```

**Returns:**
```php
[
    'success' => true,
    'total_allocated' => 15,
    'pending_quantity' => 5,
    'need_purchase' => true,
    'allocations' => [...]
]
```

---

### Method 2: Entire Order Allocation
```php
allocateSalesOrder($salesOrderId)
```

**Example:**
```php
$result = $service->allocateSalesOrder(1);
```

**Returns:**
```php
[
    'success' => true,
    'needs_purchase_order' => true,
    'purchase_order_items' => [...]
]
```

---

### Method 3: Create Purchase Order
```php
createPurchaseOrderForShortage($orderId, $items, $vendorId)
```

**Example:**
```php
$items = [
    ['sku' => 'ABC123', 'quantity_needed' => 5, 'sales_order_product_id' => 10]
];
$result = $service->createPurchaseOrderForShortage(1, $items, 1);
```

---

### Method 4: Get Allocation Breakdown
```php
getAllocationBreakdown($salesOrderId)
```

**Example:**
```php
$breakdown = $service->getAllocationBreakdown(1);
```

---

## 🌐 API Routes

| Method | Route | Description |
|--------|-------|-------------|
| POST | `/auto-allocate-stock/{id}` | Auto allocate order |
| GET | `/allocation-breakdown/{id}` | Get allocation details |
| POST | `/manual-allocate` | Manual allocation |

---

## 📊 Database Tables

### warehouse_allocations
```
- sales_order_id
- warehouse_id
- sku
- allocated_quantity
- sequence (1, 2, 3...)
- status (pending/allocated/fulfilled)
```

---

## 💡 Common Use Cases

### Use Case 1: Auto Allocate on Order Creation
```php
// In SalesOrderController@store
$allocationService = new WarehouseAllocationService();
$result = $allocationService->allocateSalesOrder($salesOrder->id);

if ($result['needs_purchase_order']) {
    $salesOrder->status = 'blocked';
    $salesOrder->save();
}
```

### Use Case 2: Check Allocation Status
```php
$breakdown = $allocationService->getAllocationBreakdown($orderId);
foreach ($breakdown as $sku => $data) {
    echo "SKU: {$sku}, Allocated: {$data['total_allocated']}\n";
}
```

### Use Case 3: Manual Override
```php
// Manually allocate from specific warehouse
WarehouseAllocation::create([
    'sales_order_id' => 1,
    'warehouse_id' => 2,
    'sku' => 'ABC123',
    'allocated_quantity' => 10,
    'sequence' => 1,
    'status' => 'allocated'
]);
```

---

## 🔍 Query Examples

### Get All Allocations for Order
```php
$allocations = WarehouseAllocation::where('sales_order_id', 1)
    ->with('warehouse')
    ->orderBy('sequence')
    ->get();
```

### Get Allocations by SKU
```php
$allocations = WarehouseAllocation::bySku('ABC123')
    ->with('warehouse')
    ->get();
```

### Get Pending Allocations
```php
$pending = WarehouseAllocation::pending()
    ->with('warehouse', 'product')
    ->get();
```

---

## ⚠️ Important Notes

1. **Always use DB transactions**
   ```php
   DB::beginTransaction();
   try {
       // allocation code
       DB::commit();
   } catch (\Exception $e) {
       DB::rollBack();
   }
   ```

2. **Stock automatically updates**
   - `available_quantity` decreases
   - `block_quantity` increases

3. **Activity logging is automatic**
   - Check `activity_log` table

4. **Warehouse sequence**
   - Default: ordered by `warehouse_id`
   - Customize in service: `->orderBy('priority')`

---

## 🐛 Troubleshooting

### Issue: No stock allocated
**Check:**
```php
// Verify warehouse stock
WarehouseStock::where('sku', 'ABC123')
    ->where('available_quantity', '>', 0)
    ->get();

// Check warehouse status
Warehouse::where('status', '1')->get();
```

### Issue: Allocation failed
**Check logs:**
```bash
tail -f storage/logs/laravel.log
```

### Issue: Wrong sequence
**Reset sequence:**
```php
$allocations = WarehouseAllocation::where('sales_order_id', 1)
    ->orderBy('id')
    ->get();

$sequence = 1;
foreach ($allocations as $allocation) {
    $allocation->sequence = $sequence++;
    $allocation->save();
}
```

---

## 📱 AJAX Example

```javascript
// Auto allocate
$.ajax({
    url: '/auto-allocate-stock/1',
    type: 'POST',
    data: { _token: '{{ csrf_token() }}' },
    success: function(response) {
        if (response.success) {
            console.log('Allocated:', response.data.total_allocated);
            console.log('Pending:', response.data.pending_quantity);
        }
    }
});

// Get breakdown
$.get('/allocation-breakdown/1', function(response) {
    console.log(response.allocations);
});
```

---

## 🎯 Response Structure

### Success Response
```json
{
    "success": true,
    "sku": "ABC123",
    "required_quantity": 20,
    "total_allocated": 15,
    "pending_quantity": 5,
    "need_purchase": true,
    "allocations": [
        {
            "warehouse_id": 1,
            "warehouse_name": "Warehouse 1",
            "allocated_quantity": 5,
            "sequence": 1,
            "status": "allocated"
        }
    ],
    "message": "Partially allocated. 5 units need to be purchased."
}
```

### Error Response
```json
{
    "success": false,
    "error": "Error message",
    "sku": "ABC123",
    "required_quantity": 20
}
```

---

## 🔧 Customization

### Change Warehouse Priority
```php
// In WarehouseAllocationService.php, line 40
->orderBy('priority', 'desc')  // Instead of warehouse_id
```

### Add Warehouse Priority Field
```bash
php artisan make:migration add_priority_to_warehouses
```

```php
Schema::table('warehouses', function (Blueprint $table) {
    $table->integer('priority')->default(0);
});
```

### Filter by Region
```php
$warehouseStocksQuery->whereHas('warehouse', function ($query) {
    $query->where('region', 'North');
});
```

---

## 📞 Support

**Files to check:**
- `app/Services/WarehouseAllocationService.php` - Main logic
- `app/Models/WarehouseAllocation.php` - Model
- `app/Http/Controllers/SalesOrderController.php` - Controller methods
- `database/migrations/*_create_warehouse_allocations_table.php` - Migration

**Logs:**
- `storage/logs/laravel.log` - Error logs
- `activity_log` table - Activity tracking

---

**Version:** 1.0  
**Date:** 2025-11-05  
**Laravel:** 10+

