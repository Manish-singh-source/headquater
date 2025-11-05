# Multi-Warehouse Auto Allocation System

## 📌 Overview

Yeh system automatically multiple warehouses se stock allocate karta hai jab customer order place karta hai. Agar ek warehouse me stock kam hai, toh automatically next warehouse se allocate hota hai aur remaining quantity ke liye purchase order create hota hai.

---

## ✨ Features

✅ **Auto Allocation** - Multiple warehouses se automatic stock allocation  
✅ **Sequential Processing** - W1 → W2 → W3 sequence me allocation  
✅ **Purchase Order Creation** - Shortage ke liye automatic PO generation  
✅ **Transaction Safety** - Complete DB transaction support  
✅ **Activity Logging** - Har action ka log using Spatie Activity Log  
✅ **Manual Override** - Manual allocation option bhi available  
✅ **API Support** - JSON API endpoints for integration  

---

## 📁 Files Created

### Core Files
1. **Migration:** `database/migrations/2025_11_05_000001_create_warehouse_allocations_table.php`
2. **Model:** `app/Models/WarehouseAllocation.php`
3. **Service:** `app/Services/WarehouseAllocationService.php`
4. **Controller Methods:** Added to `app/Http/Controllers/SalesOrderController.php`
5. **Routes:** Added to `routes/web.php`

### Documentation Files
1. **WAREHOUSE_AUTO_ALLOCATION_GUIDE.md** - Complete detailed guide
2. **COMPLETE_WORKING_EXAMPLE.md** - Step-by-step working example
3. **INTEGRATION_EXAMPLE.php** - Code integration examples
4. **QUICK_REFERENCE.md** - Quick reference card
5. **README_WAREHOUSE_ALLOCATION.md** - This file

---

## 🚀 Installation

### Step 1: Run Migration
```bash
php artisan migrate
```

### Step 2: Verify Tables
```sql
-- Check if table created
SHOW TABLES LIKE 'warehouse_allocations';

-- Check structure
DESCRIBE warehouse_allocations;
```

### Step 3: Test Basic Functionality
```php
// In tinker or test route
php artisan tinker

use App\Services\WarehouseAllocationService;
$service = new WarehouseAllocationService();
// Test methods
```

---

## 💻 Usage

### Basic Usage (3 Lines)
```php
use App\Services\WarehouseAllocationService;

$service = new WarehouseAllocationService();
$result = $service->autoAllocateStock('SKU123', 20, $orderId, $productId);

// Check if purchase order needed
if ($result['need_purchase']) {
    // Handle shortage
}
```

### Complete Example
```php
use App\Services\WarehouseAllocationService;
use Illuminate\Support\Facades\DB;

DB::beginTransaction();
try {
    // Create sales order
    $salesOrder = SalesOrder::create([...]);
    $salesOrderProduct = SalesOrderProduct::create([...]);
    
    // Auto allocate
    $service = new WarehouseAllocationService();
    $result = $service->autoAllocateStock(
        'SKU123',
        20,
        $salesOrder->id,
        $salesOrderProduct->id
    );
    
    // Handle result
    if ($result['need_purchase']) {
        $purchaseItems = [[
            'sku' => 'SKU123',
            'quantity_needed' => $result['pending_quantity'],
            'sales_order_product_id' => $salesOrderProduct->id
        ]];
        
        $service->createPurchaseOrderForShortage(
            $salesOrder->id,
            $purchaseItems,
            $vendorId
        );
    }
    
    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
}
```

---

## 🌐 API Endpoints

### 1. Auto Allocate Stock
```
POST /auto-allocate-stock/{salesOrderId}
```

**Response:**
```json
{
    "success": true,
    "message": "Order allocated successfully",
    "data": {
        "total_allocated": 15,
        "pending_quantity": 5,
        "allocations": [...]
    }
}
```

### 2. Get Allocation Breakdown
```
GET /allocation-breakdown/{salesOrderId}
```

**Response:**
```json
{
    "success": true,
    "sales_order_id": 1,
    "allocations": {
        "SKU123": {
            "total_allocated": 15,
            "warehouses": [...]
        }
    }
}
```

### 3. Manual Allocation
```
POST /manual-allocate
```

**Request:**
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

## 📊 Example Scenario

### Input
- **SKU:** 123
- **Order Quantity:** 20 units
- **W1 Stock:** 5 units
- **W2 Stock:** 10 units
- **W3 Stock:** 0 units

### Output
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

### Database Changes

**warehouse_allocations:**
| id | warehouse_id | sku | allocated_quantity | sequence |
|----|--------------|-----|-------------------|----------|
| 1  | 1            | 123 | 5                 | 1        |
| 2  | 2            | 123 | 10                | 2        |

