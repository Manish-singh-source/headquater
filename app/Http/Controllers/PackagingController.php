<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\SalesOrder;
use App\Models\SalesOrderProduct;
use App\Models\User;
use App\Models\WarehouseAllocation;
use App\Models\WarehouseProductIssue;
use App\Services\NotificationService;
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
    public function index(Request $request)
    {

        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('Super Admin');
        $isAdmin = $user->hasRole(['Super Admin', 'Admin']) || ! $user->warehouse_id;
        $userWarehouseId = $user->warehouse_id;

        $status = $request->query('status', 'all');
        $orders = SalesOrder::with('customerGroup');
        if ($status === 'all') {
            $orders->whereIn('status', ['ready_to_package', 'partial_packaged', 'all_packaged', 'packaged']);
        } elseif ($status === 'ready_to_package') {
            $orders->where('status', 'ready_to_package');
        } elseif ($status === 'partial_packaged') {
            $orders->where('status', 'partial_packaged');
        } elseif ($status === 'all_packaged') {
            $orders->where('status', 'all_packaged');
        }

        $orders->with('orderedProducts.warehouseAllocations', function ($query) use ($userWarehouseId, $isAdmin) {
            if (! $isAdmin) {
                $query->where('warehouse_id', $userWarehouseId);
            }
        });
        
        $orders->withCount([
            'orderedProducts as packaged_warehouse_alloc_count' => function ($q) use ($userWarehouseId, $isAdmin) {
                if (! $isAdmin) {
                    $q->join('warehouse_allocations', 'sales_order_products.id', '=', 'warehouse_allocations.sales_order_product_id')
                        ->where('warehouse_allocations.status', 'packaged')->where('warehouse_allocations.warehouse_id', $userWarehouseId);
                } else {
                    $q->join('warehouse_allocations', 'sales_order_products.id', '=', 'warehouse_allocations.sales_order_product_id')
                        ->where('warehouse_allocations.status', 'packaged');
                }
            },
            'orderedProducts as approval_pending_warehouse_alloc_count' => function ($q) use ($userWarehouseId, $isAdmin) {
                if (! $isAdmin) {
                    $q->join('warehouse_allocations', 'sales_order_products.id', '=', 'warehouse_allocations.sales_order_product_id')
                        ->where('warehouse_allocations.status', 'approval_pending')->where('warehouse_allocations.warehouse_id', $userWarehouseId);
                } else {
                    $q->join('warehouse_allocations', 'sales_order_products.id', '=', 'warehouse_allocations.sales_order_product_id')
                        ->where('warehouse_allocations.status', 'approval_pending');
                }
            },
            'orderedProducts as partially_shipped_warehouse_alloc_count' => function ($q) use ($userWarehouseId, $isAdmin) {
                if (! $isAdmin) {
                    $q->join('warehouse_allocations', 'sales_order_products.id', '=', 'warehouse_allocations.sales_order_product_id')
                        ->where('warehouse_allocations.status', 'ready_to_ship')->where('warehouse_allocations.warehouse_id', $userWarehouseId);
                } else {
                    $q->join('warehouse_allocations', 'sales_order_products.id', '=', 'warehouse_allocations.sales_order_product_id')
                        ->where('warehouse_allocations.status', 'ready_to_ship');
                }
            },
        ]);

        $orders->withCount([
            'orderedProducts as warehouse_alloc_count' => function ($q) use ($userWarehouseId, $isAdmin) {
                if (! $isAdmin) {
                    $q->join('warehouse_allocations', 'sales_order_products.id', '=', 'warehouse_allocations.sales_order_product_id')
                        ->where('warehouse_allocations.warehouse_id', $userWarehouseId);
                } else {
                    $q->join('warehouse_allocations', 'sales_order_products.id', '=', 'warehouse_allocations.sales_order_product_id');
                }
            },
        ]);
        $orders = $orders->get();

        foreach ($orders as $order) {
            if (!$isAdmin) {
                if ($order->approval_pending_warehouse_alloc_count > 0) {
                    $order->status = 'approval_pending';
                } else {
                    if ($order->warehouse_alloc_count == $order->packaged_warehouse_alloc_count) {
                        // just update result not original record
                        $order->status = 'all_packaged';
                    }
                }
            } else {
                if ($order->partially_shipped_warehouse_alloc_count > 0 && $order->order->warehouse_alloc_count == $order->partially_shipped_warehouse_alloc_count) {
                    $order->status = 'ready_to_ship';
                } else {
                    if ($order->partially_shipped_warehouse_alloc_count > 0) {
                        $order->status = 'partially_shipped';
                    }
                }
                if ($order->warehouse_alloc_count == $order->packaged_warehouse_alloc_count) {
                    // just update result not original record
                    $order->status = 'all_packaged';
                    $order->save();
                } elseif ($order->packaged_warehouse_alloc_count > 0 && $order->warehouse_alloc_count != $order->packaged_warehouse_alloc_count) {
                    $order->status = 'partial_packaged';
                    $order->save();
                }
            }
        }
        // dd($orders);
        return view('packagingList.index', compact('orders', 'status'));
    }

    /**
     * View a specific sales order with packaging details
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function view($id)
    {
        try {
            $user = Auth::user();

            $isSuperAdmin = $user->hasRole('Super Admin');
            $isAdmin = $user->hasRole(['Super Admin', 'Admin']) || ! $user->warehouse_id;
            $userWarehouseId = $user->warehouse_id;

            $salesOrder = SalesOrder::with([
                'customerGroup',
                'warehouse',
                'orderedProducts' => function ($query) use ($userWarehouseId, $isSuperAdmin) {
                    // Only include orderedProducts that have warehouseAllocations for this warehouse
                    $query->whereHas('warehouseAllocations', function ($q) use ($userWarehouseId, $isSuperAdmin) {
                        if (! $isSuperAdmin && $userWarehouseId) {
                            $q->where('warehouse_id', $userWarehouseId);
                        }
                    })->with([
                        'product',
                        'customer',
                        'tempOrder',
                        'warehouseStock.warehouse',
                        'warehouseAllocations.warehouse',
                    ]);
                },
            ])
                ->whereHas('orderedProducts.warehouseAllocations', function ($query) use ($userWarehouseId, $isSuperAdmin) {
                    if (! $isSuperAdmin && $userWarehouseId) {
                        $query->where('warehouse_id', $userWarehouseId);
                    }
                })
                ->find($id);

            $facilityNames = $salesOrder->orderedProducts
                ->pluck('customer.facility_name')
                ->filter()
                ->unique()
                ->values()
                ->toArray();

            $pendingApprovalList = [];

            foreach ($salesOrder->orderedProducts as $order) {
                foreach ($order->warehouseAllocations as $allocation) {
                    if ($allocation->product_status === 'approval_pending') {
                        $pendingApprovalList[$allocation->warehouse_id][$allocation->sales_order_id][$allocation->sales_order_product_id] = $allocation->id;
                        $pendingApprovalList[$allocation->warehouse_id]['name'] = $allocation->warehouse->name;
                        $pendingApprovalList[$allocation->warehouse_id]['product_count'] = count($pendingApprovalList[$allocation->warehouse_id][$allocation->sales_order_id]);
                        $pendingApprovalList[$allocation->warehouse_id]['allocation_ids'][] = $allocation->id;
                        $pendingApprovalList[$allocation->warehouse_id]['warehouse_id'] = $allocation->warehouse_id;
                    }
                }
            }

            $readyToShipAllocations = WarehouseAllocation::where('sales_order_id', $id)
                ->where('product_status', 'completed')
                ->when(! $isAdmin, function ($query) use ($userWarehouseId) {
                    $query->where('warehouse_id', $userWarehouseId);
                })
                ->get();

            return view('packagingList.view', compact('salesOrder', 'facilityNames', 'isAdmin', 'isSuperAdmin', 'userWarehouseId', 'user', 'pendingApprovalList', 'readyToShipAllocations'));
        } catch (\Exception $e) {
            Log::error('Error loading packaging view: ' . $e->getMessage());

            return redirect()->route('packaging.list.index')
                ->with('error', 'Error loading packaging details: ' . $e->getMessage());
        }
    }

    /**
     * Download packaging products as Excel file
     *
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
        $isAdmin = $user->hasRole(['Super Admin', 'Admin']) || ! $user->warehouse_id;
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
        if (! $isAdmin && $userWarehouseId) {
            $filteredProducts = $salesOrder->orderedProducts->filter(function ($product) use ($userWarehouseId) {
                if ($product->warehouseAllocations && $product->warehouseAllocations->count() > 0) {
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
                    $userAllocations = $order->warehouseAllocations->where('warehouse_id', $userWarehouseId);

                    if ($userAllocations->count() > 0) {
                        foreach ($userAllocations as $allocation) {
                            $warehouseName = $allocation->warehouse->name ?? 'N/A';
                            $allocations[] = ($allocation->warehouse->name ?? 'N/A') . ': ' . $allocation->allocated_quantity;
                        }
                        $warehouseAllocation = implode(', ', $allocations);
                    } else {
                        // Fallback: Check warehouse stock for this SKU in user's warehouse
                        $warehouseStock = \App\Models\WarehouseStock::where('sku', $order->sku)
                            ->where('warehouse_id', $userWarehouseId)
                            ->where('block_quantity', '>', 0)
                            ->first();

                        if ($warehouseStock && $order->tempOrder) {
                            $warehouseName = $warehouseStock->warehouse->name ?? 'N/A';
                            $warehouseAllocation = ($warehouseStock->warehouse->name ?? 'N/A') . ': ' . ($order->tempOrder->block ?? 0);
                        } else {
                            $warehouseName = 'N/A';
                            $warehouseAllocation = 'N/A';
                        }
                    }
                }
            } else {
                // Single warehouse allocation
                if ($order->warehouseStock) {
                    $warehouseName = $order->warehouseStock->warehouse->name ?? 'N/A';
                    $warehouseAllocation = ($order->warehouseStock->warehouse->name ?? 'N/A') . ': ' . ($order->tempOrder->block ?? 0);
                } elseif ($order->tempOrder && $order->tempOrder->block > 0) {
                    // Check warehouse stock for blocked quantity
                    $warehouseStock = \App\Models\WarehouseStock::where('sku', $order->sku)
                        ->where('block_quantity', '>', 0)
                        ->first();

                    if ($warehouseStock) {
                        if ($isAdmin) {
                            $warehouseName = $warehouseStock->warehouse->name ?? 'N/A';
                            $warehouseAllocation = ($warehouseStock->warehouse->name ?? 'N/A') . ': ' . $order->tempOrder->block;
                        } else {
                            // Warehouse user: Only show if it's their warehouse
                            if ($warehouseStock->warehouse_id == $userWarehouseId) {
                                $warehouseName = $warehouseStock->warehouse->name ?? 'N/A';
                                $warehouseAllocation = ($warehouseStock->warehouse->name ?? 'N/A') . ': ' . ($order->tempOrder->block ?? 0);
                            } else {
                                $warehouseName = 'N/A';
                                $warehouseAllocation = 'N/A';
                            }
                        }
                    } else {
                        if ($isAdmin) {
                            $warehouseName = 'All';
                            $warehouseAllocation = 'Total Blocked: ' . $order->tempOrder->block;
                        } else {
                            $warehouseName = 'N/A';
                            $warehouseAllocation = 'N/A';
                        }
                    }
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

            // Fallback: If still 0, use tempOrder->block (order-specific blocked quantity)
            if ($totalDispatchQty == 0 && isset($order->tempOrder->block)) {
                $totalDispatchQty = $order->tempOrder->block;
            }

            // Get final dispatch quantity from warehouse allocations if available
            $finalDispatchQty = 0;
            $boxCount = 0;
            $weight = 0;
            if ($hasAllocations) {
                if ($isAdmin) {
                    // Admin: Sum all warehouses' final dispatch quantities
                    $finalDispatchQty = $order->warehouseAllocations->sum('final_dispatched_quantity') ?: 0;
                    $boxCount = $order->warehouseAllocations->sum('box_count') ?: 0;
                    $weight = $order->warehouseAllocations->sum('weight') ?: 0;
                } else {
                    // Warehouse user: Only their warehouse's final dispatch quantity
                    $finalDispatchQty = $order->warehouseAllocations
                        ->where('warehouse_id', $userWarehouseId)
                        ->sum('final_dispatched_quantity') ?: 0;
                    $boxCount = $order->warehouseAllocations
                        ->where('warehouse_id', $userWarehouseId)
                        ->sum('box_count') ?: 0;
                    $weight = $order->warehouseAllocations
                        ->where('warehouse_id', $userWarehouseId)
                        ->sum('weight') ?: 0;
                }
            } else {
                // Single warehouse or fallback to sales_order_products table
                $finalDispatchQty = $order->final_dispatched_quantity ?? 0;
                $boxCount = $order->box_count ?? 0;
                $weight = $order->weight ?? 0;
            }

            $writer->addRow([
                'Warehouse Name' => $order->warehouseStock ? $order->warehouseStock->warehouse->name : 'N/A',
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
                'Box Count' => $boxCount,
                'Weight' => $weight,
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
            $isAdmin = $user->hasRole(['Super Admin', 'Admin']) || ! $user->warehouse_id;
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

                if (! $customer) {
                    continue;
                }

                // Find the sales order product
                $order = SalesOrderProduct::with(['tempOrder', 'warehouseAllocations'])
                    ->where('customer_id', $customer->id)
                    ->where('sales_order_id', $request->salesOrderId)
                    ->where('sku', $record['SKU Code'])
                    ->first();

                if (! $order) {
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

            // update sales order status as partially packaged if any product is partially packaged
            $salesOrder = SalesOrder::with([
                'orderedProducts.warehouseAllocations'
            ])
                ->withCount('orderedProducts')

                // total warehouse allocations
                ->withCount([
                    'orderedProducts as warehouse_alloc_count' => function ($q) {
                        $q->join('warehouse_allocations', 'sales_order_products.id', '=', 'warehouse_allocations.sales_order_product_id');
                    }
                ])

                // packaged ordered products
                ->withCount([
                    'orderedProducts as packaged_product_count' => function ($q) {
                        $q->where('status', 'packaged');
                    }
                ])

                // packaged warehouse allocations
                ->withCount([
                    'orderedProducts as packaged_warehouse_alloc_count' => function ($q) {
                        $q->join('warehouse_allocations', 'sales_order_products.id', '=', 'warehouse_allocations.sales_order_product_id')
                            ->where('warehouse_allocations.status', 'packaged');
                    }
                ])

                ->findOrFail($request->salesOrderId);

            if ($salesOrder->packaged_warehouse_alloc_count > 0 && $salesOrder->warehouse_alloc_count != $salesOrder->packaged_warehouse_alloc_count) {
                $salesOrder->status = 'partial_packaged';
            }
            $salesOrder->save();


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

            return redirect()->route('packing.products.view', $request->salesOrderId)
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
     * @param  SalesOrderProduct  $order
     * @param  array  $record
     * @param  int  $salesOrderId
     * @param  bool  $isAdmin
     * @param  int|null  $userWarehouseId
     * @return void
     */
    private function updateSalesOrderProduct($order, $record, $salesOrderId, $isAdmin, $userWarehouseId)
    {
        $finalDispatchQty = (int) ($record['Final Dispatch Qty'] ?? $order->dispatched_quantity);
        $boxCount = (int) ($record['Box Count'] ?? 0);
        $weight = (int) ($record['Weight'] ?? 0);
        $issueUnits = (int) ($record['Issue Units'] ?? 0);
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
                if ($order->warehouseAllocations->count() > 1) {
                    $totalAllocated = $order->warehouseAllocations->sum('allocated_quantity');
                } else {
                    $totalAllocated = $order->warehouseAllocations->first()->allocated_quantity;
                }
                foreach ($order->warehouseAllocations as $allocation) {
                    if ($totalAllocated > 0) {
                        // Distribute final dispatch quantity proportionally
                        $proportion = $allocation->allocated_quantity / $totalAllocated;
                        $allocation->final_dispatched_quantity = (int) ($finalDispatchQty * $proportion);
                        $allocation->box_count = (int) ($boxCount * $proportion);
                        $allocation->weight = (int) ($weight * $proportion);
                        $allocation->product_status = 'packaged';
                        $allocation->status = 'packaged';
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
                    $userAllocation->product_status = 'packaged';
                    $userAllocation->status = 'packaged';
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
        $order->product_status = 'packaged';

        // Handle shortage: dispatched > final
        if ($order->dispatched_quantity > $finalDispatchQty) {
            $shortageQuantity = $order->dispatched_quantity - $finalDispatchQty;

            $order->issue_item = $issueUnits ?? $shortageQuantity;
            $order->issue_reason = 'Shortage';
            $order->issue_description = $issueReason ?? 'Shortage products';

            $order->save();

            // Create warehouse product issue record (only once, not per warehouse)
            if (! $hasAllocations || $isAdmin) {
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

    /**
     * Change status to ready_to_ship for warehouse-specific products
     * Warehouse users request approval, Admin approves
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeStatusToReadyToShip(Request $request)
    {
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'sales_order_id' => 'required|integer|exists:sales_orders,id',
                'warehouse_id' => 'nullable|integer|exists:warehouses,id',
                'user_id' => 'nullable|integer|exists:users,id',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Invalid request.');
            }

            $user = User::findOrFail($request->user_id);
            $isAdmin = $user->hasRole(['Super Admin', 'Admin']) || ! $user->warehouse_id;
            $userWarehouseId = $user->warehouse_id;
            $specificWarehouseId = $request->warehouse_id; // For admin to approve specific warehouse

            $salesOrder = SalesOrder::with([
                'orderedProducts.warehouseAllocations',
                'orderedProducts.warehouseStock',
            ])->findOrFail($request->sales_order_id);

            if ($salesOrder->status === 'ready_to_ship') {
                return redirect()->back()
                    ->with('error', 'Order is already in ready_to_ship status.');
            }

            if (! $isAdmin) {
                // Warehouse user: Mark their allocations as pending approval
                $allocationsUpdated = 0;

                foreach ($salesOrder->orderedProducts as $product) {
                    $hasAllocations = $product->warehouseAllocations && $product->warehouseAllocations->count() > 0;

                    if ($hasAllocations) {
                        // Update warehouse allocations for this warehouse
                        $userAllocations = $product->warehouseAllocations->where('warehouse_id', $userWarehouseId);

                        foreach ($userAllocations as $allocation) {
                            // Change status from 'draft' to 'pending' when warehouse clicks "Ready to Ship"
                            if ($allocation->final_dispatched_quantity > 0 && $allocation->approval_status === 'draft') {
                                $allocation->approval_status = 'pending';
                                $allocation->product_status = 'approval_pending';
                                $allocation->status = 'approval_pending';
                                $allocation->save();
                                $allocationsUpdated++;

                                activity()
                                    ->performedOn($allocation)
                                    ->causedBy($user)
                                    ->withProperties([
                                        'warehouse_id' => $userWarehouseId,
                                        'sales_order_id' => $salesOrder->id,
                                        'sku' => $allocation->sku,
                                    ])
                                    ->log('Warehouse requested approval for ready to ship');
                            }
                        }
                    }
                }

                if ($allocationsUpdated === 0) {
                    return redirect()->back()
                        ->with('error', 'No products are ready for approval. Please ensure final dispatch quantities are set by uploading Excel file.');
                }

                DB::commit();

                return redirect()->back()
                    ->with('success', 'Approval request sent to admin for ' . $allocationsUpdated . ' product(s). Waiting for admin approval.');
            } else {
                // Admin: Approve pending allocations (all or specific warehouse)
                $productsToUpdate = [];
                $allocationsApproved = 0;

                foreach ($salesOrder->orderedProducts as $product) {
                    $hasAllocations = $product->warehouseAllocations && $product->warehouseAllocations->count() > 0;

                    if ($hasAllocations) {
                        // Multi-warehouse product
                        foreach ($product->warehouseAllocations as $allocation) {
                            // If specific warehouse is requested, only approve that warehouse
                            if ($specificWarehouseId && $allocation->warehouse_id != $specificWarehouseId) {
                                // Skip this allocation if not the requested warehouse
                                continue;
                            }

                            // Only approve if status is 'pending' (warehouse has requested approval)
                            if ($allocation->final_dispatched_quantity > 0 && $allocation->status === 'approval_pending') {
                                // Approve this allocation
                                // $allocation->status = 'ready_to_ship';
                                $allocation->approval_status = 'approved';
                                $allocation->product_status = 'completed';
                                $allocation->status = 'ready_to_ship';
                                $allocation->approved_by = $user->id;
                                $allocation->approved_at = now();
                                $allocation->save();
                                $allocationsApproved++;

                                activity()
                                    ->performedOn($allocation)
                                    ->causedBy($user)
                                    ->withProperties([
                                        'warehouse_id' => $allocation->warehouse_id,
                                        'sales_order_id' => $salesOrder->id,
                                        'sku' => $allocation->sku,
                                    ])
                                    ->log('Admin approved warehouse allocation for ready to ship');
                            }
                        }

                        // After approving allocations, check if ALL allocations for this product are now approved
                        // IMPORTANT: We need to check ALL allocations that have allocated_quantity > 0
                        // Not just those with final_dispatched_quantity > 0
                        $allWarehousesApproved = true;
                        $hasAnyAllocation = false;

                        foreach ($product->warehouseAllocations as $allocation) {
                            // Check if this allocation has allocated quantity (warehouse is supposed to dispatch)
                            if ($allocation->allocated_quantity > 0) {
                                $hasAnyAllocation = true;

                                // This warehouse must have:
                                // 1. final_dispatched_quantity > 0 (warehouse has uploaded Excel and filled data)
                                // 2. approval_status = 'approved' (admin has approved)
                                if ($allocation->final_dispatched_quantity <= 0 || $allocation->approval_status !== 'approved') {
                                    $allWarehousesApproved = false;
                                    break;
                                }
                            }
                        }

                        // Only update product status if:
                        // 1. Product has at least one allocation with allocated_quantity > 0
                        // 2. All such allocations have final_dispatched_quantity > 0 AND are approved
                        if ($hasAnyAllocation && $allWarehousesApproved) {
                            $productsToUpdate[] = $product->id;
                        }
                    } else {
                        // Single warehouse product or auto-allocation without explicit allocations
                        if ($product->final_dispatched_quantity > 0) {
                            $productsToUpdate[] = $product->id;
                        }
                    }
                    $product->product_status = 'completed';
                    $product->status = 'ready_to_ship';
                    $product->save();
                }

                if (empty($productsToUpdate) && $allocationsApproved === 0) {
                    return redirect()->back()
                        ->with('error', 'No products are ready to ship. Please ensure final dispatch quantities are set.');
                }

                // Update product statuses to ready_to_ship
                if (! empty($productsToUpdate)) {
                    SalesOrderProduct::whereIn('id', $productsToUpdate)
                        ->update(['status' => 'ready_to_ship']);

                    // Log activity for each product
                    foreach ($productsToUpdate as $productId) {
                        $product = SalesOrderProduct::find($productId);
                        activity()
                            ->performedOn($product)
                            ->causedBy($user)
                            ->withProperties([
                                'old_status' => 'packaging',
                                'new_status' => 'ready_to_ship',
                                'sales_order_id' => $salesOrder->id,
                            ])
                            ->log('Product status changed to ready_to_ship');
                    }
                }

                // Check if ALL products in the order are now ready_to_ship
                $totalProducts = $salesOrder->orderedProducts->count();
                $readyToShipProducts = SalesOrderProduct::where('sales_order_id', $salesOrder->id)
                    ->where('status', 'ready_to_ship')
                    ->count();

                if ($totalProducts == $readyToShipProducts) {
                    // All products are ready, update sales order status
                    $oldStatus = $salesOrder->status;
                    $salesOrder->status = 'ready_to_ship';
                    $salesOrder->save();

                    // Create status change notification
                    NotificationService::statusChanged('sales', $salesOrder->id, $oldStatus, $salesOrder->status);

                    activity()
                        ->performedOn($salesOrder)
                        ->causedBy($user)
                        ->withProperties([
                            'old_status' => $oldStatus,
                            'new_status' => 'ready_to_ship',
                        ])
                        ->log('Sales order status changed to ready_to_ship (all products ready)');

                    DB::commit();

                    // If admin approved specific warehouse, stay on packaging page
                    // If admin approved all warehouses, redirect to Ready to Ship page
                    if ($specificWarehouseId) {
                        $warehouseName = \App\Models\Warehouse::find($specificWarehouseId)->name ?? 'Warehouse';

                        return redirect()->back()
                            ->with('success', $warehouseName . ' approved successfully! All warehouses are now approved. Order status changed to "Ready to Ship".');
                    } else {
                        return redirect()->route('readyToShip.view', $salesOrder->id)
                            ->with('success', 'All products approved and ready to ship! Order status changed to "Ready to Ship". Order ID: ' . $salesOrder->id);
                    }
                } else {
                    // Some products are still in packaging
                    // partial packaging completion
                    DB::commit();

                    // Build success message
                    $message = '';
                    if ($specificWarehouseId) {
                        $warehouseName = \App\Models\Warehouse::find($specificWarehouseId)->name ?? 'Warehouse';
                        $message = $warehouseName . ' approved successfully! ' . $allocationsApproved . ' allocation(s) approved.';

                        // Check if there are still pending approvals for other warehouses
                        $pendingAllocations = WarehouseAllocation::where('sales_order_id', $salesOrder->id)
                            ->where('approval_status', 'pending')
                            ->where('final_dispatched_quantity', '>', 0)
                            ->count();

                        if ($pendingAllocations > 0) {
                            $message .= ' ' . $pendingAllocations . ' allocation(s) from other warehouses still pending approval.';
                        }
                    } else {
                        $message = count($productsToUpdate) . ' product(s) approved and marked as ready to ship. ' . ($totalProducts - $readyToShipProducts) . ' product(s) still in packaging.';
                    }

                    return redirect()->back()
                        ->with('success', $message);
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error changing status to ready_to_ship: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error changing status: ' . $e->getMessage());
        }
    }

    /**
     * Approve individual warehouse allocation
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveWarehouseAllocation(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();
            $isAdmin = $user->hasRole(['Super Admin', 'Admin']) || ! $user->warehouse_id;

            if (! $isAdmin) {
                return redirect()->back()
                    ->with('error', 'Only admin can approve warehouse allocations.');
            }

            $allocation = WarehouseAllocation::with(['salesOrderProduct', 'warehouse'])->findOrFail($id);

            if ($allocation->approval_status === 'approved') {
                return redirect()->back()
                    ->with('error', 'This allocation is already approved.');
            }

            // Approve the allocation
            $allocation->approval_status = 'approved';
            $allocation->approved_by = $user->id;
            $allocation->approved_at = now();
            $allocation->save();

            activity()
                ->performedOn($allocation)
                ->causedBy($user)
                ->withProperties([
                    'warehouse_id' => $allocation->warehouse_id,
                    'sales_order_id' => $allocation->sales_order_id,
                    'sku' => $allocation->sku,
                ])
                ->log('Admin approved warehouse allocation for ready to ship');

            // Check if all allocations for this product are approved
            $product = $allocation->salesOrderProduct;
            $allApproved = true;

            foreach ($product->warehouseAllocations as $alloc) {
                if ($alloc->final_dispatched_quantity > 0 && $alloc->approval_status !== 'approved') {
                    $allApproved = false;
                    break;
                }
            }

            // If all allocations are approved, update product status
            if ($allApproved) {
                $product->status = 'ready_to_ship';
                $product->save();

                activity()
                    ->performedOn($product)
                    ->causedBy($user)
                    ->withProperties([
                        'old_status' => 'packaging',
                        'new_status' => 'ready_to_ship',
                        'sales_order_id' => $allocation->sales_order_id,
                    ])
                    ->log('Product status changed to ready_to_ship (all allocations approved)');

                // Check if all products in the order are ready to ship
                $salesOrder = SalesOrder::findOrFail($allocation->sales_order_id);
                $totalProducts = $salesOrder->orderedProducts->count();
                $readyToShipProducts = SalesOrderProduct::where('sales_order_id', $salesOrder->id)
                    ->where('status', 'ready_to_ship')
                    ->count();

                if ($totalProducts == $readyToShipProducts) {
                    $oldStatus = $salesOrder->status;
                    $salesOrder->status = 'ready_to_ship';
                    $salesOrder->save();

                    NotificationService::statusChanged('sales', $salesOrder->id, $oldStatus, $salesOrder->status);

                    activity()
                        ->performedOn($salesOrder)
                        ->causedBy($user)
                        ->withProperties([
                            'old_status' => $oldStatus,
                            'new_status' => 'ready_to_ship',
                        ])
                        ->log('Sales order status changed to ready_to_ship (all products approved)');
                }
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Warehouse allocation approved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error approving warehouse allocation: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error approving allocation: ' . $e->getMessage());
        }
    }

    /**
     * Reject individual warehouse allocation
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectWarehouseAllocation(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();
            $isAdmin = $user->hasRole(['Super Admin', 'Admin']) || ! $user->warehouse_id;

            if (! $isAdmin) {
                return redirect()->back()
                    ->with('error', 'Only admin can reject warehouse allocations.');
            }

            $allocation = WarehouseAllocation::with(['warehouse'])->findOrFail($id);

            if ($allocation->approval_status === 'rejected') {
                return redirect()->back()
                    ->with('error', 'This allocation is already rejected.');
            }

            // Reject the allocation
            $allocation->approval_status = 'rejected';
            $allocation->approved_by = $user->id;
            $allocation->approved_at = now();
            $allocation->save();

            activity()
                ->performedOn($allocation)
                ->causedBy($user)
                ->withProperties([
                    'warehouse_id' => $allocation->warehouse_id,
                    'sales_order_id' => $allocation->sales_order_id,
                    'sku' => $allocation->sku,
                ])
                ->log('Admin rejected warehouse allocation for ready to ship');

            DB::commit();

            return redirect()->back()
                ->with('success', 'Warehouse allocation rejected successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error rejecting warehouse allocation: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Error rejecting allocation: ' . $e->getMessage());
        }
    }
}
