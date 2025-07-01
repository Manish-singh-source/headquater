<?php

use App\Http\Controllers\Warehouse;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AccessController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\PlaceOrderController;

// Route::get('/', function () {
//     return view('index');
// })->name('index');
Route::get('/', [CustomerController::class, 'Customercount'])->name('index');

Route::get('/countries', [LocationController::class, 'getCountries']);
Route::get('/states', [LocationController::class, 'getStates']);
Route::get('/cities', [LocationController::class, 'getCities']);


// Authentication
Route::get('/login', [AuthController::class, 'loginCustomer'])->name('login');
Route::post('/login', [AuthController::class, 'loginAuthCheckCustomerData'])->name('login.auth.check');
Route::get('/register', [AuthController::class, 'registerCustomer'])->name('register');
Route::post('/register', [AuthController::class, 'registerCustomerData'])->name('register.store');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');



//Access Control
// Staff List
Route::get('/staff', [AccessController::class, 'staffList'])->name('staff');
Route::get('/add-staff', [AccessController::class, 'addStaff'])->name('add-staff');
Route::get('/staff-detail/{id}', [AccessController::class, 'staffDetail'])->name('staff-detail');
Route::post('/add-staff', [AccessController::class, 'storeStaff'])->name('store-staff');
Route::delete('/staff/delete/{id}', [AccessController::class, 'deletestaff'])->name('staff.delete');
Route::get('/staff/edit/{id}', [AccessController::class, 'editstaff'])->name('staff.edit');
Route::put('/staff/update/{id}', [AccessController::class, 'updatestaff'])->name('staff.update');





// Role List
Route::get('/role', [AccessController::class, 'roleList'])->name('role');
Route::get('/add-role', [AccessController::class, 'addRole'])->name('add-role');
Route::post('/store-role', [AccessController::class, 'storeRole'])->name('store.role');
Route::delete('/role-delete/{id}', [AccessController::class, 'roleDelete'])->name('role.delete');
Route::get('/role-edit/{id}', [AccessController::class, 'roleEdit'])->name('role.edit');
Route::put('/role-update/{id}', [AccessController::class, 'roleUpdate'])->name('role.update');



// Customer
Route::get('/customers', [CustomerController::class, 'customerList'])->name('customers');
Route::get('/add-customer', [CustomerController::class, 'addCustomer'])->name('add-customer');
Route::post('/customers/store', [CustomerController::class, 'storeCustomer'])->name('store_customer');
Route::get('/customer/detail/{id}', [CustomerController::class, 'detailCustomer'])->name('customer-detail');
Route::get('/customers/edit/{id}', [CustomerController::class, 'editCustomer'])->name('edit_customer');
Route::put('/customer/update/{id}', [CustomerController::class, 'updateCustomer'])->name('update-customer');
Route::delete('/customers/delete/{id}', [CustomerController::class, 'deleteCustomer'])->name('delete-customer');
Route::get('/customer-group', function () {
    return view('customer.customer-group');
})->name('customer-group');



// All Vendor List Page 
Route::get('/vendor', [VendorController::class, 'vendorList'])->name('vendor');
Route::get('/create-vendor', [VendorController::class, 'createVendor'])->name('vendor.create');
Route::post('/vendor/add', [VendorController::class, 'addVendor'])->name('vendor.add');
Route::get('/vendor/{id}', [VendorController::class, 'detailVendor'])->name('vendor.detail');
Route::put('/vendor/update/{id}', [VendorController::class, 'updateVendor'])->name('vendor.update');
Route::delete('/vendor/delete/{id}', [VendorController::class, 'deleteVendor'])->name('vendor.delete');
Route::get('/vendor/edit/{id}', [VendorController::class, 'editVendor'])->name('edit-vendor');

Route::get('/vendor-order-view', [VendorController::class, 'vendorOrderView'])->name('vendor-order-view');




// Place Order
Route::get('/assign-order', [PlaceOrderController::class, 'assignOrder'])->name('assign-order');
// Place Order To Vendor
Route::get('/assign-order-to-vendor', [PlaceOrderController::class, 'assignOrderToVendor'])->name('assign-order-to-vendor');

// Warehouse List
Route::get('/warehouse', [WarehouseController::class, 'warehouseList'])->name('warehouse');
Route::get('/create-warehouse', [WarehouseController::class, 'createWarehouse'])->name('warehouse.create');
Route::post('/create-warehouse', [WarehouseController::class, 'storeWarehouse'])->name('warehouse.store');
Route::get('/warehouse-detail/{id}', [WarehouseController::class, 'warehouseDetail'])->name('warehouse.detail');
Route::delete('/warehouse/delete/{id}', [WarehouseController::class, 'deleteWarehouse'])->name('warehouse.delete');
Route::get('/warehouse-edit/{id}', [WarehouseController::class, 'warehouseEdit'])->name('warehouse.edit');
Route::put('/warehouse-update/{id}', [WarehouseController::class, 'warehouseUpdate'])->name('warehouse.update');

// All Order page
Route::get('/order', [OrderController::class, 'orderList'])->name('order');
Route::get('/add-order', [OrderController::class, 'addOrder'])->name('add-order');


// Report Details List
Route::get('/vendor-purchase-history', [ReportController::class, 'vendorPurchaseHistory'])->name('vendor-purchase-history');
Route::get('/inventory-stock-history', [ReportController::class, 'inventoryStockHistory'])->name('inventory-stock-history');
Route::get('/customer-sales-history', [ReportController::class, 'customerSalesHistory'])->name('customer-sales-history');





// Route::get('/customer-detail', function () {
//     return view('customer-detail');
// })->name('customer-detail');
Route::get('/customer-order-view', function () {
    return view('customer.customer-order-view');
})->name('customer-order-view');


// Product
Route::get('/products', function () {
    return view('products');
})->name('products');
Route::get('/add-product', function () {
    return view('add-product');
})->name('add-product');

// invoice
Route::get('/invoices', function () {
    return view('invoices');
})->name('invoices');
Route::get('/create-invoice', function () {
    return view('create-invoice');
})->name('create-invoice');
Route::get('/invoices-details', function () {
    return view('invoices-details');
})->name('invoices-details');

// recev products
Route::get('/received-products', function () {
    return view('received-products');
})->name('received-products');
Route::get('/packaging-list', function () {
    return view('packaging-list');
})->name('packaging-list');
Route::get('/packing-products-list', function () {
    return view('packing-products-list');
})->name('packing-products-list');
Route::get('/raise-a-ticket-form', function () {
    return view('raise-a-ticket-form');
})->name('raise-a-ticket-form');
Route::get('/ready-to-ship', function () {
    return view('ready-to-ship');
})->name('ready-to-ship');
Route::get('/ready-to-ship-detail', function () {
    return view('ready-to-ship-detail');
})->name('ready-to-ship-detail');
Route::get('/raise-a-ticket', function () {
    return view('raise-a-ticket');
})->name('raise-a-ticket');
Route::get('/track-order', function () {
    return view('track-order');
})->name('track-order');
