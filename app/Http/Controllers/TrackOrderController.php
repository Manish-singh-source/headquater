<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TrackOrderController extends Controller
{
    /**
     * Display order tracking search form
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        try {
            if ($request->has('order_number') && ! empty($request->order_number)) {
                $validator = Validator::make($request->all(), [
                    'order_number' => 'required',
                ]);

                if ($validator->fails()) {
                    return view('trackOrder.index')
                        ->with('error', 'Invalid Sales Order Number. Please check and try again.');
                }

                $salesOrder = SalesOrder::with([
                    'customerGroup',
                    'warehouse',
                    'orderedProducts.product',
                    'orderedProducts.tempOrder',
                ])->where('order_number', $request->order_number)
                    ->first();

                if (! $salesOrder) {
                    return view('trackOrder.index')
                        ->with('error', 'Order not found. Please verify the order ID.');
                }

                // Log tracking access
                activity()
                    ->causedBy(Auth::user())
                    ->withProperties(['order_number' => $salesOrder->order_number])
                    ->event('order_tracked')
                    ->log('Order tracked: #'.$salesOrder->order_number);

                return redirect()->route('trackOrder.view', $salesOrder->id);
            }

            return view('trackOrder.index');
        } catch (\Exception $e) {
            return view('trackOrder.index')
                ->with('error', 'Error searching for order: '.$e->getMessage());
        }
    }

    /**
     * View detailed order tracking information
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function view($id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:sales_orders,id',
            ]);

            if ($validator->fails()) {
                return redirect()->route('trackOrder.index')
                    ->with('error', 'Order not found.');
            }

            $salesOrder = SalesOrder::with([
                'customerGroup',
                'warehouse',
                'orderedProducts.product',
                'orderedProducts.tempOrder.vendorPIProduct',
                'orderedProducts.warehouseStock',
            ])
                ->withSum('orderedProducts', 'purchase_ordered_quantity')
                ->withSum('orderedProducts', 'ordered_quantity')
                ->withCount('notFoundTempOrderByProduct')
                ->withCount('notFoundTempOrderByCustomer')
                ->withCount('notFoundTempOrderByVendor')
                ->findOrFail($id);

            if (! $salesOrder) {
                return redirect()->route('trackOrder.index')
                    ->with('error', 'Order not found.');
            }

            // Calculate totals efficiently using queries instead of loops
            $totals = $this->calculateOrderTotals($salesOrder);

            // Get unique brands
            $uniqueBrands = $this->getUniqueBrands($salesOrder);

            // Log tracking access
            activity()
                ->performedOn($salesOrder)
                ->causedBy(Auth::user())
                ->event('order_viewed')
                ->log('Order tracking details viewed: #'.$salesOrder->id);

            return view('trackOrder.view', array_merge(
                compact('salesOrder', 'uniqueBrands'),
                $totals
            ));
        } catch (\Exception $e) {
            return redirect()->route('trackOrder.index')
                ->with('error', 'Error loading order details: '.$e->getMessage());
        }
    }

    /**
     * Calculate order totals from ordered products
     *
     * @param  \App\Models\SalesOrder  $salesOrder
     * @return array
     */
    private function calculateOrderTotals($salesOrder)
    {
        $blockQuantity = 0;
        $vendorPiFulfillmentTotal = 0;
        $vendorPiReceivedTotal = 0;
        $availableQuantity = 0;
        $unavailableQuantity = 0;
        $orderedQuantity = 0;

        foreach ($salesOrder->orderedProducts as $product) {
            if ($product->tempOrder) {
                $blockQuantity += (int) ($product->tempOrder->block ?? 0);
                $vendorPiFulfillmentTotal += (int) ($product->tempOrder->vendor_pi_fulfillment_quantity ?? 0);
                $vendorPiReceivedTotal += (int) ($product->tempOrder->vendor_pi_received_quantity ?? 0);
                $availableQuantity += (int) ($product->tempOrder->available_quantity ?? 0);
                $unavailableQuantity += (int) ($product->tempOrder->unavailable_quantity ?? 0);
                $orderedQuantity += (int) ($product->ordered_quantity ?? 0);
            }
        }

        $remainingQuantity = max(0, $orderedQuantity - $blockQuantity);

        // Calculate delivery status percentages
        $fulfillmentPercentage = $orderedQuantity > 0
            ? round(($vendorPiFulfillmentTotal / $orderedQuantity) * 100, 2)
            : 0;

        $receivedPercentage = $orderedQuantity > 0
            ? round(($vendorPiReceivedTotal / $orderedQuantity) * 100, 2)
            : 0;

        $availablePercentage = $orderedQuantity > 0
            ? round(($availableQuantity / $orderedQuantity) * 100, 2)
            : 0;

        return [
            'blockQuantity' => $blockQuantity,
            'vendorPiFulfillmentTotal' => $vendorPiFulfillmentTotal,
            'vendorPiReceivedTotal' => $vendorPiReceivedTotal,
            'availableQuantity' => $availableQuantity,
            'unavailableQuantity' => $unavailableQuantity,
            'orderedQuantity' => $orderedQuantity,
            'remainingQuantity' => $remainingQuantity,
            'fulfillmentPercentage' => $fulfillmentPercentage,
            'receivedPercentage' => $receivedPercentage,
            'availablePercentage' => $availablePercentage,
        ];
    }

    /**
     * Get unique brands from ordered products
     *
     * @param  \App\Models\SalesOrder  $salesOrder
     * @return \Illuminate\Support\Collection
     */
    private function getUniqueBrands($salesOrder)
    {
        return $salesOrder->orderedProducts
            ->pluck('product.brand')
            ->filter(function ($brand) {
                return ! empty($brand);
            })
            ->unique()
            ->values();
    }

    /**
     * Search orders by multiple criteria (API endpoint)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:2|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid search query',
            ], 422);
        }

        try {
            $query = trim($request->query);

            $orders = SalesOrder::with('customerGroup')
                ->where('id', 'LIKE', "%{$query}%")
                ->orWhereHas('customerGroup', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%");
                })
                ->orWhereHas('orderedProducts', function ($q) use ($query) {
                    $q->where('sku', 'LIKE', "%{$query}%");
                })
                ->latest()
                ->limit(10)
                ->get()
                ->map(function ($order) {
                    return [
                        'id' => $order->id,
                        'order_number' => '#'.$order->id,
                        'customer_group' => $order->customerGroup?->name ?? 'N/A',
                        'status' => ucfirst($order->status ?? 'pending'),
                        'created_at' => $order->created_at?->format('d-m-Y H:i') ?? 'N/A',
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $orders,
                'count' => $orders->count(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error searching orders: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get order status timeline (API endpoint)
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function timeline($id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|integer|exists:sales_orders,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found',
                ], 404);
            }

            $salesOrder = SalesOrder::findOrFail($id);

            $timeline = [
                [
                    'status' => 'pending',
                    'label' => 'Order Created',
                    'date' => $salesOrder->created_at?->format('d-m-Y H:i'),
                    'completed' => true,
                ],
                [
                    'status' => 'ready_to_package',
                    'label' => 'Ready to Package',
                    'date' => null,
                    'completed' => $salesOrder->status !== 'pending',
                ],
                [
                    'status' => 'packaged',
                    'label' => 'Packaged',
                    'date' => null,
                    'completed' => in_array($salesOrder->status, ['packaged', 'ready_to_ship', 'shipped', 'delivered']),
                ],
                [
                    'status' => 'ready_to_ship',
                    'label' => 'Ready to Ship',
                    'date' => null,
                    'completed' => in_array($salesOrder->status, ['ready_to_ship', 'shipped', 'delivered']),
                ],
                [
                    'status' => 'shipped',
                    'label' => 'Shipped',
                    'date' => null,
                    'completed' => in_array($salesOrder->status, ['shipped', 'delivered']),
                ],
                [
                    'status' => 'delivered',
                    'label' => 'Delivered',
                    'date' => null,
                    'completed' => $salesOrder->status === 'delivered',
                ],
            ];

            return response()->json([
                'success' => true,
                'data' => $timeline,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving timeline: '.$e->getMessage(),
            ], 500);
        }
    }
}
