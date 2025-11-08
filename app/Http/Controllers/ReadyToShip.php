<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
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
    public function index()
    {
        try {
            $orders = SalesOrder::where('status', 'ready_to_ship')
                ->with('customerGroup')
                ->latest()
                ->paginate(15);

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
    public function view($id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:sales_orders,id',
            ]);

            if ($validator->fails()) {
                return redirect()->route('ready.to.ship.index')
                    ->with('error', 'Invalid order ID.');
            }

            $order = SalesOrder::with('orderedProducts')
                ->where('status', 'ready_to_ship')
                ->findOrFail($id);

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

            return view('readyToShip.view', compact('customerInfo', 'order'));
        } catch (\Exception $e) {
            return redirect()->route('ready.to.ship.index')
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
                'orderedProducts.warehouseStock.warehouse',
                'orderedProducts.warehouseAllocations.warehouse',
            ])
                ->where('status', 'ready_to_ship')
                ->with(['orderedProducts' => function ($q) use ($c_id) {
                    $q->where('customer_id', (int)$c_id);
                }])
                ->findOrFail($id);

            if (!$salesOrder) {
                return redirect()->back()->with('error', 'Order not found.');
            }

            // Filter products based on user role and warehouse
            if (!$isAdmin && $userWarehouseId) {
                // For warehouse users: Filter products to show only their warehouse's products
                $filteredProducts = $salesOrder->orderedProducts->filter(function ($product) use ($userWarehouseId, $salesOrder) {
                    // Check if product has warehouse allocations (auto-allocation)
                    if ($product->warehouseAllocations && $product->warehouseAllocations->count() > 0) {
                        // Check if any allocation is from user's warehouse
                        return $product->warehouseAllocations->contains('warehouse_id', $userWarehouseId);
                    } else {
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
                        // Multiple warehouses
                        foreach ($order->warehouseAllocations as $allocation) {
                            $displayProducts[] = [
                                'order' => $order,
                                'warehouse_name' => $allocation->warehouse->name ?? 'N/A',
                                'allocated_quantity' => $allocation->allocated_quantity,
                                'final_dispatched_quantity' => $allocation->final_dispatched_quantity ?? 0,
                            ];
                        }
                    } else {
                        // Single warehouse or no allocation
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
                        ];
                    }
                }
            } else {
                // For non-super admin, keep original structure
                foreach ($salesOrder->orderedProducts as $order) {
                    $hasAllocations = $order->warehouseAllocations && $order->warehouseAllocations->count() > 0;

                    if ($hasAllocations && !$isAdmin && $userWarehouseId) {
                        // Warehouse user: Show only their warehouse's data
                        $userAllocation = $order->warehouseAllocations->where('warehouse_id', $userWarehouseId)->first();
                        if ($userAllocation) {
                            $displayProducts[] = [
                                'order' => $order,
                                'warehouse_name' => $userAllocation->warehouse->name ?? 'N/A',
                                'allocated_quantity' => $userAllocation->allocated_quantity,
                                'final_dispatched_quantity' => $userAllocation->final_dispatched_quantity ?? 0,
                            ];
                        }
                    } else {
                        // Admin or single warehouse
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
                            'final_dispatched_quantity' => $hasAllocations
                                ? $order->warehouseAllocations->sum('final_dispatched_quantity')
                                : ($order->final_dispatched_quantity ?? 0),
                        ];
                    }
                }
            }

            $customerInfo = Customer::findOrFail($c_id);

            $invoice = Invoice::where('customer_id', $c_id)
                ->where('sales_order_id', $id)
                ->first();

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
     * Display pending vendor product returns
     *
     * @return \Illuminate\View\View
     */
    public function returnAccept()
    {
        try {
            $vendorOrders = VendorReturnProduct::with('vendorPIProduct')
                ->where('return_status', 'pending')
                ->latest()
                ->paginate(15);

            return view('return-or-accept', compact('vendorOrders'));
        } catch (\Exception $e) {
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

                return redirect()->back()
                    ->with('success', 'Vendor return products accepted successfully. Stock updated.');
            } else {
                DB::rollBack();

                return redirect()->back()
                    ->with('error', 'Warehouse stock not found for SKU: ' . $vendorReturnProduct->sku);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
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

            return redirect()->back()
                ->with('success', 'Vendor return products marked as returned successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error returning vendor products: ' . $e->getMessage());
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

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error processing bulk accept: ' . $e->getMessage());
        }
    }
}
