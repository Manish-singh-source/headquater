<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlaceOrderController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'loginCustomer']);

// All Vendor List Page 
Route::get('/vendor', [VendorController::class, 'vendorList'])->name('vendor');
// Create Vendor
Route::get('/create-vendor', [VendorController::class, 'createVendor'])->name('create-vendor');
// Vendor Details Page
Route::get('/vendor-details', [VendorController::class, 'vendorDetails'])->name('vendor-details');
// Vendor Order View
Route::get('/vendor-order-view', [VendorController::class, 'vendorOrderView'])->name('vendor-order-view');
// Place Order
Route::get('/assign-order', [PlaceOrderController::class, 'assignOrder'])->name('assign-order');
// Place Order To Vendor
Route::get('/assign-order-to-vendor', [PlaceOrderController::class, 'assignOrderToVendor'])->name('assign-order-to-vendor');

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
