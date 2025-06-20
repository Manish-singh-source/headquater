<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('index');

// Authentication
Route::get('/login', [AuthController::class, 'loginCustomer'])->name('login');
Route::post('/login', [AuthController::class, 'loginAuthCheckCustomerData'])->name('login.auth.check');
Route::get('/register', [AuthController::class, 'registerCustomer'])->name('register');
Route::post('/register', [AuthController::class, 'registerCustomerData'])->name('register.store');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// All Vendor List Page 
Route::get('/vendor', [VendorController::class, 'vendorList'])->name('vendor');
// Create Vendor
Route::get('/create-vendor', [VendorController::class, 'createVendor'])->name('create-vendor');
// Vendor Details Page
Route::get('/vendor-details', [VendorController::class, 'vendorDetails'])->name('vendor-details');
// Vendor Order View
Route::get('/vendor-order-view', [VendorController::class, 'vendorOrderView'])->name('vendor-order-view');
// Customer 
Route::get('/ecommerce-customers', function () {
    return view('ecommerce-customers');
})->name('ecommerce-customers');;
Route::get('/add-customer', function () {
    return view('add-customer');
})->name('add-customer');
Route::get('/customer-detail', function () {
    return view('customer-detail');
})->name('customer-detail');
Route::get('/customer-order-view', function () {
    return view('customer-order-view');
})->name('customer-order-view');


// Product
Route::get('/products', function () {
    return view('products');
})->name('products');
Route::get('/add-product', function () {
    return view('add-product');
})->name('add-product');
