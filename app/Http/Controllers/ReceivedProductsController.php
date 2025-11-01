<?php

namespace App\Http\Controllers;

use App\Models\ProductIssue;
use App\Models\PurchaseOrder;
use App\Models\VendorPI;
use App\Models\VendorPIProduct;
use App\Models\VendorReturnProduct;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\SimpleExcel\SimpleExcelReader;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ReceivedProductsController extends Controller
{
    /**
     * Display list of pending purchase orders with vendor PIs
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            $purchaseOrders = PurchaseOrder::with(['purchaseOrderProducts', 'vendorPI'])
                ->where('status', 'pending')
                ->withCount('purchaseOrderProducts')
                ->whereHas('vendorPI', function ($query) {
                    $query->where('status', 'pending');
                })
                ->latest()
                ->paginate(15);

            return view('receivedProducts.index', compact('purchaseOrders'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error retrieving purchase orders: ' . $e->getMessage());
        }
    }

    /**
     * View vendor PI details for a specific purchase order
     *
     * @param int $id
     * @param string $vendorCode
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function view($id, $vendorCode)
    {
        try {
            $validator = Validator::make([
                'id' => $id,
                'vendor_code' => $vendorCode,
            ], [
                'id' => 'required|integer|exists:purchase_orders,id',
                'vendor_code' => 'required|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Invalid purchase order or vendor code.');
            }

            $vendorPIs = VendorPI::with('products.product', 'purchaseOrder', 'vendor')
                ->where('purchase_order_id', $id)
                ->where('vendor_code', $vendorCode)
                ->first();

            if (!$vendorPIs) {
                return redirect()->back()->with('error', 'Vendor PI not found.');
            }

            return view('receivedProducts.view', compact('vendorPIs'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading vendor PI: ' . $e->getMessage());
        }
    }

    /**
     * Update vendor PI status to approve
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_pi_id' => 'required|integer|exists:vendor_p_i_s,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        DB::beginTransaction();

        try {
            $vendorPI = VendorPI::with('products')->lockForUpdate()->findOrFail($request->vendor_pi_id);

            if ($vendorPI->status !== 'pending') {
                return redirect()->back()->with('error', 'Vendor PI has already been processed.');
            }

            $oldStatus = $vendorPI->status;
            $vendorPI->status = 'approve';
            $vendorPI->approved_by = Auth::id();
            $vendorPI->approved_at = now();
            $vendorPI->save();

            DB::commit();

            // Log activity
            activity()
                ->performedOn($vendorPI)
                ->causedBy(Auth::user())
                ->withProperties([
                    'old_status' => $oldStatus,
                    'new_status' => 'approve',
                    'product_count' => $vendorPI->products->count(),
                ])
                ->event('approved')
                ->log('Vendor PI sent for approval');

            // Create notification
            $productCount = $vendorPI->products->count();
            NotificationService::productsReceived('purchase', $vendorPI->purchase_order_id, $productCount);

            return redirect()->route('received-products.index')
                ->with('success', 'Vendor PI sent for approval successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error updating vendor PI status: ' . $e->getMessage());
        }
    }

    /**
     * Download received products file as Excel
     *
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function downloadReceivedProductsFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'purchaseOrderId' => 'required|integer|exists:purchase_orders,id',
            'vendorCode' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Invalid purchase order or vendor code.');
        }

        try {
            $tempXlsxPath = storage_path('app/received_' . Str::random(8) . '.xlsx');
            $writer = SimpleExcelWriter::create($tempXlsxPath);

            $vendorPI = VendorPI::with('products.product')
                ->where('purchase_order_id', $request->purchaseOrderId)
                ->where('vendor_code', $request->vendorCode)
                ->first();

            if (!$vendorPI) {
                return redirect()->back()->with('error', 'Vendor PI not found.');
            }

            // Add header row
            $writer->addRow([
                'Order No' => 'Order No',
                'Purchase Order No' => 'Purchase Order No',
                'Vendor SKU Code' => 'Vendor SKU Code',
                'Title' => 'Title',
                'MRP' => 'MRP',
                'PO Quantity' => 'PO Quantity',
                'PI Quantity' => 'PI Quantity',
                'Quantity Received' => 'Quantity Received',
                'Issue Units' => 'Issue Units',
                'Issue Description' => 'Issue Description',
            ]);

            // Add data rows
            foreach ($vendorPI->products as $product) {
                $writer->addRow([
                    'Order No' => $vendorPI->id ?? '',
                    'Purchase Order No' => $vendorPI->purchase_order_id ?? '',
                    'Vendor SKU Code' => $product->vendor_sku_code ?? '',
                    'Title' => $product->product?->brand_title ?? '',
                    'MRP' => $product->mrp ?? '',
                    'PO Quantity' => $product->quantity_requirement ?? '',
                    'PI Quantity' => $product->available_quantity ?? '',
                    'Quantity Received' => '',
                    'Issue Units' => '',
                    'Issue Description' => '',
                ]);
            }

            $writer->close();

            return response()->download($tempXlsxPath, 'Received-Products-' . $request->vendorCode . '.xlsx', [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error downloading file: ' . $e->getMessage());
        }
    }

    /**
     * Update purchase order status to completed
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'purchase_order_id' => 'required|integer|exists:purchase_orders,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        DB::beginTransaction();

        try {
            $purchaseOrder = PurchaseOrder::lockForUpdate()->findOrFail($request->purchase_order_id);

            $oldStatus = $purchaseOrder->status;
            $purchaseOrder->status = 'completed';
            $purchaseOrder->completed_by = Auth::id();
            $purchaseOrder->completed_at = now();
            $purchaseOrder->save();

            DB::commit();

            // Log activity
            activity()
                ->performedOn($purchaseOrder)
                ->causedBy(Auth::user())
                ->withProperties([
                    'old_status' => $oldStatus,
                    'new_status' => 'completed',
                ])
                ->event('completed')
                ->log('Purchase order marked as completed');

            return redirect()->back()
                ->with('success', 'Purchase order marked as completed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error updating purchase order: ' . $e->getMessage());
        }
    }

    /**
     * Get vendors for a purchase order via AJAX
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVendors(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:purchase_orders,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid purchase order ID',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $vendorsList = VendorPI::where('purchase_order_id', $request->id)
                ->where('status', '!=', 'completed')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Vendors retrieved successfully',
                'data' => $vendorsList,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving vendors: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update received products from Excel file
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateReceivedProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pi_excel' => 'required|file|mimes:xlsx,csv,xls',
            'vendor_pi_id' => 'required|integer|exists:vendor_p_i_s,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $file = $request->file('pi_excel');
        $filepath = $file->getPathname();
        $extension = $file->getClientOriginalExtension();

        DB::beginTransaction();

        try {
            $reader = SimpleExcelReader::create($filepath, $extension);
            $rows = $reader->getRows();
            $insertCount = 0;

            $vendorPI = VendorPI::with('products')->lockForUpdate()->findOrFail($request->vendor_pi_id);

            if ($vendorPI->status !== 'pending' && $vendorPI->status !== 'approve') {
                DB::rollBack();

                return redirect()->back()
                    ->with('error', 'This vendor PI has already been processed.');
            }

            foreach ($rows as $record) {
                if (empty($record['Vendor SKU Code'] ?? null)) {
                    continue;
                }

                $vendorSkuCode = trim($record['Vendor SKU Code']);
                $quantityReceived = (int)($record['Quantity Received'] ?? 0);
                $issueUnits = (int)($record['Issue Units'] ?? 0);
                $issueDescription = trim($record['Issue Description'] ?? '');

                $productData = VendorPIProduct::lockForUpdate()
                    ->where('vendor_sku_code', $vendorSkuCode)
                    ->where('vendor_pi_id', $vendorPI->id)
                    ->first();

                if (!$productData) {
                    continue;
                }

                // Process quantity received
                if ($quantityReceived > 0) {
                    if ($productData->available_quantity < $quantityReceived) {
                        // Extra quantity received - create return entry
                        $extraQuantity = $quantityReceived - $productData->available_quantity;
                        $productData->quantity_received = $productData->available_quantity;

                        VendorReturnProduct::create([
                            'vendor_pi_product_id' => $productData->id,
                            'sku' => $productData->vendor_sku_code,
                            'return_quantity' => $extraQuantity,
                            'return_reason' => 'Extra',
                            'return_description' => 'Extra products returned to vendor',
                            'return_status' => 'pending',
                        ]);
                    } elseif ($productData->available_quantity > $quantityReceived) {
                        // Shortage - create issue entry
                        $shortageQuantity = $productData->available_quantity - $quantityReceived;
                        $productData->quantity_received = $quantityReceived;

                        ProductIssue::create([
                            'purchase_order_id' => $vendorPI->purchase_order_id,
                            'vendor_pi_id' => $vendorPI->id,
                            'vendor_pi_product_id' => $productData->id,
                            'vendor_sku_code' => $productData->vendor_sku_code,
                            'quantity_requirement' => $productData->quantity_requirement,
                            'available_quantity' => $productData->available_quantity,
                            'quantity_received' => $quantityReceived,
                            'issue_item' => $shortageQuantity,
                            'issue_reason' => 'Shortage',
                            'issue_description' => 'Shortage products',
                            'issue_from' => 'vendor',
                            'issue_status' => 'pending',
                        ]);
                    } else {
                        // Exact quantity match
                        $productData->quantity_received = $quantityReceived;
                    }
                }

                // Process issue items
                if ($issueUnits > 0) {
                    $productData->issue_item = $issueUnits;
                    $productData->issue_reason = ($productData->quantity_requirement < $quantityReceived) ? 'Exceed' : 'Shortage';
                    $productData->issue_description = $issueDescription;
                } else {
                    $productData->issue_item = 0;
                    $productData->issue_reason = '';
                    $productData->issue_description = '';
                }

                $productData->save();
                $insertCount++;
            }

            if ($insertCount === 0) {
                DB::rollBack();

                return redirect()->back()->withErrors(['pi_excel' => 'No valid data found in the file.']);
            }

            DB::commit();

            // Log activity
            activity()
                ->performedOn($vendorPI)
                ->causedBy(Auth::user())
                ->withProperties(['records_processed' => $insertCount])
                ->event('products_received')
                ->log('Received products updated: ' . $insertCount . ' records');

            // Create notification
            NotificationService::productsReceived('purchase', $vendorPI->purchase_order_id, $insertCount);

            return redirect()->back()
                ->with('success', 'Successfully processed ' . $insertCount . ' product(s).');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Error processing received products: ' . $e->getMessage())
                ->withInput();
        }
    }
}
