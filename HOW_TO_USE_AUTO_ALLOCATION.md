# How to Use Multi-Warehouse Auto Allocation

## 🎯 Quick Start Guide

### Step 1: Create Sales Order

1. Navigate to **Sales Order → Create New Customer Order**

2. Fill in the form:
   - **Customer Group:** Select customer group
   - **Warehouse Name:** Select **"🔄 Auto Allocate (All Warehouses)"** ← NEW OPTION
   - **Customer PO (CSV/XLSX):** Upload your file

3. Click **Submit**

---

## 📋 What Happens Behind the Scenes

### When you select "Auto Allocate (All Warehouses)":

```
┌─────────────────────────────────────────────────────────┐
│  1. System reads your CSV file                          │
│  2. For each SKU, checks stock in ALL active warehouses │
│  3. Calculates total available stock                    │
│  4. Shows combined availability                         │
│  5. Creates sales order                                 │
│  6. Triggers auto-allocation service                    │
│  7. Allocates stock warehouse by warehouse              │
│  8. Creates warehouse_allocations records               │
│  9. Updates warehouse_stocks (blocks quantity)          │
│ 10. Creates purchase order for shortage (if any)        │
└─────────────────────────────────────────────────────────┘
```

---

## 🔍 Example Walkthrough

### Your Scenario:
```
SKU: IV00024Y
Customer Order: 140 units

Warehouse Stock:
- W1 (Baroda): 60 units
- W3 (Mumbai): 60 units
- Total Available: 120 units
- Shortage: 20 units
```

### Step-by-Step:

#### 1. Upload CSV with this data:
```csv
SKU Code,PO Quantity,Customer Name,Facility Name,...
IV00024Y,140,ABC Corp,Mumbai Store,...
```

#### 2. Select "Auto Allocate (All Warehouses)"

#### 3. System Processing:
```
✓ Reading CSV...
✓ Found SKU: IV00024Y
✓ Checking W1 (Baroda): 60 units available
✓ Checking W3 (Mumbai): 60 units available
✓ Total Available: 120 units
✓ Required: 140 units
✓ Shortage: 20 units

Creating Sales Order...
✓ Sales Order #1 created

Auto-Allocating Stock...
✓ W1 (Baroda): Allocated 60 units (Sequence 1)
✓ W3 (Mumbai): Allocated 60 units (Sequence 2)
✓ Total Allocated: 120 units

Creating Purchase Order for Shortage...
✓ Purchase Order #1 created for 20 units

Updating Warehouse Stock...
✓ W1: Available 60 → 0, Block 0 → 60
✓ W3: Available 60 → 0, Block 0 → 60

✓ Order created successfully!
```

#### 4. Success Message:
```
Sales Order created successfully! Order ID: 1 
(Stock auto-allocated from multiple warehouses)
```

---

## 📊 View Allocation Breakdown

### Go to Sales Order → View Order #1

You'll see a new section:

```
┌──────────────────────────────────────────────────────────────┐
│ Multi-Warehouse Stock Allocation Breakdown                   │
├──────────────────────────────────────────────────────────────┤
│ ℹ This order was auto-allocated from multiple warehouses.   │
│   Below is the breakdown:                                    │
│                                                              │
│ SKU: IV00024Y                                                │
│ ┌─────┬──────────────────┬──────────────┬───────────────┐   │
│ │ Seq │ Warehouse        │ Allocated Qty│ Status        │   │
│ ├─────┼──────────────────┼──────────────┼───────────────┤   │
│ │  1  │ Baroda W1        │     60       │ ✓ Allocated   │   │
│ │  2  │ Mumbai W3        │     60       │ ✓ Allocated   │   │
│ ├─────┴──────────────────┼──────────────┼───────────────┤   │
│ │ Total Allocated:       │    120       │               │   │
│ └────────────────────────┴──────────────┴───────────────┘   │
└──────────────────────────────────────────────────────────────┘
```

---

## 🆚 Comparison: Before vs After

### BEFORE (Single Warehouse Selection):

```
User selects: W1 (Baroda)
SKU: IV00024Y
Order: 140 units
W1 Stock: 60 units

Result:
✓ Available: 60 units
✗ Unavailable: 80 units
✗ Purchase Order: 80 units

Problem: W3 me 60 units available hai but use nahi hua!
```

### AFTER (Auto Allocate):

```
User selects: Auto Allocate (All Warehouses)
SKU: IV00024Y
Order: 140 units
W1 Stock: 60 units
W3 Stock: 60 units

Result:
✓ W1 Allocated: 60 units
✓ W3 Allocated: 60 units
✓ Total Available: 120 units
✗ Unavailable: 20 units
✗ Purchase Order: 20 units

Solution: Dono warehouses ka stock use hua!
```

---

## 🎨 UI Screenshots (Text Version)

### Create Order Form:

```
┌─────────────────────────────────────────────────────┐
│ Create New Customer Order                           │
├─────────────────────────────────────────────────────┤
│                                                     │
│ Customer Group: [Select Customer Group ▼]          │
│                                                     │
│ Warehouse Name: [🔄 Auto Allocate (All Warehouses) ▼] ← NEW!
│                 [Baroda Warehouse 1              ]  │
│                 [Kandivali Warehouse 2           ]  │
│                 [Mumbai Warehouse 3              ]  │
│                                                     │
│ ℹ Select "Auto Allocate" to distribute stock from  │
│   multiple warehouses automatically                 │
│                                                     │
│ Customer PO (CSV/XLSX): [Choose File]              │
│                                                     │
│                         [Submit]                    │
└─────────────────────────────────────────────────────┘
```

