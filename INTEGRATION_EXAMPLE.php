<?php

/**
 * INTEGRATION EXAMPLE - Multi-Warehouse Auto Allocation
 * 
 * Yeh file dikhata hai ki aap existing sales order creation me 
 * auto-allocation kaise integrate kar sakte hain
 */

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use App\Models\SalesOrderProduct;
use App\Services\WarehouseAllocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ExampleIntegrationController extends Controller
{
    /**
     * EXAMPLE 1: Simple Auto Allocation
     * 
     * Ek single SKU ke liye auto allocation
     */
    public function simpleAutoAllocation()
    {
        DB::beginTransaction();
        try {
            // Step 1: Create Sales Order
            $salesOrder = SalesOrder::create([
                'warehouse_id' => 1,
                'customer_group_id' => 1,
                'status' => 'pending'
            ]);

            // Step 2: Create Sales Order Product
            $salesOrderProduct = SalesOrderProduct::create([
                'sales_order_id' => $salesOrder->id,
                'sku' => 'SKU123',
                'ordered_quantity' => 20,
                'customer_id' => 1,
                'price' => 100,
                'subtotal' => 2000
            ]);

            // Step 3: Auto Allocate Stock
            $allocationService = new WarehouseAllocationService();
            $result = $allocationService->autoAllocateStock(
                'SKU123',                    // SKU
                20,                          // Required Quantity
                $salesOrder->id,             // Sales Order ID
                $salesOrderProduct->id       // Sales Order Product ID
            );

            // Step 4: Check Result
            if ($result['success']) {
                if ($result['need_purchase']) {
                    // Partial allocation - Purchase order needed
                    $salesOrder->status = 'blocked';
                    $salesOrder->save();

                    // Create purchase order for remaining quantity
                    $purchaseItems = [
                        [
                            'sku' => 'SKU123',
                            'quantity_needed' => $result['pending_quantity'],
                            'sales_order_product_id' => $salesOrderProduct->id
                        ]
                    ];

                    $purchaseResult = $allocationService->createPurchaseOrderForShortage(
                        $salesOrder->id,
                        $purchaseItems,
                        1 // Vendor ID
                    );
                } else {
                    // Full allocation
                    $salesOrder->status = 'pending';
                    $salesOrder->save();
                }

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Order created and stock allocated successfully',
                    'allocation' => $result
                ]);
            } else {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Allocation failed: ' . $result['error']
                ], 500);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * EXAMPLE 2: Multiple SKUs Auto Allocation
     * 
     * Multiple products ke liye ek saath allocation
     */
    public function multipleSkuAllocation()
    {
        DB::beginTransaction();
        try {
            // Step 1: Create Sales Order
            $salesOrder = SalesOrder::create([
                'warehouse_id' => 1,
                'customer_group_id' => 1,
                'status' => 'pending'
            ]);

            // Step 2: Create Multiple Products
            $products = [
                ['sku' => 'SKU123', 'quantity' => 20, 'price' => 100],
                ['sku' => 'SKU456', 'quantity' => 15, 'price' => 200],
                ['sku' => 'SKU789', 'quantity' => 30, 'price' => 150],
            ];

            foreach ($products as $product) {
                SalesOrderProduct::create([
                    'sales_order_id' => $salesOrder->id,
                    'sku' => $product['sku'],
                    'ordered_quantity' => $product['quantity'],
                    'customer_id' => 1,
                    'price' => $product['price'],
                    'subtotal' => $product['quantity'] * $product['price']
                ]);
            }

            // Step 3: Auto Allocate Entire Order
            $allocationService = new WarehouseAllocationService();
            $result = $allocationService->allocateSalesOrder($salesOrder->id);

            // Step 4: Handle Result
            if ($result['success']) {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'data' => $result
                ]);
            } else {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Allocation failed: ' . $result['error']
                ], 500);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Multiple SKU allocation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * EXAMPLE 3: Specific Warehouses Allocation
     * 
     * Sirf specific warehouses se allocate karna
     */
    public function specificWarehouseAllocation()
    {
        DB::beginTransaction();
        try {
            $salesOrder = SalesOrder::create([
                'warehouse_id' => 1,
                'customer_group_id' => 1,
                'status' => 'pending'
            ]);

            $salesOrderProduct = SalesOrderProduct::create([
                'sales_order_id' => $salesOrder->id,
                'sku' => 'SKU123',
                'ordered_quantity' => 20,
                'customer_id' => 1
            ]);

            // Only check Warehouse 1 and 2 (not 3, 4, etc.)
            $specificWarehouses = [1, 2];

            $allocationService = new WarehouseAllocationService();
            $result = $allocationService->autoAllocateStock(
                'SKU123',
                20,
                $salesOrder->id,
                $salesOrderProduct->id,
                $specificWarehouses  // Pass specific warehouse IDs
            );

            DB::commit();
            return response()->json($result);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * EXAMPLE 4: Get Allocation Details
     * 
     * Order ka complete allocation breakdown dekhna
     */
    public function viewAllocationDetails($salesOrderId)
    {
        try {
            $allocationService = new WarehouseAllocationService();
            $breakdown = $allocationService->getAllocationBreakdown($salesOrderId);

            return response()->json([
                'success' => true,
                'sales_order_id' => $salesOrderId,
                'allocations' => $breakdown
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * EXAMPLE 5: Manual Allocation
     * 
     * Manually specific warehouse se allocate karna
     */
    public function manualAllocationExample(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sales_order_id' => 'required|exists:sales_orders,id',
            'sales_order_product_id' => 'required|exists:sales_order_products,id',
            'sku' => 'required|string',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $warehouseStock = \App\Models\WarehouseStock::where('warehouse_id', $request->warehouse_id)
                ->where('sku', $request->sku)
                ->first();

            if (!$warehouseStock || $warehouseStock->available_quantity < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock in selected warehouse'
                ], 400);
            }

            $lastSequence = \App\Models\WarehouseAllocation::where('sales_order_id', $request->sales_order_id)
                ->where('sku', $request->sku)
                ->max('sequence') ?? 0;

            $allocation = \App\Models\WarehouseAllocation::create([
                'sales_order_id' => $request->sales_order_id,
                'sales_order_product_id' => $request->sales_order_product_id,
                'warehouse_id' => $request->warehouse_id,
                'sku' => $request->sku,
                'allocated_quantity' => $request->quantity,
                'sequence' => $lastSequence + 1,
                'status' => 'allocated',
                'notes' => 'Manual allocation'
            ]);

            $warehouseStock->available_quantity -= $request->quantity;
            $warehouseStock->block_quantity += $request->quantity;
            $warehouseStock->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stock allocated successfully',
                'allocation' => $allocation
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * EXAMPLE 6: Complete JSON Response Example
     * 
     * Yeh example dikhata hai ki response kaise aayega
     */
    public function jsonResponseExample()
    {
        // Example successful allocation response
        $exampleResponse = [
            'success' => true,
            'sales_order_id' => 1,
            'allocations' => [
                [
                    'success' => true,
                    'sku' => 'SKU123',
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
                ],
                [
                    'success' => true,
                    'sku' => 'SKU456',
                    'required_quantity' => 15,
                    'total_allocated' => 15,
                    'pending_quantity' => 0,
                    'need_purchase' => false,
                    'allocations' => [
                        [
                            'warehouse_id' => 1,
                            'warehouse_name' => 'Baroda Warehouse 1',
                            'allocated_quantity' => 15,
                            'sequence' => 1,
                            'status' => 'allocated'
                        ]
                    ],
                    'message' => 'Fully allocated from 1 warehouse(s).'
                ]
            ],
            'needs_purchase_order' => true,
            'purchase_order_items' => [
                [
                    'sku' => 'SKU123',
                    'quantity_needed' => 5,
                    'sales_order_product_id' => 1
                ]
            ],
            'message' => 'Order partially allocated. Purchase order required for remaining items.'
        ];

        return response()->json($exampleResponse);
    }
}

