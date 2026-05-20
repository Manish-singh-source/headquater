# Sales Order Import Block Quantity Solution

## Requirement

In `app/Http/Controllers/SalesOrderController.php`, the `store()` import should treat the uploaded `Block` value as the quantity the user wants to reserve from warehouse stock.

If a row has:

- `PO Quantity = 20`
- `Block = 20`

then the system should block 20 units and create no purchase order quantity for that row.

If a row has:

- `PO Quantity = 20`
- `Block = 10`

then the system should block 10 units and create a purchase order only for the remaining 10 units.

## Current Behavior

Inside `store()`, the code currently calculates stock shortage from the full `PO Quantity`.

Relevant current flow:

1. `$poQty` is read from `PO Quantity`.
2. `$blockQty` is read from `Block`.
3. `$availableQty` is taken from `$productStockCache[$sku]['available']`.
4. If available stock is enough for the full PO quantity, the cache is reduced by full `$poQty`.
5. If available stock is not enough for the full PO quantity, `$shortQty = $poQty - $availableQty`.
6. `TempOrder.block` is stored as `min(record Block, availableQty)`.
7. `purchase_order_quantity` is stored from `$shortQty`.
8. Purchase orders are created when `$shortQty > 0` or uploaded `Purchase Order Quantity > 0`.

Because the cache is reduced by full `PO Quantity`, stock can be consumed even when the user only wants to block part of that row. This causes later rows to show shortage and purchase order quantity incorrectly.

## What Happens With The Attached File

The attached file contains these rows for the same SKU:

| Row | Customer | PO Quantity | Purchase Order Quantity | Block |
| --- | --- | ---: | ---: | ---: |
| 2 | shubham | 180 | 0 | 150 |
| 3 | saurabh | 10 | 10 | 0 |
| 4 | manish | 20 | 20 | 20 |
| 5 | rupa | 20 | 20 | 10 |

The controller sorts rows by `Block` descending before processing:

1. shubham: `Block 150`
2. manish: `Block 20`
3. rupa: `Block 10`
4. saurabh: `Block 0`

If warehouse stock is 180 for that SKU, current code behavior becomes:

| Processed Row | Current Block | Current Purchase Qty | Reason |
| --- | ---: | ---: | --- |
| shubham | 150 | 0 | Full `PO Quantity` 180 is deducted from cache even though only 150 is blocked. |
| manish | 0 | 20 | Cache is already 0, so `Block 20` cannot be applied. |
| rupa | 0 | 20 | Cache is already 0. |
| saurabh | 0 | 10 | Cache is already 0. |

This is not the required behavior.

## Expected Behavior

The import should calculate three separate values per row:

- `requestedBlockQty`: the value from Excel `Block`.
- `actualBlockQty`: the quantity that can really be blocked from available stock.
- `purchaseOrderQty`: the remaining quantity that still needs purchase.

Formula:

```php
$requestedBlockQty = max(0, (int) ($record['Block'] ?? 0));
$poQty = max(0, (int) ($record['PO Quantity'] ?? 0));

$actualBlockQty = min($requestedBlockQty, $poQty, $availableQty);
$purchaseOrderQty = max(0, $poQty - $actualBlockQty);
```

Then reduce warehouse/cache stock only by `$actualBlockQty`, not by `$poQty`.

```php
$productStockCache[$sku]['available'] = max(0, $availableQty - $actualBlockQty);
```

With the attached file and 180 available stock, expected result should be:

| Processed Row | Expected Block | Expected Purchase Qty |
| --- | ---: | ---: |
| shubham | 150 | 30 |
| manish | 20 | 0 |
| rupa | 10 | 10 |
| saurabh | 0 | 10 |

Total blocked quantity: `180`

Total purchase order quantity: `50`

## Code-Level Change Needed

In `store()`, replace the current stock check block:

```php
if ($availableQty >= $poQty) {
    $productStockCache[$sku]['available'] -= $poQty;
    $availableQty = $poQty;
} else {
    $shortQty = $poQty - $availableQty;
    $productStockCache[$sku]['available'] = 0;
}
```

with logic based on the requested block quantity:

```php
$requestedBlockQty = max(0, (int) ($record['Block'] ?? 0));
$actualBlockQty = min($requestedBlockQty, $poQty, $availableQty);
$shortQty = max(0, $poQty - $actualBlockQty);

$productStockCache[$sku]['available'] = max(0, $availableQty - $actualBlockQty);
$availableQty = $actualBlockQty;
```

After this change:

- `$availableQty` represents the actual blocked quantity for this row.
- `$shortQty` represents the quantity to purchase.
- `TempOrder.block` should use `$actualBlockQty`.
- `TempOrder.available_quantity` should use `$actualBlockQty`.
- `TempOrder.unavailable_quantity` should use `$shortQty`.
- `TempOrder.purchase_order_quantity` should use `$shortQty`.
- `SalesOrderProduct.purchase_ordered_quantity` should use `$shortQty`.
- `SalesOrderProduct.dispatched_quantity` should use `$actualBlockQty`.
- `WarehouseAllocation.allocated_quantity` should use `$actualBlockQty`.
- `PurchaseOrderProduct.ordered_quantity` should use `$shortQty`.

## Important Cleanup

The purchase order creation condition should not depend on the uploaded `Purchase Order Quantity` column.

Current condition:

```php
if ($shortQty > 0 || $record['Purchase Order Quantity'] ?? 0 > 0) {
```

Recommended condition:

```php
if ($shortQty > 0) {
```

Reason: once purchase quantity is calculated from `PO Quantity - actual blocked quantity`, the uploaded `Purchase Order Quantity` should not force purchase order creation.

## Extra Note For Auto Allocation

The same business rule should also be reviewed for auto allocation.

Currently auto allocation calls:

```php
$allocationService->autoAllocateStock(
    $orderProduct->sku,
    $orderProduct->ordered_quantity,
    $salesOrder->id,
    $orderProduct->id
);
```

That passes the full ordered quantity. If the `Block` column should control allocation in auto mode too, the service should receive the requested/allowed block quantity instead of the full ordered quantity, and pending quantity should still be calculated as:

```php
$poQty - $actualAllocatedBlockQty
```
