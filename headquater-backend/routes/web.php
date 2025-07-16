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
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\RoleController;
use App\Models\CustomerGroup;

// Route::get('/', function () {
//     return view('index');
// })->name('index');
// Route::middleware('IsAdmin')->group(function() {
//     Route::get('/', [CustomerController::class, 'Customercount'])->name('index');
// });

// index
// view
// create
// store
// edit
// update
// delete/destroy

Route::get('/', [CustomerController::class, 'index'])->name('index');

Route::controller(LocationController::class)->group(function () {
    Route::get('/countries', 'getCountries');
    Route::get('/states', 'getStates');
    Route::get('/cities', 'getCities');
});



// Customer Group Controller
Route::controller(CustomerGroupController::class)->group(function () {
    Route::get('/customer-groups', 'index')->name('customer.groups.index');
    Route::get('/create-customer-groups', 'create')->name('customer.groups.create');
    Route::post('/store-customer-groups', 'store')->name('customer.groups.store');
    Route::delete('/delete-customer-groups/{id}', 'destroy')->name('customer.groups.destroy');
    Route::get('/view-customer-groups/{id}', 'view')->name('customer.groups.view');
});


// Warehouse List
Route::controller(WarehouseController::class)->group(function () {
    Route::get('/warehouses', 'index')->name('warehouse.index');
    Route::get('/create-warehouses', 'create')->name('warehouse.create');
    Route::post('/warehouses', 'store')->name('warehouse.store');
    Route::get('/warehouses/{id}', 'edit')->name('warehouse.edit');
    Route::put('/warehouse/{id}', 'update')->name('warehouse.update');
    Route::delete('/warehouses/{id}', 'destroy')->name('warehouse.destroy');
    Route::get('/warehouses/view/{id}', 'view')->name('warehouse.view');

    Route::post('/warehouse/toggle-status', [WarehouseController::class, 'toggleStatus'])->name('warehouse.toggleStatus');
});



// Vendors
Route::controller(VendorController::class)->group(function () {
    Route::get('/vendors', 'index')->name('vendor.index');
    Route::get('/create-vendors', 'create')->name('vendor.create');
    Route::post('/vendors', 'store')->name('vendor.store');
    Route::get('/vendors/{id}', 'edit')->name('vendor.edit');
    Route::put('/vendor/{id}', 'update')->name('vendor.update');
    Route::delete('/vendors/{id}', 'destroy')->name('vendor.destroy');
    Route::get('/vendors/view/{id}', 'view')->name('vendor.view');

    Route::get('/vendor-order-view/{id}', 'vendorOrderView')->name('vendor-order-view');
    Route::get('/single-vendor-order-view/{orderId}/{vendorCode}', 'singleVendorOrderView')->name('single-vendor-order-view');
});
Route::post('/vendor/toggle-status', [VendorController::class, 'toggleStatus'])->name('vendor.toggleStatus');
Route::delete('/vendor/delete-selected', [VendorController::class, 'deleteSelected'])->name('delete.selected.vendor');
Route::delete('/products/delete-selected', [ProductController::class, 'deleteSelected'])->name('delete.selected.product');
Route::delete('/warehouse/delete-selected', [WarehouseController::class, 'deleteSelected'])->name('delete.selected.warehouse');




// Authentication
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'loginCustomer')->name('login');
    Route::post('/login', 'loginAuthCheckCustomerData')->name('login.auth.check');
    Route::get('/register', 'registerCustomer')->name('register');
    Route::post('/register', 'registerCustomerData')->name('register.store');
    Route::get('/logout', 'logout')->name('logout');
});


//Access Control

Route::controller(RoleController::class)->group(function () {
    // Roles
    Route::get('/role', 'index')->name('role.index');
    Route::get('/create-role', 'create')->name('role.create');
    Route::post('/store-role', 'store')->name('role.store');
    Route::get('/edit-role/{id}', 'edit')->name('role.edit');
    Route::put('/update-role/{id}', 'update')->name('role.update');
    Route::delete('/delete-role/{id}', 'destroy')->name('role.destroy');
    Route::get('/view-staff/{id}', 'view')->name('role.view');
});

Route::controller(StaffController::class)->group(function () {
    // Staff 
    Route::get('/staff', 'index')->name('staff.index');
    Route::get('/create-staff', 'create')->name('staff.create');
    Route::post('/store-staff', 'store')->name('staff.store');
    Route::get('/edit-staff/{id}', 'edit')->name('staff.edit');
    Route::put('/update-staff/{id}', 'update')->name('staff.update');
    Route::delete('/delete-staff/{id}', 'destroy')->name('staff.destroy');
    Route::get('/view-staff/{id}', 'view')->name('staff.view');
});


// Product controller
Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index')->name('products.index');
    Route::get('/create-products', 'create')->name('products.create');
    Route::post('/products', 'store')->name('products.store');
    Route::get('/products/{id}', 'edit')->name('products.edit');
    Route::put('/products/{id}', 'update')->name('products.update');
    Route::delete('/products/{id}', 'destroy')->name('products.destroy');
    Route::get('/products/view/{id}', 'view')->name('products.view');
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



// Place Order
Route::get('/assign-order', [PlaceOrderController::class, 'assignOrder'])->name('assign-order');
// Place Order To Vendor
Route::get('/assign-order-to-vendor', [PlaceOrderController::class, 'assignOrderToVendor'])->name('assign-order-to-vendor');

Route::get('/purchase-order', [PurchaseOrderController::class, 'index'])->name('purchase.order.index');
Route::get('/purchase-order-view/{id}', [PurchaseOrderController::class, 'view'])->name('purchase.order.view');



// All Order page
Route::controller(OrderController::class)->group(function () {
    // Route::get('/order', 'orderList')->name('order');
    // Route::get('/add-order', 'addOrder')->name('add-order');
    // Route::post('/process-order', 'processOrder')->name('process.order');
    // Route::post('/process-block-order', 'processBlockOrder')->name('process.block.order');
    // Route::get('/download-block-order-csv', 'downloadBlockedCSV')->name('download.order.excel');
    // Route::get('/customer-order-view/{id}', 'viewOrder')->name('customer-order-view');
    // Route::delete('/customer-order-delete/{id}', 'deleteOrder')->name('delete.order');

    Route::get('/order', 'index')->name('order.index');
    Route::get('/create-order', 'create')->name('order.create');
    Route::post('/check-products-stock', 'checkProductsStock')->name('check.order.stock');
    Route::post('/store-order', 'store')->name('order.store');
    Route::get('/view-order/{id}', 'view')->name('order.view');
    Route::delete('/delete-order/{id}', 'destroy')->name('order.delete');

    Route::get('/download-block-order-csv', 'downloadBlockedCSV')->name('download.order.excel');
});









// Later Tasks 

// Report Details List
Route::controller(ReportController::class)->group(function () {
    Route::get('/vendor-purchase-history', 'vendorPurchaseHistory')->name('vendor-purchase-history');
    Route::get('/inventory-stock-history', 'inventoryStockHistory')->name('inventory-stock-history');
    Route::get('/customer-sales-history', 'customerSalesHistory')->name('customer-sales-history');
});








// Later Tasks


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
