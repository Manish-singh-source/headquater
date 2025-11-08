<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\SalesOrder;
use App\Models\SalesOrderProduct;
use App\Models\WarehouseAllocation;
use App\Models\WarehouseProductIssue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\SimpleExcel\SimpleExcelReader;
use Spatie\SimpleExcel\SimpleExcelWriter;

class PackagingController extends Controller
{
    /**
     * Display list of orders ready for packaging
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $orders = SalesOrder::with('customerGroup')
            ->where('status', 'ready_to_package')
            ->get();

        return view('packagingList.index', compact('orders'));
    }

    /**
     * View a specific sales order with packaging details
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function view($id)
    {
        try {
            $user = Auth::user();
            // Check if user is admin (Super Admin or Admin role, or warehouse_id is null/0)
            $isAdmin = $user->hasRole(['Super Admin', 'Admin']) || !$user->warehouse_id;
            $userWarehouseId = $user->warehouse_id; // Get user's warehouse ID

            // Load sales order with relationships
            $salesOrder = SalesOrder::with([
                'customerGroup',
                'warehouse',
                'orderedProducts.product',
                'orderedProducts.customer',
                'orderedProducts.tempOrder',
                'orderedProducts.warehouseStock.warehouse', // Load warehouse stock with warehouse
                'orderedProducts.warehouseAllocations.warehouse', // Load warehouse allocations
            ])->findOrFail($id);

            // Filter products based on user role and warehouse
            if (!$isAdmin && $userWarehouseId) {
                // For warehouse users: Filter products to show only their warehouse's products
                $filteredProducts = $salesOrder->orderedProducts->filter(function ($product) use ($userWarehouseId) {
                    // Check if product has warehouse allocations (auto-allocation)
                    if ($product->warehouseAllocations && $product->warehouseAllocations->count() > 0) {
                        // Check if any allocation is from user's warehouse
                        return $product->warehouseAllocations->contains('warehouse_id', $userWarehouseId);
                    } else {
                        // Single warehouse allocation: Check warehouse_stock_id
                        if ($product->warehouseStock) {
                            return $product->warehouseStock->warehouse_id == $userWarehouseId;
                        }
                    }
                    return false;
                });

                // Replace orderedProducts with filtered collection
                $salesOrder->setRelation('orderedProducts', $filteredProducts);
            }

            // For admin: Show all products (no filtering needed)

            $facilityNames = $salesOrder->orderedProducts
                ->pluck('customer.facility_name')
                ->filter()
                ->unique()
                ->values();

            // Pass additional data to view
            return view('packagingList.view', compact('salesOrder', 'facilityNames', 'isAdmin', 'userWarehouseId'));
        } catch (\Exception $e) {
            Log::error('Error loading packaging view: ' . $e->getMessage());
            return redirect()->route('packaging.list.index')
                ->with('error', 'Error loading packaging details: ' . $e->getMessage());
        }
    }

    /**
     * Download packaging products as Excel file
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function downloadPackagingProducts(Request $request)
    {
        if (! $request->id) {
            return back()->with('error', 'Please Try Again.');
        }

        $id = $request->id;

        // Create temporary .xlsx file path
        $tempXlsxPath = storage_path('app/received_' . Str::random(8) . '.xlsx');

        // Create writer
        $writer = SimpleExcelWriter::create($tempXlsxPath);

        // Get user info for filtering
        $user = Auth::user();
        // Check if user is admin (Super Admin or Admin role, or warehouse_id is null/0)
        $isAdmin = $user->hasRole(['Super Admin', 'Admin']) || !$user->warehouse_id;
        $userWarehouseId = $user->warehouse_id;

        // Fetch data with relationships
        $salesOrder = SalesOrder::with([
            'customerGroup',
            'warehouse',
            'orderedProducts.product',
            'orderedProducts.customer',
            'orderedProducts.tempOrder',
            'orderedProducts.warehouseStock.warehouse',
            'orderedProducts.warehouseAllocations.warehouse',
        ])
            ->findOrFail($id);

        $facilityNames = collect();
        foreach ($salesOrder->orderedProducts as $order) {
            $facilityNames->push($order->customer->contact_name);
        }
        $facilityNames = $facilityNames->filter()->unique()->values();

        // For warehouse users: Filter products to show only their warehouse's products
        // For admin: Show all products
        $filteredProducts = $salesOrder->orderedProducts;
        if (!$isAdmin && $userWarehouseId) {
            $filteredProducts = $salesOrder->orderedProducts->filter(function ($product) use ($userWarehouseId) {
                if ($product->warehouseAllocations && $product->warehouseAllocations->count() > 0) {
                    return $product->warehouseAllocations->contains('warehouse_id', $userWarehouseId);
                } else {
                    if ($product->warehouseStock) {
                        return $product->warehouseStock->warehouse_id == $userWarehouseId;
                    }
                }
                return false;
            });
        }

        // Add rows
        foreach ($filteredProducts as $order) {

            if (isset($request->facility_name) && $order->tempOrder->facility_name != $request->facility_name) {
                continue;
            }

            // Build warehouse allocation text and warehouse name
            $warehouseAllocation = '';
            $warehouseName = '';
            $hasAllocations = $order->warehouseAllocations && $order->warehouseAllocations->count() > 0;

            if ($hasAllocations) {
                // Auto-allocation: Show warehouse-wise breakdown
                if ($isAdmin) {
                    // Admin sees all warehouses - show "All"
                    $warehouseName = 'All';
                    $allocations = [];
                    foreach ($order->warehouseAllocations->sortBy('sequence') as $allocation) {
                        $allocations[] = ($allocation->warehouse->name ?? 'N/A') . ': ' . $allocation->allocated_quantity;
                    }
                    $warehouseAllocation = implode(', ', $allocations);
                } else {
                    // Warehouse user sees only their warehouse
                    $allocations = [];
                    foreach ($order->warehouseAllocations->where('warehouse_id', $userWarehouseId) as $allocation) {
                        $warehouseName = $allocation->warehouse->name ?? 'N/A';
                        $allocations[] = ($allocation->warehouse->name ?? 'N/A') . ': ' . $allocation->allocated_quantity;
                    }
                    $warehouseAllocation = implode(', ', $allocations);
                }
            } else {
                // Single warehouse allocation
                if ($order->warehouseStock) {
                    $warehouseName = $order->warehouseStock->warehouse->name ?? 'N/A';
                    $warehouseAllocation = ($order->warehouseStock->warehouse->name ?? 'N/A') . ': ' . ($order->tempOrder->block ?? 0);
                } elseif ($order->tempOrder && $order->tempOrder->block > 0 && $isAdmin) {
                    // Fallback: Show block quantity without warehouse name if admin
                    $warehouseName = 'All';
                    $warehouseAllocation = 'Total Blocked: ' . $order->tempOrder->block;
                } else {
                    $warehouseName = 'N/A';
                    $warehouseAllocation = 'N/A';
                }
            }

            // Calculate Total Dispatch Qty based on user role
            $totalDispatchQty = 0;
            if ($hasAllocations) {
                // Auto-allocation case
                if ($isAdmin) {
                    // Admin: Show total from all warehouses
                    $totalDispatchQty = $order->warehouseAllocations->sum('allocated_quantity');
                } else {
                    // Warehouse user: Show only their warehouse's quantity
                    $totalDispatchQty = $order->warehouseAllocations
                        ->where('warehouse_id', $userWarehouseId)
                        ->sum('allocated_quantity');
                }
            } else {
                // Single warehouse case or fallback
                if ($isAdmin) {
                    // Admin: Show full blocked quantity from tempOrder
                    $totalDispatchQty = $order->tempOrder->block ?? 0;
                } else {
                    // Warehouse user: Show only if it's their warehouse
                    if ($order->warehouseStock && $order->warehouseStock->warehouse_id == $userWarehouseId) {
                        $totalDispatchQty = $order->tempOrder->block ?? 0;
                    } else {
                        $totalDispatchQty = 0;
                    }
                }
            }

            // Fallback: If still 0 and admin, use tempOrder->block
            if ($totalDispatchQty == 0 && $isAdmin && isset($order->tempOrder->block)) {
                $totalDispatchQty = $order->tempOrder->block;
            }

            // Get final dispatch quantity from warehouse allocations if available
            $finalDispatchQty = 0;
            if ($hasAllocations) {
                if ($isAdmin) {
                    // Admin: Sum all warehouses' final dispatch quantities
                    $finalDispatchQty = $order->warehouseAllocations->sum('final_dispatched_quantity') ?: 0;
                } else {
                    // Warehouse user: Only their warehouse's final dispatch quantity
                    $finalDispatchQty = $order->warehouseAllocations
                        ->where('warehouse_id', $userWarehouseId)
                        ->sum('final_dispatched_quantity') ?: 0;
                }
            } else {
                // Single warehouse or fallback to sales_order_products table
                $finalDispatchQty = $order->final_dispatched_quantity ?? 0;
            }

            $writer->addRow([
                'Customer Name' => $order->customer->contact_name ?? '',
                'SKU Code' => $order->tempOrder->sku ?? '',
                'Facility Name' => $order->tempOrder->facility_name ?? '',
                'Facility Location' => $order->tempOrder->facility_location ?? '',
                'PO Date' => $order->tempOrder->po_date ?? '',
                'PO Expiry Date' => $order->tempOrder->po_expiry_date ?? '',
                'HSN' => $order->tempOrder->hsn ?? '',
                'Item Code' => $order->tempOrder->item_code ?? '',
                'Description' => $order->tempOrder->description ?? '',
                'GST' => $order->tempOrder->gst ?? '',
                'Basic Rate' => $order->tempOrder->basic_rate ?? '',
                'Net Landing Rate' => $order->tempOrder->net_landing_rate ?? '',
                'MRP' => $order->tempOrder->mrp ?? '',
                'PO Quantity' => $order->tempOrder->po_qty ?? '',
                'Purchase Order Quantity' => $order->tempOrder->purchase_order_quantity ?? '',
                'Warehouse Name' => $warehouseName,
                'Warehouse Allocation' => $warehouseAllocation,
                'Purchase Order No' => $order->tempOrder->po_number ?? '',
                'Total Dispatch Qty' => $totalDispatchQty,
                'Final Dispatch Qty' => $finalDispatchQty,
                'Box Count' => $order->box_count ?? 0,
                'Weight' => $order->weight ?? 0,
                'Issue Units' => '',
                'Issue Reason' => '',
            ]);
        }

        // Close the writer
        $writer->close();

        return response()->download($tempXlsxPath, 'Packaging-Products.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Update packaging products from uploaded Excel file
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePackagingProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pi_excel' => 'required|file|mimes:xlsx,csv,xls',
            'salesOrderId' => 'required|integer|min:1|exists:sales_orders,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $file = $request->file('pi_excel');
        $filepath = $file->getPathname();
        $extension = $file->getClientOriginalExtension();

        DB::beginTransaction();

        try {
            // Get user info
            $user = Auth::user();
            // Check if user is admin (Super Admin or Admin role, or warehouse_id is null/0)
            $isAdmin = $user->hasRole(['Super Admin', 'Admin']) || !$user->warehouse_id;
            $userWarehouseId = $user->warehouse_id;

            $reader = SimpleExcelReader::create($filepath, $extension);
            $rows = $reader->getRows();
            $insertCount = 0;

            foreach ($rows as $record) {
                // Skip empty SKU records
                if (empty($record['SKU Code'])) {
                    continue;
                }

                // Find customer by facility name
                $customer = Customer::where('facility_name', $record['Facility Name'] ?? '')
                    ->first();

                if (!$customer) {
                    continue;
                }

                // Find the sales order product
                $order = SalesOrderProduct::with(['tempOrder', 'warehouseAllocations'])
                    ->where('customer_id', $customer->id)
                    ->where('sales_order_id', $request->salesOrderId)
                    ->where('sku', $record['SKU Code'])
                    ->first();

                if (!$order) {
                    continue;
                }

                // Process the packaging update
                $this->updateSalesOrderProduct(
                    $order,
                    $record,
                    $request->salesOrderId,
                    $isAdmin,
                    $userWarehouseId
                );

                $insertCount++;
            }

            if ($insertCount === 0) {
                DB::rollBack();
                return redirect()->back()->withErrors(['pi_excel' => 'No valid data found in the file.']);
            }

            DB::commit();

            // Log activity
            activity()
                ->causedBy(Auth::user())
                ->withProperties(['sales_order_id' => $request->salesOrderId, 'records_updated' => $insertCount])
                ->log('Packaging products updated');

            return redirect()->route('packaging.list.index')
                ->with('success', 'Packaging products updated successfully. ' . $insertCount . ' records processed.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating packaging products: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Error processing file: ' . $e->getMessage());
        }
    }

    /**
     * Update individual sales order product with packaging details
     *
     * @param SalesOrderProduct $order
     * @param array $record
     * @param int $salesOrderId
     * @param bool $isAdmin
     * @param int|null $userWarehouseId
     * @return void
     */
    private function updateSalesOrderProduct($order, $record, $salesOrderId, $isAdmin, $userWarehouseId)
    {
        $finalDispatchQty = (int)($record['Final Dispatch Qty'] ?? $order->dispatched_quantity);
        $boxCount = (int)($record['Box Count'] ?? 0);
        $weight = (int)($record['Weight'] ?? 0);
        $issueUnits = (int)($record['Issue Units'] ?? 0);
        $issueReason = $record['Issue Reason'] ?? '';

        // Ensure final dispatch qty is not negative
        if ($finalDispatchQty < 0) {
            $finalDispatchQty = 0;
        }

        // Check if product has warehouse allocations (auto-allocation)
        $hasAllocations = $order->warehouseAllocations && $order->warehouseAllocations->count() > 0;

        if ($hasAllocations) {
            // Update warehouse allocations table
            if ($isAdmin) {
                // Admin: Update all warehouse allocations proportionally or set total
                // For simplicity, we'll update the final_dispatched_quantity as total
                // You can distribute proportionally if needed
                $totalAllocated = $order->warehouseAllocations->sum('allocated_quantity');

                foreach ($order->warehouseAllocations as $allocation) {
                    if ($totalAllocated > 0) {
                        // Distribute final dispatch quantity proportionally
                        $proportion = $allocation->allocated_quantity / $totalAllocated;
                        $allocation->final_dispatched_quantity = (int)($finalDispatchQty * $proportion);
                        $allocation->box_count = (int)($boxCount * $proportion);
                        $allocation->weight = (int)($weight * $proportion);
                    } else {
                        $allocation->final_dispatched_quantity = 0;
                        $allocation->box_count = 0;
                        $allocation->weight = 0;
                    }
                    $allocation->save();
                }
            } else {
                // Warehouse user: Update only their warehouse allocation
                $userAllocation = $order->warehouseAllocations
                    ->where('warehouse_id', $userWarehouseId)
                    ->first();

                if ($userAllocation) {
                    $userAllocation->final_dispatched_quantity = $finalDispatchQty;
                    $userAllocation->box_count = $boxCount;
                    $userAllocation->weight = $weight;
                    $userAllocation->save();
                }
            }

            // Update sales_order_products table with aggregated values
            if ($isAdmin) {
                $order->final_dispatched_quantity = $finalDispatchQty;
                $order->box_count = $boxCount;
                $order->weight = $weight;
            } else {
                // For warehouse users, aggregate from all allocations
                $order->final_dispatched_quantity = $order->warehouseAllocations->sum('final_dispatched_quantity');
                $order->box_count = $order->warehouseAllocations->sum('box_count');
                $order->weight = $order->warehouseAllocations->sum('weight');
            }
        } else {
            // Single warehouse allocation - update sales_order_products table directly
            $order->final_dispatched_quantity = $finalDispatchQty;
            $order->box_count = $boxCount;
            $order->weight = $weight;
        }

        // Update status
        $order->status = 'packaged';

        // Handle shortage: dispatched > final
        if ($order->dispatched_quantity > $finalDispatchQty) {
            $shortageQuantity = $order->dispatched_quantity - $finalDispatchQty;

            $order->issue_item = $issueUnits ?? $shortageQuantity;
            $order->issue_reason = 'Shortage';
            $order->issue_description = $issueReason ?? 'Shortage products';

            $order->save();

            // Create warehouse product issue record (only once, not per warehouse)
            if (!$hasAllocations || $isAdmin) {
                WarehouseProductIssue::create([
                    'customer_id' => $order->customer_id,
                    'sales_order_id' => $salesOrderId,
                    'sales_order_product_id' => $order->id,
                    'sku' => $order->tempOrder?->sku ?? '',
                    'issue_item' => $shortageQuantity,
                    'issue_reason' => 'Shortage',
                    'issue_description' => 'Shortage products',
                    'issue_from' => 'warehouse',
                    'issue_status' => 'pending',
                ]);
            }
        }
        // Handle exceed: dispatched < final
        elseif ($order->dispatched_quantity < $finalDispatchQty) {
            $exceedQuantity = $finalDispatchQty - $order->dispatched_quantity;

            $order->issue_item = $issueUnits ?? $exceedQuantity;
            $order->issue_reason = 'Exceed';
            $order->issue_description = $issueReason ?? 'Exceed products';

            $order->save();
        }
        // Handle exact match: dispatched == final
        else {
            $order->save();
        }
    }
}
