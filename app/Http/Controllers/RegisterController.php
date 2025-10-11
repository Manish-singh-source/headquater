<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Warehouse;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\WarehouseStock;
use App\Models\SalesOrderProduct;
use App\Models\PurchaseOrderProduct;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'phoneNo' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'fname' => $request->firstName,
            'lname' => $request->lastName,
            'phone' => $request->phoneNo,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return redirect()->route('login')->with('success', 'Registration successful.');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            if (Auth::user()->status !== '1') {
                Auth::logout();

                return back()->withErrors(['email' => 'Your account is not active.']);
            }
            $request->session()->regenerate();

            return redirect()->intended('/')->with('success', 'Login successful.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function index()
    {
        $customersCount = Customer::count();
        $vendorsCount = Vendor::count();
        $salesOrdersCount = SalesOrderProduct::count();
        $purchaseOrdersCount = PurchaseOrderProduct::count();
        $productsCount = Product::count();
        $warehouseCount = Warehouse::count();
        $readyToShipOrdersCount = SalesOrder::where('status', 'ready_to_ship')->count();
        $readyToPackageOrdersCount = SalesOrder::where('status', 'ready_to_package')->count();

        // Recent Purchase Orders (Vendor)
        $purchaseOrders = PurchaseOrder::with('purchaseOrderProducts')->limit(4)->latest()->get();
        $vendorCodes = $purchaseOrders->flatMap(function ($po) {
            return $po->purchaseOrderProducts->pluck('vendor_code');
        })->unique()->values();

        // Recent Sales Orders (Cutomer)
        $orders = SalesOrder::with('customerGroup', 'orderedProducts.product')->limit(4)->latest()->get();

        // Fetch recent 4 orders with related products and customer groups
        $orders = SalesOrder::with('customerGroup', 'orderedProducts.product')
            ->latest()
            ->limit(4)
            ->get();

        // Process brand summary
        $salesOrdersByBrand = $orders->flatMap(function ($order) {
            // Flatten each product in every order
            return $order->orderedProducts->map(function ($item) use ($order) {
                $brand = optional($item->product)->brand;

                return [
                    'brand' => $brand,
                    'order_id' => $order->id,
                    'order_status' => $order->status ?? 'pending', // assuming 'status' field exists
                ];
            });
        })
            ->filter(function ($item) {
                return !empty($item['brand']); // only valid brands
            })
            ->groupBy('brand')
            ->map(function ($items, $brand) {
                return [
                    'brand' => $brand,
                    'total_orders' => $items->pluck('order_id')->unique()->count(),
                    'pending_orders' => $items->where('order_status', 'pending')->pluck('order_id')->unique()->count(),
                    'completed_orders' => $items->where('order_status', 'completed')->pluck('order_id')->unique()->count(),
                ];
            })
            ->values();

        //  in products table i have list of products from where i want to select unique brands where  
        // the brand is not null and then count the total number of orders, from this orders i want to show pending orders and completed orders for that brand 



        // Recent Packaging List
        $packagingOrders = SalesOrder::where('status', 'ready_to_package')->with('customerGroup')->limit(4)->latest()->get();

        // Recent Ready To Ship Orders
        $readyToShipOrders = SalesOrder::where('status', 'ready_to_ship')->with('customerGroup')->limit(4)->latest()->get();

        // Invoices Lists
        $invoices = Invoice::with(['warehouse', 'customer', 'salesOrder'])->limit(4)->latest()->get();


        // Warehouse Inventory Stocks
        $warehouseStocks = WarehouseStock::with('product')
            ->latest()
            ->get();

        // Group and summarize by brand
        $brandWiseStocks = $warehouseStocks
            ->map(function ($stock) {
                $brand = optional($stock->product)->brand;
                $price = optional($stock->product)->price ?? 0; // assume product has 'price' field

                return [
                    'brand' => $brand,
                    'quantity' => $stock->quantity ?? 0,
                    'price' => $price,
                    'total_value' => ($stock->quantity ?? 0) * $price,
                ];
            })
            ->filter(function ($item) {
                return !empty($item['brand']); // skip null brands
            })
            ->groupBy('brand')
            ->map(function ($items, $brand) {
                return [
                    'brand' => $brand,
                    'total_quantity' => $items->sum('quantity'),
                    'total_value' => $items->sum('total_value'),
                ];
            })
            ->values();


        $brandSummary = $purchaseOrders->flatMap(function ($order) {
            return $order->purchaseOrderProducts->map(function ($item) use ($order) {
                $brand = optional($item->product)->brand;

                return [
                    'brand' => $brand,
                    'order_id' => $order->id,
                    'quantity' => $item->quantity ?? 0,
                    'price' => $item->price ?? 0,
                    'total' => ($item->quantity ?? 0) * ($item->price ?? 0),
                ];
            });
        })
            ->filter(function ($item) {
                return !empty($item['brand']); // remove null brands
            })
            ->groupBy('brand')
            ->map(function ($items, $brand) {
                return [
                    'brand' => $brand,
                    'total_orders' => $items->pluck('order_id')->unique()->count(),
                    'total_sales' => $items->sum('quantity'),
                    'total_revenue' => $items->sum('total'),
                ];
            })
            ->values();


        // want to count ready_to_ship and shipped orders group by brand so that i can show brands total orders, pending orders and completed orders
        $shipmentOrders = SalesOrder::with('orderedProducts.product')
            ->whereIn('status', ['ready_to_ship', 'shipped']) // fetch only relevant orders
            ->latest()
            ->get();

        $shipmentOrders = $shipmentOrders->flatMap(function ($order) {
            return $order->orderedProducts->map(function ($item) use ($order) {
                $brand = optional($item->product)->brand;

                return [
                    'brand' => $brand,
                    'order_id' => $order->id,
                    'order_status' => $order->status,
                ];
            });
        })
            ->filter(fn($item) => !empty($item['brand'])) // remove null brands
            ->groupBy('brand')
            ->map(function ($items, $brand) {
                return [
                    'brand' => $brand,
                    'total_orders' => $items->pluck('order_id')->unique()->count(),
                    'pending_orders' => $items->where('order_status', 'ready_to_ship')->pluck('order_id')->unique()->count(),
                    'completed_orders' => $items->where('order_status', 'shipped')->pluck('order_id')->unique()->count(),
                ];
            })
            ->values();


        return view('index', compact('purchaseOrders', 'shipmentOrders', 'brandWiseStocks', 'brandSummary', 'salesOrdersByBrand', 'vendorCodes', 'orders', 'packagingOrders', 'readyToShipOrders', 'invoices', 'customersCount', 'vendorsCount', 'salesOrdersCount', 'purchaseOrdersCount', 'productsCount', 'warehouseCount', 'readyToShipOrdersCount', 'readyToPackageOrdersCount'));
    }
}