**warehouse_stocks:**
| warehouse_id | sku | available_quantity | block_quantity |
|--------------|-----|-------------------|----------------|
| 1            | 123 | 0 (was 5)         | 5 (was 0)      |
| 2            | 123 | 0 (was 10)        | 10 (was 0)     |

**purchase_orders:**
| id | sales_order_id | status |
|----|----------------|---------|
| 1  | 1              | pending |

**purchase_order_products:**
| id | purchase_order_id | sku | ordered_quantity |
|----|-------------------|-----|------------------|
| 1  | 1                 | 123 | 5                |

---

## 🔧 Configuration

### Change Warehouse Priority
Edit `app/Services/WarehouseAllocationService.php` line 40:

```php
// Current (by warehouse ID)
->orderBy('warehouse_id')

// By priority (add priority field first)
->orderBy('priority', 'desc')

// By region
->whereHas('warehouse', function($q) {
    $q->where('region', 'North');
})
```

### Add Warehouse Priority
```bash
php artisan make:migration add_priority_to_warehouses
```

```php
Schema::table('warehouses', function (Blueprint $table) {
    $table->integer('priority')->default(0)->after('status');
});
```

---

## 📚 Documentation Files

| File | Description |
|------|-------------|
| `WAREHOUSE_AUTO_ALLOCATION_GUIDE.md` | Complete detailed guide with all features |
| `COMPLETE_WORKING_EXAMPLE.md` | Step-by-step working example with your scenario |
| `INTEGRATION_EXAMPLE.php` | Code examples for integration |
| `QUICK_REFERENCE.md` | Quick reference card for daily use |

---

## 🧪 Testing

### Test Scenario 1: Full Stock
```php
// W1: 50 units, Order: 20 units
// Expected: 20 from W1, 0 pending
```

### Test Scenario 2: Partial Stock
```php
// W1: 5, W2: 10, Order: 20
// Expected: 5 from W1, 10 from W2, 5 pending
```

### Test Scenario 3: No Stock
```php
// All warehouses: 0, Order: 20
// Expected: 0 allocated, 20 pending
```

### Run Tests
```bash
# Create test route
Route::get('/test-allocation', function() {
    // Your test code
});

# Visit
http://localhost/test-allocation
```

---

## 🐛 Troubleshooting

### Issue: Migration Error
```bash
# Check if table exists
php artisan migrate:status

# Rollback and retry
php artisan migrate:rollback
php artisan migrate
```

### Issue: No Allocation
```php
// Check warehouse stock
WarehouseStock::where('sku', 'ABC123')
    ->where('available_quantity', '>', 0)
    ->get();

// Check warehouse status
Warehouse::where('status', '1')->get();
```

### Issue: Service Not Found
```bash
# Clear cache
php artisan config:clear
php artisan cache:clear
composer dump-autoload
```

---

## 📞 Support

### Check Logs
```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Activity logs
SELECT * FROM activity_log ORDER BY id DESC LIMIT 10;
```

### Debug Mode
```php
// In WarehouseAllocationService.php
Log::info('Allocation started', ['sku' => $sku, 'quantity' => $requiredQuantity]);
```

---

## 🎯 Next Steps

1. ✅ Run migration
2. ✅ Test basic allocation
3. ✅ Integrate with existing sales order flow
4. ✅ Test with real data
5. ✅ Monitor activity logs
6. ✅ Customize warehouse priority (optional)

---

## 📝 Model Relationships Added

### SalesOrder
```php
public function warehouseAllocations()
```

### SalesOrderProduct
```php
public function warehouseAllocations()
```

### Warehouse
```php
public function warehouseAllocations()
public function scopeActive($query)
public function scopeInactive($query)
```

---

## ⚡ Performance Tips

1. **Index Usage:** Indexes already added on `sales_order_id`, `warehouse_id`, `sku`
2. **Eager Loading:** Use `->with('warehouse', 'product')` when querying
3. **Caching:** Cache warehouse stock for high-traffic scenarios
4. **Batch Processing:** Use for bulk orders

---

## 🔐 Security

- ✅ CSRF protection on all POST routes
- ✅ Validation on all inputs
- ✅ Transaction safety
- ✅ Activity logging for audit trail
- ✅ Error handling with rollback

---

## 📈 Future Enhancements

- [ ] Warehouse priority system
- [ ] Region-based allocation
- [ ] Real-time stock updates
- [ ] Allocation analytics dashboard
- [ ] Email notifications on shortage
- [ ] Webhook support for external systems

---

**Version:** 1.0  
**Created:** 2025-11-05  
**Laravel Version:** 10+  
**Author:** Warehouse Management System  

---

## 🎉 Ready to Use!

Aap ab is system ko production me use kar sakte hain. Saare files create ho gaye hain aur documentation complete hai.

**Start with:** `COMPLETE_WORKING_EXAMPLE.md` for step-by-step implementation.

