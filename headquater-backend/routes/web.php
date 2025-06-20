<?php

use App\Http\Controllers\AuthController;
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