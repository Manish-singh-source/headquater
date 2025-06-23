<?php

use App\Http\Controllers\AccessController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PlaceOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\Warehouse;
use App\Http\Controllers\WarehouseController;
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

//Access Control
// Staff List
Route::get('/staff',[AccessController::class, 'staffList'])->name('staff');
// Add Staff 
Route::get('/add-staff',[AccessController::class, 'addStaff'])->name('add-staff');
// Staff  Detail
Route::get('/staff-detail',[AccessController::class, 'staffDetail'])->name('staff-detail');
// Role List
Route::get('/role',[AccessController::class, 'roleList'])->name('role');
// Add Role 
Route::get('/add-role',[AccessController::class, 'addRole'])->name('add-role');

// All Vendor List Page 
Route::get('/vendor', [VendorController::class, 'vendorList'])->name('vendor');
// Create Vendor
Route::get('/create-vendor', [VendorController::class, 'createVendor'])->name('create-vendor');
// Vendor Details Page
Route::get('/vendor-details', [VendorController::class, 'vendorDetails'])->name('vendor-details');
// Vendor Order View
Route::get('/vendor-order-view', [VendorController::class, 'vendorOrderView'])->name('vendor-order-view');
// Form fill to store in database and after display on main List
Route::post('/vendor/add', [VendorController::class, 'addVendor'])->name('add_vendor');
// Edit Vendor {id}
Route::get('/vendor/edit/{id}', [VendorController::class, 'editVendor'])->name('edit-vendor');
// Update Vendor {id}
Route::put('/vendor/update/{id}', [VendorController::class, 'updateVendor'])->name('update-vendor');
// Delet Vendor {id}
Route::delete('/vendor/delete/{id}', [VendorController::class, 'deleteVendor'])->name('delete-vendor');
// Vendor Detail {id}
Route::get('/vendor/{id}', [VendorController::class, 'detailVendor'])->name('detail-vendor');


// Place Order
Route::get('/assign-order', [PlaceOrderController::class, 'assignOrder'])->name('assign-order');
// Place Order To Vendor
Route::get('/assign-order-to-vendor', [PlaceOrderController::class, 'assignOrderToVendor'])->name('assign-order-to-vendor');

// Warehouse List
Route::get('/warehouse', [WarehouseController::class, 'warehouseList'])->name('warehouse');
// Create Warehouse List
Route::get('/create-warehouse', [WarehouseController::class, 'createWarehouse'])->name('warehouse.create');
Route::post('/create-warehouse', [WarehouseController::class, 'storeWarehouse'])->name('warehouse.store');
// Warehouse Details List
Route::get('/warehouse-detail/{id}', [WarehouseController::class, 'warehouseDetail'])->name('warehouse.detail');
Route::delete('/warehouse/delete/{id}', [WarehouseController::class, 'deleteWarehouse'])->name('warehouse.delete');
Route::get('/warehouse-edit/{id}', [WarehouseController::class, 'warehouseEdit'])->name('warehouse.edit');
Route::put('/warehouse-update/{id}', [WarehouseController::class, 'warehouseUpdate'])->name('warehouse.update');

// All Order page
Route::get('/order',[OrderController::class, 'orderList'])->name('order');
// Add Order page
Route::get('/add-order',[OrderController::class, 'addOrder'])->name('add-order');


// Report Details List
Route::get('/vendor-purchase-history', [ReportController::class, 'vendorPurchaseHistory'])->name('vendor-purchase-history');
Route::get('/inventory-stock-history', [ReportController::class, 'inventoryStockHistory'])->name('inventory-stock-history');
Route::get('/customer-sales-history', [ReportController::class, 'customerSalesHistory'])->name('customer-sales-history');


// Customer
Route::get('/ecommerce-customers', function () {
    return view('ecommerce-customers');
})->name('ecommerce-customers');
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