### View Order Page:

```
┌─────────────────────────────────────────────────────┐
│ Order Details                                       │
├─────────────────────────────────────────────────────┤
│ Order Id: #1                                        │
│ Customer Group: ABC Corp                            │
│ Status: Blocked                                     │
│ Total PO Quantity: 140                              │
│ Total Purchase Order Quantity: 20                   │
└─────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────┐
│ 📦 Multi-Warehouse Stock Allocation Breakdown       │
├─────────────────────────────────────────────────────┤
│ ℹ This order was auto-allocated from multiple      │
│   warehouses. Below is the breakdown:               │
│                                                     │
│ SKU: IV00024Y                                       │
│ ┌─────┬──────────────┬──────────┬──────────────┐   │
│ │ Seq │ Warehouse    │ Qty      │ Status       │   │
│ ├─────┼──────────────┼──────────┼──────────────┤   │
│ │  1  │ Baroda W1    │   60     │ ✓ Allocated  │   │
│ │  2  │ Mumbai W3    │   60     │ ✓ Allocated  │   │
│ ├─────┴──────────────┼──────────┼──────────────┤   │
│ │ Total:             │  120     │              │   │
│ └────────────────────┴──────────┴──────────────┘   │
└─────────────────────────────────────────────────────┘
```

---

## 🧪 Testing Scenarios

### Test 1: Full Allocation
```
Input:
- SKU: TEST001
- Order: 50 units
- W1: 30 units
- W2: 20 units

Expected Output:
✓ W1: 30 allocated
✓ W2: 20 allocated
✓ Total: 50 allocated
✓ Shortage: 0
✗ No purchase order
```

### Test 2: Partial Allocation (Your Case)
```
Input:
- SKU: IV00024Y
- Order: 140 units
- W1: 60 units
- W3: 60 units

Expected Output:
✓ W1: 60 allocated
✓ W3: 60 allocated
✓ Total: 120 allocated
✗ Shortage: 20
✓ Purchase order created for 20 units
```

### Test 3: No Stock
```
Input:
- SKU: TEST003
- Order: 100 units
- All warehouses: 0 units

Expected Output:
✗ Total: 0 allocated
✗ Shortage: 100
✓ Purchase order created for 100 units
```

### Test 4: Multiple SKUs
```
Input:
- SKU1: 50 units (W1: 30, W2: 20)
- SKU2: 100 units (W1: 50, W3: 30)

Expected Output:
SKU1:
✓ W1: 30, W2: 20, Total: 50, Shortage: 0

SKU2:
✓ W1: 50, W3: 30, Total: 80, Shortage: 20
✓ Purchase order for 20 units
```

---

## 📝 Important Notes

### 1. Warehouse Priority
- Warehouses are processed in order of `warehouse_id`
- W1 → W2 → W3 → ...
- You can customize this in the service file

### 2. Active Warehouses Only
- Only warehouses with `status = 1` are considered
- Inactive warehouses are skipped

### 3. Stock Blocking
- Allocated stock moves from `available_quantity` to `block_quantity`
- This prevents double allocation

### 4. Purchase Orders
- Automatically created for shortage
- Linked to sales order
- Status: Pending

### 5. Activity Logging
- All allocations are logged
- Check `activity_log` table
- Useful for audit trail

---

## 🔧 Troubleshooting

### Issue: "Auto Allocate" option not showing
**Solution:** Clear browser cache and refresh page

### Issue: No allocation happening
**Check:**
1. Are warehouses active? (status = 1)
2. Is stock available? (available_quantity > 0)
3. Check Laravel logs: `storage/logs/laravel.log`

### Issue: Wrong allocation sequence
**Solution:** Warehouses are ordered by `warehouse_id`. To change:
1. Edit `app/Services/WarehouseAllocationService.php`
2. Line 40: Change `->orderBy('warehouse_id')` to your preference

### Issue: Allocation breakdown not showing
**Check:**
1. Was "Auto Allocate" selected during order creation?
2. Check `warehouse_allocations` table for records
3. Refresh the view order page

---

## 🎯 Quick Reference

| Action | Location | What to Do |
|--------|----------|------------|
| Create Order | Sales Order → Create | Select "Auto Allocate" option |
| View Breakdown | Sales Order → View Order | Scroll to "Multi-Warehouse Stock Allocation Breakdown" |
| Check Logs | Database | Query `warehouse_allocations` table |
| Check Activity | Database | Query `activity_log` table |

---

## 📞 Support

### Database Tables to Check:
```sql
-- Check allocations
SELECT * FROM warehouse_allocations WHERE sales_order_id = 1;

-- Check warehouse stock
SELECT warehouse_id, sku, available_quantity, block_quantity 
FROM warehouse_stocks 
WHERE sku = 'IV00024Y';

-- Check purchase orders
SELECT * FROM purchase_orders WHERE sales_order_id = 1;
```

### Log Files:
- Laravel Log: `storage/logs/laravel.log`
- Activity Log: `activity_log` table in database

---

## ✅ Checklist

Before using auto allocation:

- [ ] Migration run ho gaya hai
- [ ] Warehouses active hain (status = 1)
- [ ] Stock available hai warehouses me
- [ ] CSV file correct format me hai
- [ ] Browser cache clear hai

---

**Ready to use!** 🚀

Aap ab multi-warehouse auto allocation use kar sakte hain!

