<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Illuminate\Http\Request;

class TrackOrderController extends Controller
{
    //
    public function index(Request $request)
    {
        if (isset($request->order_id)) {
            $salesOrder = SalesOrder::with('customerGroup', 'warehouse', 'orderedProducts.product', 'orderedProducts.tempOrder')->find($request->order_id);
            if (! isset($salesOrder)) {
                return view('trackOrder.index')->with('error', 'Order Not Found.');
            }
            return redirect()->route('trackOrder.view', $salesOrder->id);
        }

        return view('trackOrder.index');
    }

    public function view($id)
    {
        $salesOrder = SalesOrder::with([
            'customerGroup',
            'warehouse',
            'orderedProducts.tempOrder.vendorPIProduct',
            'orderedProducts.warehouseStock',
        ])
            ->withSum('orderedProducts', 'purchase_ordered_quantity')
            ->withSum('orderedProducts', 'ordered_quantity')
            ->withCount('notFoundTempOrderByProduct')
            ->withCount('notFoundTempOrderByCustomer')
            ->withCount('notFoundTempOrderByVendor')
            ->findOrFail($id);

        $blockQuantity = 0;
        $vendorPiFulfillmentTotal = 0;
        $vendorPiReceivedTotal = 0;
        $availableQuantity = 0;
        $unavailableQuantity = 0;
        $orderedQuantity = 0;

        foreach ($salesOrder->orderedProducts as $product) {
            if (isset($product->tempOrder)) {
                $blockQuantity += $product->tempOrder->block;
                $vendorPiFulfillmentTotal += $product->tempOrder->vendor_pi_fulfillment_quantity;
                $vendorPiReceivedTotal += $product->tempOrder->vendor_pi_received_quantity;
                $availableQuantity += $product->tempOrder->available_quantity;
                $unavailableQuantity += $product->tempOrder->unavailable_quantity;
                $orderedQuantity += $product->ordered_quantity;
            }
        }

        $remainingQuantity = $orderedQuantity - ($blockQuantity);

        // fetch unique brand names from orderedProducts
        $uniqueBrands = $salesOrder->orderedProducts->pluck('product.brand')->unique();
        $uniqueBrands = $uniqueBrands->filter()->unique()->values();

        return view('trackOrder.view', compact('uniqueBrands', 'remainingQuantity', 'blockQuantity', 'salesOrder', 'vendorPiFulfillmentTotal', 'availableQuantity', 'orderedQuantity', 'unavailableQuantity', 'vendorPiReceivedTotal'));
    }
}
