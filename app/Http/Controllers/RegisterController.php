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
use App\Models\SalesOrderProduct;
use App\Models\PurchaseOrderProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
            if( Auth::user()->status !== '1') {
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
        $orders = SalesOrder::with('customerGroup')->limit(4)->latest()->get();

        // Recent Packaging List
        $packagingOrders = SalesOrder::where('status', 'ready_to_package')->with('customerGroup')->limit(4)->latest()->get();

        // Recent Ready To Ship Orders
        $readyToShipOrders = SalesOrder::where('status', 'ready_to_ship')->with('customerGroup')->limit(4)->latest()->get();

        // Invoices Lists
        $invoices = Invoice::with(['warehouse', 'customer', 'salesOrder'])->limit(4)->latest()->get();

        return view('index', compact('purchaseOrders', 'vendorCodes', 'orders', 'packagingOrders', 'readyToShipOrders', 'invoices', 'customersCount', 'vendorsCount', 'salesOrdersCount', 'purchaseOrdersCount', 'productsCount', 'warehouseCount', 'readyToShipOrdersCount', 'readyToPackageOrdersCount'));
    }
}