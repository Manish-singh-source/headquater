<?php

use App\Http\Controllers\ReadyToShip;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PackagingController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\SKUMappingController;
use App\Http\Controllers\TrackOrderController;
use App\Http\Controllers\CustomerGroupController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReceivedProductsController;

// Authentication
Route::controller(RegisterController::class)->group(function () {
    Route::get('/login', 'loginCustomer')->name('login');
    Route::get('/register', 'registerCustomer')->name('register');
    Route::post('/register', 'registerCustomerData')->name('register.store');
    Route::post('/login', 'loginAuthCheckCustomerData')->name('login.auth.check');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(LocationController::class)->group(function () {
    Route::get('/countries', 'getCountries');
    Route::get('/states', 'getStates');
    Route::get('/cities', 'getCities');
});

Route::middleware('RolePermission:customer-handler')->group(function () {

    Route::get('/', [CustomerController::class, 'index'])->name('index');

    // Customer Group Controller
    Route::controller(CustomerGroupController::class)->group(function () {
        Route::get('/customer-groups', 'index')->name('customer.groups.index');
        Route::get('/create-customer-groups', 'create')->name('customer.groups.create');
        Route::post('/store-customer-groups', 'store')->name('customer.groups.store');
        Route::get('/edit-customer-groups/{id}', 'edit')->name('customer.groups.edit');
        Route::put('/update-customer-groups/{id}', 'update')->name('customer.groups.update');
        Route::delete('/delete-customer-groups/{id}', 'destroy')->name('customer.groups.destroy');
        Route::get('/view-customer-groups/{id}', 'view')->name('customer.groups.view');
        // Route::post('/import-large-csv', 'importLargeCsv')->name('import-large-csv');
    });

    // Customer
    Route::controller(CustomerController::class)->group(function () {
        Route::get('/customer-create/{g_id}', 'create')->name('customer.create');
        Route::post('/customer-store-bulk/{g_id}', 'storeBulk')->name('customer.store.bulk');
        Route::post('/customers/store', 'store')->name('customer.store');
        Route::get('/customers/edit/{id}/{group_id}', 'edit')->name('customer.edit');
        Route::put('/customer/update/{id}', 'update')->name('customer.update');
        Route::delete('/customers/delete/{id}', 'delete')->name('customer.delete');
        // Route::get('/customers/detail/{id}', 'detail')->name('customers.detail');
        Route::get('/customer-detail/{id}', 'detail')->name('customer.detail');
        Route::get('/user-profile', 'profile')->name('user-profile');
        Route::put('/user-profile/update/{id}', 'updateuser')->name('user.update');
        Route::post('/customer/toggle-status', 'toggleStatus')->name('customer.toggleStatus');
        Route::delete('/customers/delete-selected', 'deleteSelected')->name('delete.selected.customers');
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
        Route::post('/warehouse/toggle-status', 'toggleStatus')->name('warehouse.toggleStatus');
        Route::delete('/warehouse/delete-selected', 'deleteSelected')->name('delete.selected.warehouse');
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
        // Route::get('/vendor-order-view/{id}', 'vendorOrderView')->name('vendor-order-view');
        Route::get('/single-vendor-order-view/{purchaseOrderId}/{vendorCode}', 'singleVendorOrderView')->name('single-vendor-order-view');
        Route::post('/vendor/toggle-status', 'toggleStatus')->name('vendor.toggleStatus');
        Route::delete('/vendor/delete-selected', 'deleteSelected')->name('delete.selected.vendor');
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
        // Route::get('/view-staff/{id}', 'view')->name('role.view');
        Route::delete('/role/delete-selected', 'deleteSelected')->name('delete.selected.role');
        Route::post('/role/toggle-status', 'toggleStatus')->name('role.toggleStatus');
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
        Route::delete('/staff/delete-selected', 'deleteSelected')->name('delete.selected.staff');
        Route::post('/staff/toggle-status', 'toggleStatus')->name('staff.toggleStatus');
    });

    // Product controller
    Route::controller(ProductController::class)->group(function () {
        Route::get('/products', 'index')->name('products.index');
        Route::get('/create-products', 'create')->name('products.create');
        Route::post('/products', 'store')->name('products.store');
        // Route::get('/products/{id}', 'edit')->name('product.edit');
        Route::put('/products', 'update')->name('products.update');
        Route::get('/products/{id}/edit','editProduct')->name('product.edit');
        Route::get('/download-product-sheet','downloadProductSheet')->name('download.product.sheet');
        Route::post('/products/update','updateProduct')->name('product.update');

        Route::delete('/product-order/{id}', 'destroy')->name('product.delete');
        // Route::delete('/products/{id}', 'destroy')->name('products.destroy');
        // Route::get('/products/view/{id}', 'view')->name('products.view');
        Route::delete('/products/delete-selected', 'deleteSelected')->name('delete.selected.product');
    });

    // All Order page
    Route::controller(OrderController::class)->group(function () {
        Route::get('/order', 'index')->name('order.index');
        Route::get('/create-order', 'create')->name('order.create');
        Route::post('/store-order', 'store')->name('order.store');
        Route::get('/edit-order/{id}', 'edit')->name('order.edit');
        Route::put('/update-order', 'update')->name('order.update');
        Route::get('/view-order/{id}', 'view')->name('order.view');
        Route::delete('/delete-order/{id}', 'destroy')->name('order.delete');
        Route::delete('/order/delete-selected', 'deleteSelected')->name('delete.selected.order');
        Route::put('/change-status', 'changeStatus')->name('change.order.status');
        Route::post('/check-products-stock', 'checkProductsStock')->name('check.order.stock');
        Route::get('/download-block-order-csv', 'downloadBlockedCSV')->name('download.order.excel');
        Route::get('/products-download-po-excel', 'downloadPoExcel')->name('products.download.po.excel');
    });


    // Place Order
    Route::controller(PurchaseOrderController::class)->group(function () {
        Route::get('/purchase-order', 'index')->name('purchase.order.index');
        Route::post('/purchase-order-store', 'store')->name('purchase.order.store');
        Route::get('/purchase-order-view/{id}', 'view')->name('purchase.order.view');
        Route::post('/received-products-pi-update', 'update')->name('received.products.pi.update');
        Route::delete('/purchase-order-delete/{id}', 'delete')->name('purchase.order.delete');
        Route::post('/received-products-status', 'updateStatus')->name('received.products.status');
        Route::post('/approve-vendor-pi-request', 'approveRequest')->name('approve.vendor.pi.request');
        Route::post('/purchase-order-invoice-store', 'invoiceStore')->name('purchase.order.invoice.store');
        Route::post('/purchase-order-grn-store', 'grnStore')->name('purchase.order.grn.store');
        Route::get('/download-vendor-po-excel', 'downloadVendorPO')->name('download.vendor.po.excel');
    });

    // Report Details List
    Route::controller(ReportController::class)->group(function () {
        Route::get('/vendor-purchase-history', 'vendorPurchaseHistory')->name('vendor-purchase-history');
        Route::get('/inventory-stock-history', 'inventoryStockHistory')->name('inventory-stock-history');
        Route::get('/customer-sales-history', 'customerSalesHistory')->name('customer-sales-history');
    });

    // received products
    Route::controller(ReceivedProductsController::class)->group(function () {
        Route::get('/received-products', 'index')->name('received-products.index');
        Route::post('/received-products', 'view')->name('received-products.view');
        Route::put('/received-products', 'update')->name('received-products.update');
        Route::post('/get-vendors', 'getVendors')->name('get.vendors');
        Route::get('/download-received-products-excel', 'downloadReceivedProductsFile')->name('download.received-products.excel');
    });

    // Route::get('/received-products', function () {
    //     return view('received-products');
    // })->name('received-products');
    Route::controller(PackagingController::class)->group(function () {
        Route::get('/packaging-list', 'index')->name('packaging.list.index');
        Route::get('/packing-products-list/{id}', 'view')->name('packing.products.view');
    });

    // 
    Route::controller(ReadyToShip::class)->group(function () {
        Route::get('/ready-to-ship', 'index')->name('readyToShip.index');
        Route::get('/ready-to-ship-detail/{id}', 'view')->name('readyToShip.view');
        Route::get('/ready-to-ship-detail-view/{id}/{c_id}', 'viewDetail')->name('readyToShip.view.detail');
    });

    Route::controller(TrackOrderController::class)->group(function () {
        Route::get('/track-order', 'index')->name('trackOrder.index');
        Route::post('/track-order', 'index')->name('trackOrder.index');
    });


    Route::controller(SKUMappingController::class)->group(function () {
        Route::get('/sku-mapping', 'index')->name('sku.mapping');
        Route::post('/sku-mapping', 'store')->name('sku.mapping.store');
        Route::get('/sku-mapping-edit/{id}', 'edit')->name('sku.mapping.edit');
        Route::put('/sku-mapping-update', 'update')->name('sku.mapping.update');
        Route::delete('/sku-mapping-destroy/{id}', 'delete')->name('sku.mapping.destroy');
    });

    // invoice
    Route::controller(InvoiceController::class)->group(function () {
        Route::get('/invoices', 'index')->name('invoices');
        Route::get('/invoices/{id}', 'view')->name('invoices.view');
        // Route::get('/create-invoice', 'create')->name('invoice.create');
        // Route::post('/store-invoice', 'store')->name('invoice.store');
        // Route::get('/view-invoice/{id}', 'view')->name('invoice.view');
        // Route::delete('/delete-invoice/{id}', 'destroy')->name('invoice.delete');
        Route::get('/download-invoice-pdf/{id}', 'downloadPdf')->name('invoice.downloadPdf');

        Route::get('/create-invoice', function () {
            return view('create-invoice');
        })->name('create-invoice');
        Route::get('/invoices-details', function () {
            return view('invoices-details');
        })->name('invoices-details');
    });

    Route::view('/excel-file-formats', 'excel-file-formats')->name('excel-file-formats');
});

Route::view('/404', 'errors.404');
