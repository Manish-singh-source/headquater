<?php

use App\Http\Controllers\Warehouse;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\AccessController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\PlaceOrderController;
use App\Http\Controllers\CustomerGroupController;

// Route::get('/', function () {
//     return view('index');
// })->name('index');
// Route::middleware('IsAdmin')->group(function() {
    Route::get('/', [CustomerController::class, 'Customercount'])->name('index');
// });

Route::controller(LocationController::class)->group(function () {
    Route::get('/countries', 'getCountries');
    Route::get('/states', 'getStates');
    Route::get('/cities', 'getCities');
});


// Authentication
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'loginCustomer')->name('login');
    Route::post('/login', 'loginAuthCheckCustomerData')->name('login.auth.check');
    Route::get('/register', 'registerCustomer')->name('register');
    Route::post('/register', 'registerCustomerData')->name('register.store');
    Route::get('/logout', 'logout')->name('logout');
});


//Access Control
Route::controller(AccessController::class)->group(function () {
    // Staff 
    Route::get('/staff', 'staffList')->name('staff');
    Route::get('/add-staff', 'addStaff')->name('add-staff');
    Route::get('/staff-detail/{id}', 'staffDetail')->name('staff-detail');
    Route::post('/add-staff', 'storeStaff')->name('store-staff');
    Route::delete('/staff/delete/{id}', 'deletestaff')->name('staff.delete');
    Route::get('/staff/edit/{id}', 'editstaff')->name('staff.edit');
    Route::put('/staff/update/{id}', 'updatestaff')->name('staff.update');

    // Roles
    Route::get('/role', 'roleList')->name('role');
    Route::get('/add-role', 'addRole')->name('add-role');
    Route::post('/store-role', 'storeRole')->name('store.role');
    Route::delete('/role-delete/{id}', 'roleDelete')->name('role.delete');
    Route::get('/role-edit/{id}', 'roleEdit')->name('role.edit');
    Route::put('/role-update/{id}', 'roleUpdate')->name('role.update');
});


// Customer
Route::controller(CustomerController::class)->group(function () {
    Route::get('/groups', 'groupsList')->name('groups');
    Route::get('/customers-group-detail/{id}', 'customerGroupDetail')->name('customers.group.detail');
    Route::get('/add-customer', 'addCustomer')->name('add-customer');
    Route::post('/customers/store', 'storeCustomer')->name('store_customer');
    // Route::get('/customer/detail/{id}', 'detailCustomer')->name('customer-detail');
    Route::get('/customers/edit/{id}', 'editCustomer')->name('edit_customer');
    Route::put('/customer/update/{id}', 'updateCustomer')->name('update-customer');
    Route::delete('/customers/delete/{id}', 'deleteCustomer')->name('delete-customer');
    // Customer Group
    Route::get('/customer/detail/{id}', 'customersList')->name('customers.list');
    Route::delete('/customer-group/delete/{id}', 'deleteCustomerGroup')->name('delete.customer.group');
});
Route::get('/customer-group', function () {
    return view('customer.customer-group');
})->name('customer-group');

Route::post('/import-large-csv', [CustomerGroupController::class, 'importLargeCsv'])->name('import-large-csv');
Route::post('/customer/toggle-status', [CustomerController::class, 'toggleStatus'])->name('customer.toggleStatus');
Route::delete('/customers/delete-selected', [CustomerController::class, 'deleteSelected'])->name('delete.selected.customers');

// Vendors
Route::controller(VendorController::class)->group(function () {
    Route::get('/vendors', 'vendorList')->name('vendor');
    Route::get('/create-vendor', 'createVendor')->name('vendor.create');
    Route::post('/vendor/add', 'addVendor')->name('vendor.add');
    Route::get('/vendor/{id}', 'detailVendor')->name('vendor.detail');
    Route::put('/vendor/update/{id}', 'updateVendor')->name('vendor.update');
    Route::delete('/vendor/delete/{id}', 'deleteVendor')->name('vendor.delete');
    Route::get('/vendor/edit/{id}', 'editVendor')->name('edit-vendor');
    Route::get('/vendor-order-view', 'vendorOrderView')->name('vendor-order-view');
});
Route::post('/vendor/toggle-status', [VendorController::class, 'toggleStatus'])->name('vendor.toggleStatus');
Route::delete('/vendor/delete-selected', [VendorController::class, 'deleteSelected'])->name('delete.selected.vendor');
Route::delete('/products/delete-selected', [ProductController::class, 'deleteSelected'])->name('delete.selected.product');
Route::delete('/warehouse/delete-selected', [WarehouseController::class, 'deleteSelected'])->name('delete.selected.warehouse');



// Place Order
Route::get('/assign-order', [PlaceOrderController::class, 'assignOrder'])->name('assign-order');
// Place Order To Vendor
Route::get('/assign-order-to-vendor', [PlaceOrderController::class, 'assignOrderToVendor'])->name('assign-order-to-vendor');

// Warehouse List
Route::controller(WarehouseController::class)->group(function () {
    Route::get('/warehouse', 'warehouseList')->name('warehouse');
    Route::get('/create-warehouse', 'createWarehouse')->name('warehouse.create');
    Route::post('/create-warehouse', 'storeWarehouse')->name('warehouse.store');
    Route::get('/warehouse-detail/{id}', 'warehouseDetail')->name('warehouse.detail');
    Route::delete('/warehouse/delete/{id}', 'deleteWarehouse')->name('warehouse.delete');
    Route::get('/warehouse-edit/{id}', 'warehouseEdit')->name('warehouse.edit');
    Route::put('/warehouse-update/{id}', 'warehouseUpdate')->name('warehouse.update');
    Route::post('/warehouse/toggle-status', [WarehouseController::class, 'toggleStatus'])->name('warehouse.toggleStatus');
});

// All Order page
Route::controller(OrderController::class)->group(function () {
    Route::get('/order', 'orderList')->name('order');
    Route::get('/add-order', 'addOrder')->name('add-order');
    Route::post('/process-order', 'processOrder')->name('process.order');
    Route::post('/process-block-order', 'processBlockOrder')->name('process.block.order');
    Route::get('/download-block-order-csv', 'downloadBlockedCSV')->name('download.order.excel');
    Route::get('/customer-order-view/{id}', 'viewOrder')->name('customer-order-view');
    Route::delete('/customer-order-delete/{id}', 'deleteOrder')->name('delete.order');
});

// Report Details List
Route::controller(ReportController::class)->group(function () {
    Route::get('/vendor-purchase-history', 'vendorPurchaseHistory')->name('vendor-purchase-history');
    Route::get('/inventory-stock-history', 'inventoryStockHistory')->name('inventory-stock-history');
    Route::get('/customer-sales-history', 'customerSalesHistory')->name('customer-sales-history');
});




// Route::get('/customer-detail', function () {
//     return view('customer-detail');
// })->name('customer-detail');



// Product
Route::get('/products', [ProductController::class, 'productsList'])->name('products');
Route::get('/add-product', [ProductController::class, 'addProductPage'])->name('add-product');
Route::post('/store-products', [ProductController::class, 'storeProducts'])->name('store.products');

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
