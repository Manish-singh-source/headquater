<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Show registration form
     *
     * @return \Illuminate\View\View
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->intended('/');
        }

        return view('auth.register');
    }

    /**
     * Register a new user
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'phoneNo' => 'required|string|regex:/^[0-9]{10,}$/|unique:users,phone',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
        ], [
            'firstName.required' => 'First name is required.',
            'lastName.required' => 'Last name is required.',
            'phoneNo.required' => 'Phone number is required.',
            'phoneNo.regex' => 'Phone number must be at least 10 digits.',
            'phoneNo.unique' => 'This phone number is already registered.',
            'email.unique' => 'This email is already registered.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must contain uppercase, lowercase, number, and special character.',
            'password.confirmed' => 'Passwords do not match.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $user = User::create([
                'fname' => trim($request->firstName),
                'lname' => trim($request->lastName),
                'phone' => $request->phoneNo,
                'email' => strtolower(trim($request->email)),
                'password' => Hash::make($request->password),
                'status' => '0', // Inactive by default, admin must activate
            ]);

            DB::commit();

            // Log activity
            activity()
                ->performedOn($user)
                ->withProperties(['email' => $user->email, 'phone' => $user->phone])
                ->event('registered')
                ->log('New user registered');

            return redirect()->route('login')
                ->with('success', 'Registration successful. Please wait for admin approval.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Registration failed. Please try again.')
                ->withInput();
        }
    }

    /**
     * Show login form
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->intended('/');
        }

        return view('auth.login');
    }

    /**
     * Handle user login
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        try {
            $user = User::where('email', strtolower($request->email))->first();

            if (!$user) {
                return redirect()->back()
                    ->withErrors(['email' => 'The provided credentials do not match.'])
                    ->withInput();
            }

            if ($user->status !== '1') {
                return redirect()->back()
                    ->withErrors(['email' => 'Your account is not active. Please contact administrator.'])
                    ->withInput();
            }

            if (Auth::attempt(['email' => strtolower($request->email), 'password' => $request->password])) {
                $request->session()->regenerate();

                // Log activity
                activity()
                    ->performedOn($user)
                    ->event('logged_in')
                    ->log('User logged in');

                return redirect()->intended('/')->with('success', 'Login successful.');
            }

            return redirect()->back()
                ->withErrors(['email' => 'The provided credentials do not match.'])
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Login failed. Please try again.')
                ->withInput();
        }
    }

    /**
     * Handle user logout
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Log activity
        if ($user) {
            activity()
                ->performedOn($user)
                ->event('logged_out')
                ->log('User logged out');
        }

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }

    /**
     * Display dashboard
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // Count statistics
            $customersCount = Customer::count();
            $vendorsCount = Vendor::count();
            $productsCount = Product::count();
            $warehouseCount = Warehouse::count();
            $readyToShipOrdersCount = SalesOrder::where('status', 'ready_to_ship')->count();
            $readyToPackageOrdersCount = SalesOrder::where('status', 'ready_to_package')->count();

            // Calculate order product counts
            $salesOrdersCount = DB::table('sales_order_products')->count();
            $purchaseOrdersCount = DB::table('purchase_order_products')->count();

            // Recent Purchase Orders
            $purchaseOrders = PurchaseOrder::with('purchaseOrderProducts')
                ->latest()
                ->limit(4)
                ->get();

            // Recent Sales Orders
            $orders = SalesOrder::with('customerGroup', 'orderedProducts.product')
                ->latest()
                ->limit(4)
                ->get();

            // Sales Orders by Brand
            $salesOrdersByBrand = $this->getSalesOrdersByBrand($orders);

            // Brand Summary (Purchase Orders)
            $brandSummary = $this->getBrandSummary($purchaseOrders);

            // Shipment Orders Summary
            $shipmentOrders = $this->getShipmentOrders();

            // Warehouse Inventory by Brand
            $brandWiseStocks = $this->getBrandWiseStocks();

            // Recent Packaging Orders
            $packagingOrders = SalesOrder::where('status', 'ready_to_package')
                ->with('customerGroup')
                ->latest()
                ->limit(4)
                ->get();

            // Recent Ready To Ship Orders
            $readyToShipOrders = SalesOrder::where('status', 'ready_to_ship')
                ->with('customerGroup')
                ->latest()
                ->limit(4)
                ->get();

            // Recent Invoices
            $invoices = Invoice::with(['warehouse', 'customer', 'salesOrder'])
                ->latest()
                ->limit(4)
                ->get();

            // Vendor Codes
            $vendorCodes = $purchaseOrders->flatMap(function ($po) {
                return $po->purchaseOrderProducts?->pluck('vendor_code') ?? collect();
            })->unique()->values();

            return view('index', compact(
                'purchaseOrders',
                'shipmentOrders',
                'brandWiseStocks',
                'brandSummary',
                'salesOrdersByBrand',
                'vendorCodes',
                'orders',
                'packagingOrders',
                'readyToShipOrders',
                'invoices',
                'customersCount',
                'vendorsCount',
                'salesOrdersCount',
                'purchaseOrdersCount',
                'productsCount',
                'warehouseCount',
                'readyToShipOrdersCount',
                'readyToPackageOrdersCount'
            ));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error loading dashboard: ' . $e->getMessage());
        }
    }

    /**
     * Get sales orders grouped by brand with status summary
     *
     * @param \Illuminate\Database\Eloquent\Collection $orders
     * @return \Illuminate\Support\Collection
     */
    private function getSalesOrdersByBrand($orders)
    {
        return $orders->flatMap(function ($order) {
            return $order->orderedProducts->map(function ($item) use ($order) {
                $brand = optional($item->product)->brand;

                return [
                    'brand' => $brand,
                    'order_id' => $order->id,
                    'order_status' => $order->status ?? 'pending',
                ];
            });
        })
            ->filter(function ($item) {
                return !empty($item['brand']);
            })
            ->groupBy('brand')
            ->map(function ($items, $brand) {
                $orderIds = $items->pluck('order_id')->unique();

                return [
                    'brand' => $brand,
                    'total_orders' => $orderIds->count(),
                    'pending_orders' => $items->where('order_status', 'pending')
                        ->pluck('order_id')->unique()->count(),
                    'completed_orders' => $items->where('order_status', 'completed')
                        ->pluck('order_id')->unique()->count(),
                ];
            })
            ->values();
    }

    /**
     * Get brand summary from purchase orders
     *
     * @param \Illuminate\Database\Eloquent\Collection $purchaseOrders
     * @return \Illuminate\Support\Collection
     */
    private function getBrandSummary($purchaseOrders)
    {
        return $purchaseOrders->flatMap(function ($order) {
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
                return !empty($item['brand']);
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
    }

    /**
     * Get shipment orders grouped by brand
     *
     * @return \Illuminate\Support\Collection
     */
    private function getShipmentOrders()
    {
        $orders = SalesOrder::with('orderedProducts.product')
            ->whereIn('status', ['ready_to_ship', 'shipped'])
            ->latest()
            ->get();

        return $orders->flatMap(function ($order) {
            return $order->orderedProducts->map(function ($item) use ($order) {
                $brand = optional($item->product)->brand;

                return [
                    'brand' => $brand,
                    'order_id' => $order->id,
                    'order_status' => $order->status,
                ];
            });
        })
            ->filter(fn($item) => !empty($item['brand']))
            ->groupBy('brand')
            ->map(function ($items, $brand) {
                return [
                    'brand' => $brand,
                    'total_orders' => $items->pluck('order_id')->unique()->count(),
                    'pending_orders' => $items->where('order_status', 'ready_to_ship')
                        ->pluck('order_id')->unique()->count(),
                    'completed_orders' => $items->where('order_status', 'shipped')
                        ->pluck('order_id')->unique()->count(),
                ];
            })
            ->values();
    }

    /**
     * Get brand-wise warehouse stocks with total values
     *
     * @return \Illuminate\Support\Collection
     */
    private function getBrandWiseStocks()
    {
        $stocks = WarehouseStock::with('product')
            ->latest()
            ->get();

        return $stocks->map(function ($stock) {
            $brand = optional($stock->product)->brand;
            $price = optional($stock->product)->price ?? 0;

            return [
                'brand' => $brand,
                'quantity' => $stock->available_quantity ?? 0,
                'price' => $price,
                'total_value' => ($stock->available_quantity ?? 0) * $price,
            ];
        })
            ->filter(function ($item) {
                return !empty($item['brand']);
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
    }
}
