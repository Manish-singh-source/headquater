<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetails;
use App\Models\Customer;
use App\Models\SalesOrder;
use App\Models\ProductIssue;
use App\Models\CustomerReturn;
use App\Models\WarehouseStock;
use App\Models\VendorPIProduct;
use App\Models\SalesOrderProduct;
use App\Models\VendorReturnProduct;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReadyToShip extends Controller
{
    /**
     * Display list of orders ready to ship
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            // Check if user is admin (Super Admin or Admin role, or warehouse_id is null/0)
            $isAdmin = $user->hasRole(['Super Admin', 'Admin']) || !$user->warehouse_id;
            $userWarehouseId = $user->warehouse_id;

            $status = $request->query('status', 'all');
            $orders = SalesOrder::with('customerGroup')->with('orderedProducts.warehouseAllocations', function ($q) use ($isAdmin, $userWarehouseId) {
                if (!$isAdmin) {
                    $q->where('warehouse_id', $userWarehouseId);
                }
                $q->where('product_status', 'completed');
            })
                ->whereHas('orderedProducts.warehouseAllocations', function ($q) use ($isAdmin, $userWarehouseId) {
                    if (!$isAdmin) {
                        $q->where('warehouse_id', $userWarehouseId);
                    }
                    $q->where('product_status', 'completed');
                })
                ->get();

            // dd($orders);

            return view('readyToShip.index', compact('orders'));






            $query = SalesOrder::where('status', 'ready_to_ship')
                ->with('customerGroup');

            // For warehouse users, only show orders that have products allocated to their warehouse
            if (!$isAdmin && $userWarehouseId) {
                $query->whereHas('orderedProducts', function ($q) use ($userWarehouseId) {
                    $q->where(function ($subQuery) use ($userWarehouseId) {
                        // Check for explicit warehouse allocations
                        $subQuery->whereHas('warehouseAllocations', function ($allocQuery) use ($userWarehouseId) {
                            $allocQuery->where('warehouse_id', $userWarehouseId);
                        })
                            // Or check for warehouse stock with blocked quantity
                            ->orWhereHas('warehouseStock', function ($stockQuery) use ($userWarehouseId) {
                                $stockQuery->where('warehouse_id', $userWarehouseId);
                            })
                            // Or check for auto-allocated products with blocked quantity in user's warehouse
                            ->orWhere(function ($autoAllocQuery) use ($userWarehouseId) {
                                $autoAllocQuery->whereNull('warehouse_stock_id')
                                    ->where('sku', '!=', '')
                                    ->whereExists(function ($existsQuery) use ($userWarehouseId) {
                                        $existsQuery->selectRaw('1')
                                            ->from('warehouse_stocks')
                                            ->whereColumn('warehouse_stocks.sku', 'sales_order_products.sku')
                                            ->where('warehouse_stocks.warehouse_id', $userWarehouseId)
                                            ->where('warehouse_stocks.block_quantity', '>', 0);
                                    });
                            });
                    });
                });
            }

            $orders = $query->latest()->paginate(15);

            return view('readyToShip.index', compact('orders'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error retrieving orders: ' . $e->getMessage());
        }
    }

    /**
     * View order with customer information
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function view($id, Request $request)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:sales_orders,id',
            ]);

            if ($validator->fails()) {
                return redirect()->route('ready.to.ship.index')
                    ->with('error', 'Invalid order ID.');
            }

            $user = Auth::user();
            // Check if user is admin (Super Admin or Admin role, or warehouse_id is null/0)
            $isAdmin = $user->hasRole(['Super Admin', 'Admin']) || !$user->warehouse_id;
            $userWarehouseId = $user->warehouse_id;

            $status = $request->query('status', 'all');

            $order = SalesOrder::with('orderedProducts')
                ->with('orderedProducts.warehouseAllocations', function ($q) use ($isAdmin, $userWarehouseId) {
                    if (!$isAdmin) {
                        $q->where('warehouse_id', $userWarehouseId);
                    }
                    $q->where('product_status', 'completed');
                })
                ->whereHas('orderedProducts.warehouseAllocations', function ($q) use ($isAdmin, $userWarehouseId) {
                    if (!$isAdmin) {
                        $q->where('warehouse_id', $userWarehouseId);
                    }
                    $q->where('product_status', 'completed');
                })
                ->find($id);

            // count warehouseAllocations 
            $warehouseAllocationsCount = $order->orderedProducts->sum(function ($product) {
                return $product->warehouseAllocations->count();
            });

            if (!$order) {
                return redirect()->route('ready.to.ship.index')
                    ->with('error', 'Order not found or not ready to ship.');
            }

            // Get unique customers for this order
            $facilityNames = SalesOrderProduct::with('customer')
                ->where('sales_order_id', $id)
                ->get()
                ->pluck('customer')
                ->filter()
                ->unique('id');

            $customerIds = $facilityNames->pluck('id')->toArray();

            $customerInfo = Customer::with('groupInfo.customerGroup')
                ->withCount('orders')
                ->whereIn('id', $customerIds)
                ->get();

            return view('readyToShip.view', compact('customerInfo', 'order', 'warehouseAllocationsCount'));
        } catch (\Exception $e) {
            return redirect()->route('readyToShip.index')
                ->with('error', 'Error loading order: ' . $e->getMessage());
        }
    }

    /**
     * View detailed order information for a specific customer
     *
     * @param int $id
     * @param int $c_id
     * @return \Illuminate\View\View
     */
    public function viewDetail($id, $c_id)
    {
        try {
            $validator = Validator::make([
                'id' => $id,
                'c_id' => $c_id,
            ], [
                'id' => 'required|integer|exists:sales_orders,id',
                'c_id' => 'required|integer|exists:customers,id',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Invalid order or customer ID.');
            }

            $user = Auth::user();
            // Check if user is admin (Super Admin or Admin role, or warehouse_id is null/0)
            $isSuperAdmin = $user->hasRole('Super Admin');
            $isAdmin = $user->hasRole(['Super Admin', 'Admin']) || !$user->warehouse_id;
            $userWarehouseId = $user->warehouse_id;
            $salesOrder = SalesOrder::with([
                'customerGroup',
                'warehouse',
                'orderedProducts.product',
                'orderedProducts.tempOrder',
                'orderedProducts.customer',
                'orderedProducts.warehouseAllocations.warehouse',
            ])

                // Filter orderedProducts by customer
                ->with(['orderedProducts' => function ($q) use ($c_id) {
                    if(!$isAdmin){
                        $q->whereHas('warehouseAllocations', function($allocQuery) use ($userWarehouseId) {
                            $allocQuery->where('warehouse_id', $userWarehouseId);
                        });
                    }
                    $q->where('customer_id', $c_id);
                }])

                // Filter warehouse allocations inside orderedProducts
                ->with(['orderedProducts.warehouseAllocations' => function ($q) use ($isAdmin, $userWarehouseId) {
                    if (!$isAdmin) {
                        $q->where('warehouse_id', $userWarehouseId);
                    }
                    $q->where('product_status', 'completed');
                }])

                // Ensure ONLY those orders that actually have allocations are fetched
                ->whereHas('orderedProducts', function ($q) use ($c_id) {
                    $q->where('customer_id', $c_id);
                })

                ->whereHas('orderedProducts.warehouseAllocations', function ($q) use ($isAdmin, $userWarehouseId) {
                    if (!$isAdmin) {
                        $q->where('warehouse_id', $userWarehouseId);
                    }
                    $q->where('product_status', 'completed');
                })

                ->findOrFail($id);


            dd($salesOrder);

            if (!$salesOrder) {
                return redirect()->back()->with('error', 'Order not found.');
            }

            // Filter products based on user role and warehouse
            // Skip warehouse filtering for 'ready_to_ship' orders as they are already packaged
            if (!$isAdmin && $userWarehouseId) {
                // For warehouse users: Filter products to show only their warehouse's products
                $filteredProducts = $salesOrder->orderedProducts->filter(function ($product) use ($userWarehouseId, $salesOrder) {
                    // Check if product has warehouse allocations (auto-allocation)
                    if ($product->warehouseAllocations && $product->warehouseAllocations->count() > 0) {
                        // Check if any allocation is from user's warehouse
                        return $product->warehouseAllocations->contains('warehouse_id', $userWarehouseId);
                    } else {
                        // Check warehouse stock for blocked quantity
                        $warehouseStock = \App\Models\WarehouseStock::where('sku', $product->sku)
                            ->where('warehouse_id', $userWarehouseId)
                            ->where('block_quantity', '>', 0)
                            ->first();
                        if ($warehouseStock) {
                            return true;
                        }

                        // Single warehouse allocation: Check warehouse_stock_id
                        if ($product->warehouseStock) {
                            return $product->warehouseStock->warehouse_id == $userWarehouseId;
                        }
                        // If warehouseStock is null, check sales order's warehouse_id
                        elseif ($salesOrder->warehouse_id) {
                            return $salesOrder->warehouse_id == $userWarehouseId;
                        }
                    }
                    return false;
                });

                // Replace orderedProducts with filtered collection
                $salesOrder->setRelation('orderedProducts', $filteredProducts);
            }

            // Prepare display products with warehouse-wise breakdown
            $displayProducts = [];
            if ($isSuperAdmin) {
                // For super admin, create separate rows for each warehouse allocation
                foreach ($salesOrder->orderedProducts as $order) {
                    $hasAllocations = $order->warehouseAllocations && $order->warehouseAllocations->count() > 0;

                    if ($hasAllocations) {
                        // Has warehouse allocations (both single and multi-warehouse)
                        foreach ($order->warehouseAllocations as $allocation) {
                            $displayProducts[] = [
                                'order' => $order,
                                'warehouse_name' => $allocation->warehouse->name ?? 'N/A',
                                'allocated_quantity' => $allocation->allocated_quantity,
                                'final_dispatched_quantity' => $allocation->final_dispatched_quantity ?? 0,
                                'allocation_id' => $allocation->id,
                            ];
                        }
                    } else {
                        // Fallback: No allocation record (legacy data)
                        $warehouseName = 'N/A';

                        // Try to get warehouse name from warehouseStock relationship
                        if ($order->warehouseStock && $order->warehouseStock->warehouse) {
                            $warehouseName = $order->warehouseStock->warehouse->name;
                        }
                        // If not found, try to get from warehouse_id in sales_order
                        elseif ($salesOrder->warehouse) {
                            $warehouseName = $salesOrder->warehouse->name;
                        }

                        $allocatedQty = $order->tempOrder->block ?? 0;
                        $displayProducts[] = [
                            'order' => $order,
                            'warehouse_name' => $warehouseName,
                            'allocated_quantity' => $allocatedQty,
                            'final_dispatched_quantity' => $order->final_dispatched_quantity ?? 0,
                            'allocation_id' => null,
                        ];
                    }
                }
            } else {
                // For non-super admin (warehouse users and admins)
                foreach ($salesOrder->orderedProducts as $order) {
                    $hasAllocations = $order->warehouseAllocations && $order->warehouseAllocations->count() > 0;

                    if ($hasAllocations) {
                        if (!$isAdmin && $userWarehouseId) {
                            // Warehouse user: Show only their warehouse's allocations
                            $userAllocations = $order->warehouseAllocations->filter(function ($allocation) use ($userWarehouseId) {
                                return $allocation->warehouse_id == $userWarehouseId;
                            });

                            foreach ($userAllocations as $allocation) {
                                $displayProducts[] = [
                                    'order' => $order,
                                    'warehouse_name' => $allocation->warehouse->name ?? 'N/A',
                                    'allocated_quantity' => $allocation->allocated_quantity,
                                    'final_dispatched_quantity' => $allocation->final_dispatched_quantity ?? 0,
                                    'allocation_id' => $allocation->id,
                                ];
                            }
                        } else {
                            // Admin: Show all allocations
                            foreach ($order->warehouseAllocations as $allocation) {
                                $displayProducts[] = [
                                    'order' => $order,
                                    'warehouse_name' => $allocation->warehouse->name ?? 'N/A',
                                    'allocated_quantity' => $allocation->allocated_quantity,
                                    'final_dispatched_quantity' => $allocation->final_dispatched_quantity ?? 0,
                                    'allocation_id' => $allocation->id,
                                ];
                            }
                        }
                    } else {
                        // Fallback: No allocation record (legacy data)
                        $warehouseName = 'N/A';

                        if ($isAdmin) {
                            $warehouseName = 'All';
                        } else {
                            // Try to get warehouse name from warehouseStock relationship
                            if ($order->warehouseStock && $order->warehouseStock->warehouse) {
                                $warehouseName = $order->warehouseStock->warehouse->name;
                            }
                            // If not found, try to get from warehouse_id in sales_order
                            elseif ($salesOrder->warehouse) {
                                $warehouseName = $salesOrder->warehouse->name;
                            }
                        }

                        $displayProducts[] = [
                            'order' => $order,
                            'warehouse_name' => $warehouseName,
                            'allocated_quantity' => null,
                            'final_dispatched_quantity' => $order->final_dispatched_quantity ?? 0,
                            'allocation_id' => null,
                        ];
                    }
                }
            }

            $customerInfo = Customer::findOrFail($c_id);

            // Get invoice based on user role
            $invoiceQuery = Invoice::where('customer_id', $c_id)
                ->where('sales_order_id', $id);

            if (!$isSuperAdmin && !$isAdmin && $userWarehouseId) {
                // Warehouse users can only see invoices for their warehouse
                $invoiceQuery->where('warehouse_id', $userWarehouseId);
            }

            $invoice = $invoiceQuery->first();

            return view('readyToShip.view-detail', compact('salesOrder', 'customerInfo', 'invoice', 'displayProducts', 'isAdmin', 'isSuperAdmin', 'userWarehouseId'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error loading order details: ' . $e->getMessage());
        }
    }

    /**
     * Display issues/shortage products
     *
     * @return \Illuminate\View\View
     */
    public function issuesProducts()
    {
        try {
            $vendorOrders = ProductIssue::with(['order', 'product', 'purchaseOrder', 'tempOrder'])
                ->latest()
                ->paginate(15);

            return view('exceed-shortage', compact('vendorOrders'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error retrieving product issues: ' . $e->getMessage());
        }
    }

    /**
     * Display vendor product returns with optional status filter
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function returnAccept(Request $request)
    {
        try {
            // Default to 'all' so the page shows all records when no status is provided
            $status = $request->get('status', 'all');

            $query = VendorReturnProduct::with('vendorPIProduct.product');

            // Filter by status if not 'all'
            if ($status !== 'all') {
                $query->where('return_status', $status);
            }

            $vendorOrders = $query->latest()->get();

            // If AJAX request, return only the table HTML
            if ($request->ajax()) {
                // Pass the current status to the partial so action links can preserve the active tab
                return response()->json([
                    'success' => true,
                    'html' => view('partials.vendor-return-table', compact('vendorOrders', 'status'))->render()
                ]);
            }

            return view('return-or-accept', compact('vendorOrders', 'status'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error retrieving vendor returns: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error retrieving vendor returns: ' . $e->getMessage());
        }
    }

    /**
     * Accept vendor returned products and update warehouse stock
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function acceptVendorProducts($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:vendor_return_products,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Invalid return ID.');
        }

        DB::beginTransaction();

        try {
            $vendorReturnProduct = VendorReturnProduct::lockForUpdate()->findOrFail($id);

            if ($vendorReturnProduct->return_status !== 'pending') {
                return redirect()->back()
                    ->with('error', 'This return has already been processed.');
            }

            $oldStatus = $vendorReturnProduct->return_status;

            // Update return status
            $vendorReturnProduct->return_status = 'accepted';
            $vendorReturnProduct->save();

            // Update warehouse stock
            $warehouseStock = WarehouseStock::lockForUpdate()
                ->where('sku', $vendorReturnProduct->sku)
                ->first();

            if ($warehouseStock) {
                $returnQty = (int)($vendorReturnProduct->return_quantity ?? 0);

                $warehouseStock->available_quantity += $returnQty;
                $warehouseStock->original_quantity += $returnQty;
                $warehouseStock->save();

                // Log activity
                activity()
                    ->performedOn($vendorReturnProduct)
                    ->causedBy(Auth::user())
                    ->withProperties([
                        'old_status' => $oldStatus,
                        'new_status' => 'accepted',
                        'sku' => $vendorReturnProduct->sku,
                        'quantity' => $returnQty,
                    ])
                    ->event('accepted')
                    ->log('Vendor return products accepted');

                // Create notification
                NotificationService::warehouseProductAdded(
                    'Vendor Return: ' . $vendorReturnProduct->sku,
                    $returnQty
                );

                DB::commit();

                return redirect()->route('return.accept', ['status' => request()->get('status', 'all')])
                    ->with('success', 'Vendor return products accepted successfully. Stock updated.');
            } else {
                DB::rollBack();

                return redirect()->route('return.accept', ['status' => request()->get('status', 'all')])
                    ->with('error', 'Warehouse stock not found for SKU: ' . $vendorReturnProduct->sku);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('return.accept', ['status' => request()->get('status', 'all')])
                ->with('error', 'Error accepting vendor products: ' . $e->getMessage());
        }
    }

    /**
     * Return vendor products back to vendor
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function returnVendorProducts($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:vendor_return_products,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Invalid return ID.');
        }

        DB::beginTransaction();

        try {
            $vendorReturnProduct = VendorReturnProduct::lockForUpdate()->findOrFail($id);

            if ($vendorReturnProduct->return_status !== 'pending') {
                return redirect()->back()
                    ->with('error', 'This return has already been processed.');
            }

            $oldStatus = $vendorReturnProduct->return_status;

            // Update return status
            $vendorReturnProduct->return_status = 'returned';
            $vendorReturnProduct->save();

            DB::commit();

            // Log activity
            activity()
                ->performedOn($vendorReturnProduct)
                ->causedBy(Auth::user())
                ->withProperties([
                    'old_status' => $oldStatus,
                    'new_status' => 'returned',
                    'sku' => $vendorReturnProduct->sku,
                    'quantity' => $vendorReturnProduct->return_quantity,
                ])
                ->event('returned')
                ->log('Vendor return products marked as returned');

            return redirect()->route('return.accept', ['status' => request()->get('status', 'all')])
                ->with('success', 'Vendor return products marked as returned successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('return.accept', ['status' => request()->get('status', 'all')])
                ->with('error', 'Error returning vendor products: ' . $e->getMessage());
        }
    }

    /**
     * Generate warehouse-specific invoice
     *
     * @param int $orderId
     * @param int $customerId
     * @param int $warehouseId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generateWarehouseInvoice($orderId, $customerId, $warehouseId)
    {
        try {
            $user = Auth::user();
            $isSuperAdmin = $user->hasRole('Super Admin');
            $isAdmin = $user->hasRole(['Super Admin', 'Admin']) || !$user->warehouse_id;

            // Validate permissions - Super Admin can generate for any warehouse, warehouse users only for their warehouse
            if (!$isSuperAdmin && !$isAdmin && $user->warehouse_id != $warehouseId && $warehouseId != 0) {
                return redirect()->back()->with('error', 'You can only generate invoices for your warehouse.');
            }

            $salesOrder = SalesOrder::with([
                'customerGroup',
                'orderedProducts.product',
                'orderedProducts.tempOrder',
                'orderedProducts.customer',
                'orderedProducts.warehouseStock.warehouse',
                'orderedProducts.warehouseAllocations.warehouse',
            ])
                ->where('status', 'ready_to_ship')
                ->findOrFail($orderId);

            // Get products for this warehouse
            $warehouseProducts = $salesOrder->orderedProducts->filter(function ($product) use ($customerId, $warehouseId) {
                if ($product->customer_id != $customerId) {
                    return false;
                }

                // If warehouseId is 0 (single warehouse case), include all products
                if ($warehouseId == 0) {
                    return true;
                }

                // Check warehouse allocations
                if ($product->warehouseAllocations && $product->warehouseAllocations->count() > 0) {
                    return $product->warehouseAllocations->contains('warehouse_id', $warehouseId);
                }

                // Check warehouse stock
                if ($product->warehouseStock) {
                    return $product->warehouseStock->warehouse_id == $warehouseId;
                }

                return false;
            });

            if ($warehouseProducts->isEmpty()) {
                return redirect()->back()->with('error', 'No products found for this warehouse.');
            }

            DB::beginTransaction();

            // Generate invoice number
            $yearMonth = date('Ym');
            $lastInvoice = Invoice::where('invoice_number', 'LIKE', "INV-{$yearMonth}-%")
                ->orderBy('id', 'desc')
                ->first();

            $timestamp = time();

            if ($lastInvoice) {
                $lastNumber = (int) substr($lastInvoice->invoice_number, -3);
                $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '001';
            }

            $invoiceNumber = 'INV-' . $timestamp . '-' . str_pad($newNumber + 1, 4, '0', STR_PAD_LEFT);

            // Calculate totals
            $subtotal = 0;
            $totalTax = 0;
            $totalDiscount = 0;

            foreach ($warehouseProducts as $product) {
                $quantity = 0;
                $unitPrice = $product->tempOrder->mrp ?? 0;

                // Get quantity from warehouse allocation or final dispatched
                if ($product->warehouseAllocations && $product->warehouseAllocations->count() > 0) {
                    $allocation = $product->warehouseAllocations->where('warehouse_id', $warehouseId)->first();
                    $quantity = $allocation ? $allocation->final_dispatched_quantity ?? $allocation->allocated_quantity : 0;
                } else {
                    $quantity = $product->final_dispatched_quantity ?? 0;
                }

                $amount = $quantity * $unitPrice;
                $discount = 0; // Can be modified based on requirements
                $tax = 0; // Can be modified based on requirements

                $subtotal += $amount;
                $totalDiscount += $discount;
                $totalTax += $tax;
            }

            $totalAmount = $subtotal - $totalDiscount + $totalTax;

            // Create invoice
            $invoice = Invoice::create([
                'warehouse_id' => $warehouseId,
                'invoice_number' => $invoiceNumber,
                'customer_id' => $customerId,
                'sales_order_id' => $orderId,
                'invoice_date' => now(),
                'po_number' => $salesOrder->po_number,
                'po_date' => $salesOrder->po_date,
                'subtotal' => $subtotal,
                'tax_amount' => $totalTax,
                'discount_amount' => $totalDiscount,
                'round_off' => 0,
                'total_amount' => $totalAmount,
                'paid_amount' => 0,
                'balance_due' => $totalAmount,
                'payment_mode' => null,
                'payment_status' => 'unpaid',
                'invoice_type' => 'sales_order',
                'invoice_item_type' => 'product',
                'notes' => "Warehouse-specific invoice for " . \App\Models\Warehouse::find($warehouseId)->name,
            ]);

            // Create invoice details
            foreach ($warehouseProducts as $product) {
                $quantity = 0;
                $unitPrice = $product->tempOrder->mrp ?? 0;

                if ($product->warehouseAllocations && $product->warehouseAllocations->count() > 0) {
                    $allocation = $product->warehouseAllocations->where('warehouse_id', $warehouseId)->first();
                    $quantity = $allocation ? $allocation->final_dispatched_quantity ?? $allocation->allocated_quantity : 0;
                } else {
                    $quantity = $product->final_dispatched_quantity ?? 0;
                }

                $amount = $quantity * $unitPrice;

                InvoiceDetails::create([
                    'invoice_id' => $invoice->id,
                    'warehouse_id' => $warehouseId,
                    'product_id' => $product->product_id,
                    'sales_order_product_id' => $product->id,
                    'hsn' => $product->tempOrder->hsn ?? null,
                    'quantity' => $quantity,
                    'box_count' => $product->box_count ?? null,
                    'weight' => $product->weight ?? null,
                    'unit_price' => $unitPrice,
                    'discount' => 0,
                    'amount' => $amount,
                    'tax' => 0,
                    'total_price' => $amount,
                    'description' => $product->tempOrder->description ?? null,
                ]);
            }

            DB::commit();

            activity()->performedOn($invoice)->causedBy($user)->log("Warehouse-specific invoice generated for Order #{$orderId}, Warehouse #{$warehouseId}");

            return redirect()->route('invoices.view', $orderId)->with('success', 'Warehouse invoice generated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error generating warehouse invoice: ' . $e->getMessage());
        }
    }

    /**
     * Bulk accept vendor returns
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkAcceptVendorProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:vendor_return_products,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Invalid return IDs selected.');
        }

        DB::beginTransaction();

        try {
            $ids = $request->ids;
            $successCount = 0;
            $errorCount = 0;

            foreach ($ids as $id) {
                $vendorReturnProduct = VendorReturnProduct::lockForUpdate()->find($id);

                if (!$vendorReturnProduct || $vendorReturnProduct->return_status !== 'pending') {
                    $errorCount++;
                    continue;
                }

                $vendorReturnProduct->return_status = 'accepted';
                $vendorReturnProduct->save();

                $warehouseStock = WarehouseStock::where('sku', $vendorReturnProduct->sku)->first();

                if ($warehouseStock) {
                    $returnQty = (int)($vendorReturnProduct->return_quantity ?? 0);
                    $warehouseStock->available_quantity += $returnQty;
                    $warehouseStock->original_quantity += $returnQty;
                    $warehouseStock->save();

                    $successCount++;
                } else {
                    $errorCount++;
                }
            }

            DB::commit();

            $message = "Accepted: {$successCount} return(s)";
            if ($errorCount > 0) {
                $message .= ", Errors: {$errorCount}";
            }

            return redirect()->route('return.accept', ['status' => request()->get('status', 'all')])->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('return.accept', ['status' => request()->get('status', 'all')])
                ->with('error', 'Error processing bulk accept: ' . $e->getMessage());
        }
    }
}
