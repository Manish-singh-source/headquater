<?php

use App\Http\Controllers\ReadyToShip;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PackagingController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\SKUMappingController;
use App\Http\Controllers\TrackOrderController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CustomerGroupController;
use App\Http\Controllers\ProductReturnController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReceivedProductsController;

Route::controller(LocationController::class)->group(function () {
    Route::get('/countries', 'getCountries');
    Route::get('/states', 'getStates');
    Route::get('/cities', 'getCities');
});

// Authentication
Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegisterForm')->name('register');
    Route::post('/register', 'register')->name('register.store');

    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login')->name('login.auth.check');

    Route::get('/logout', 'logout')->name('logout');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    // Route::get('/', [RegisterController::class, 'index'])->name('index');
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    // Access Control
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
        // Route::post('/role/toggle-status', 'toggleStatus')->name('role.toggleStatus');
    });

    Route::controller(PermissionController::class)->group(function () {
        // Permissions
        Route::get('/permission', 'index')->name('permission.index');
        Route::get('/create-permission', 'create')->name('permission.create');
        Route::post('/store-permission', 'store')->name('permission.store');
        Route::get('/edit-permission/{id}', 'edit')->name('permission.edit');
        Route::put('/update-permission/{id}', 'update')->name('permission.update');
        Route::delete('/delete-permission/{id}', 'destroy')->name('permission.destroy');
        Route::delete('/permission/delete-selected', 'deleteSelected')->name('delete.selected.permission');
        Route::post('/permission/toggle-status', 'toggleStatus')->name('permission.toggleStatus');
    });

    Route::controller(CustomerGroupController::class)->group(function () {
        Route::get('/customer-groups', 'index')->name('customer.groups.index');
        Route::get('/create-customer-groups', 'create')->name('customer.groups.create');
        Route::post('/store-customer-groups', 'store')->name('customer.groups.store');
        Route::get('/edit-customer-groups/{id}', 'edit')->name('customer.groups.edit');
        Route::put('/update-customer-groups/{id}', 'update')->name('customer.groups.update');
        Route::delete('/delete-customer-groups/{id}', 'destroy')->name('customer.groups.destroy');
        Route::get('/view-customer-groups/{id}', 'view')->name('customer.groups.view');
        Route::post('/customer-groups/toggle-status', 'toggleStatus')->name('customer.groups.toggleStatus');
        Route::delete('/customers-group/delete-selected', 'deleteSelected')->name('delete.selected.customers.group');
        Route::post('/customer-groups/bulk-status-change', 'bulkStatusChange')->name('customer.groups.bulkStatusChange');
    });

    // Customer
    Route::controller(CustomerController::class)->group(function () {
        Route::get('/customers', 'index')->name('customer.index');
        Route::get('/customer-create/{g_id}', 'create')->name('customer.create');
        Route::post('/customers/store', 'store')->name('customer.store');
        Route::post('/customers/store-individual', 'storeIndividual')->name('customer.store.individual');
        Route::get('/customers/edit/{id}/{group_id}', 'edit')->name('customer.edit');
        Route::put('/customer/update/{id}', 'update')->name('customer.update');
        Route::delete('/customers/delete/{id}', 'delete')->name('customer.delete');
        Route::get('/customer-detail/{id}', 'detail')->name('customer.detail');

        Route::post('/customer-store-bulk/{g_id}', 'storeBulk')->name('customer.store.bulk');
        // Route::get('/customers/detail/{id}', 'detail')->name('customers.detail');
        Route::get('/user-profile', 'profile')->name('user-profile');
        Route::put('/user-profile/update/{id}', 'updateuser')->name('user.update');
        Route::delete('/customers/delete-selected', 'deleteSelected')->name('delete.selected.customers');
        Route::post('/customers/toggle-status', 'toggleStatus')->name('customer.toggleStatus');
        Route::post('/customers/bulk-status-change', 'bulkStatusChange')->name('customer.bulkStatusChange');
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
        Route::post('/vendor/change-selected-status', 'changeSelectedStatus')->name('vendor.changeSelectedStatus');
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

    // Product controller
    Route::controller(ProductController::class)->group(function () {
        Route::get('/products', 'index')->name('products.index');
        Route::get('/create-products', 'create')->name('products.create');
        Route::post('/products', 'store')->name('products.store');
        Route::put('/products', 'update')->name('products.update');
        Route::get('/products/{id}/edit', 'editProduct')->name('product.edit');

        // Route::get('/products/{id}', 'edit')->name('product.edit');
        Route::get('/download-product-sheet/{id?}', 'downloadProductSheet')->name('download.product.sheet');
        Route::post('/products/update', 'updateProduct')->name('product.update');

        Route::delete('/product-order/{id}', 'destroy')->name('product.delete');
        // Route::delete('/products/{id}', 'destroy')->name('products.destroy');
        // Route::get('/products/view/{id}', 'view')->name('products.view');
        Route::delete('/products/delete-selected', 'deleteSelected')->name('delete.selected.product');
    });

    Route::controller(SKUMappingController::class)->group(function () {
        Route::get('/sku-mapping', 'index')->name('sku.mapping');
        Route::post('/sku-mapping', 'store')->name('sku.mapping.store');
        Route::get('/sku-mapping-edit/{id}', 'edit')->name('sku.mapping.edit');
        Route::put('/sku-mapping-update', 'update')->name('sku.mapping.update');
        Route::delete('/sku-mapping-destroy/{id}', 'delete')->name('sku.mapping.destroy');
    });

    // All Order page
    Route::controller(SalesOrderController::class)->group(function () {
        Route::get('/order', 'index')->name('sales.order.index');
        Route::get('/create-order', 'create')->name('sales.order.create');
        Route::post('/store-order', 'store')->name('sales.order.store');
        Route::get('/edit-order/{id}', 'edit')->name('sales.order.edit');
        Route::put('/update-order', 'update')->name('sales.order.update');
        Route::get('/view-order/{id}', 'view')->name('sales.order.view');
        Route::delete('/delete-order/{id}', 'destroy')->name('sales.order.delete');
        Route::delete('/order/delete-selected', 'deleteSelected')->name('delete.selected.order');
        Route::put('/change-status', 'changeStatus')->name('change.sales.order.status');
        Route::post('/check-products-stock', 'checkProductsStock')->name('check.sales.order.stock');
        Route::get('/download-block-order-csv', 'downloadBlockedCSV')->name('download.sales.order.excel');
        Route::get('/products-download-po-excel', 'downloadPoExcel')->name('products.download.po.excel');
        Route::get('/download-not-found-sku/{id}', 'downloadNotFoundSku')->name('download.not.found.sku.excel');
        Route::get('/download-not-found-customer/{id}', 'downloadNotFoundCustomer')->name('download.not.found.customer.excel');
        Route::get('/download-not-found-vendor/{id}', 'downloadNotFoundVendor')->name('download.not.found.vendor.excel');
        Route::post('/generate-invoice', 'generateInvoice')->name('generate.invoice');

        // Multi-warehouse auto allocation routes
        Route::post('/auto-allocate-stock/{id}', 'autoAllocateStock')->name('sales.order.auto.allocate');
        Route::get('/allocation-breakdown/{id}', 'getAllocationBreakdown')->name('sales.order.allocation.breakdown');
        Route::post('/manual-allocate', 'manualAllocate')->name('sales.order.manual.allocate');
    });

    // Place Order
    Route::controller(PurchaseOrderController::class)->group(function () {
        Route::get('/purchase-order', 'index')->name('purchase.order.index');
        Route::post('/purchase-order-store', 'store')->name('purchase.order.store');
        Route::post('/custom-purchase-order-store', 'storeCustomPurchaseOrder')->name('custom.purchase.order.store');
        Route::get('/purchase-order-view/{id}', 'view')->name('purchase.order.view');
        Route::delete('/purchase-order-delete/{id}', 'delete')->name('purchase.order.delete');
        Route::delete('/purchase-orders-delete', 'multiDelete')->name('purchase.order.bulk.delete');
        Route::delete('/purchase-order-product-delete/{id}', 'SingleProductdelete')->name('purchase.order.product.delete');
        Route::delete('/purchase-order-products-delete', 'multiProductdelete')->name('purchase.order.products.delete');
        Route::post('/approve-vendor-pi-request', 'approveRequest')->name('approve.vendor.pi.request');
        Route::post('/reject-vendor-pi-request', 'rejectRequest')->name('reject.vendor.pi.request');
        Route::post('/purchase-order-invoice-store', 'invoiceStore')->name('purchase.order.invoice.store');
        Route::post('/purchase-order-grn-store', 'grnStore')->name('purchase.order.grn.store');
        Route::get('/download-vendor-po-excel', 'downloadVendorPO')->name('download.vendor.po.excel');

        Route::get('/purchase-order-create/{purchaseId?}', 'customPurchaseCreate')->name('purchase.order.create');
        Route::post('/purchase-custom-order-store', 'customPurchaseStore')->name('store.purchase.order');
        Route::put('/change-purchase-order-status', 'changeStatus')->name('change.purchase.order.status');

        Route::post('/vendor-invoice-payment-update', 'vendorInvoicePaymentStore')->name('vendor.invoice.payment.store');
        // Return or accept packaging products
        Route::get('/vendor-product-return/{id}', 'vendorProductReturn')->name('vendor.product.return');
        Route::get('/vendor-product-accept/{id}', 'vendorProductAccept')->name('vendor.product.accept');
    });

    // Check code from here

    // received products From Vendors PI Order
    Route::controller(ReceivedProductsController::class)->group(function () {
        Route::get('/received-products', 'index')->name('received-products.index');
        Route::get('/received-products/{id}/{vendorCode}', 'view')->name('received-products.view');
        Route::put('/received-products', 'update')->name('received-products.update');
        Route::post('/received-products-status', 'updateStatus')->name('received.products.status');
        Route::post('/received-products-pi-update', 'updateRecievedProduct')->name('received.products.pi.update');
        Route::post('/get-vendors', 'getVendors')->name('get.vendors');
        Route::get('/download-received-products-excel', 'downloadReceivedProductsFile')->name('download.received-products.excel');
    });

    Route::controller(PackagingController::class)->group(function () {
        Route::get('/packaging-list', 'index')->name('packaging.list.index');
        Route::get('/packing-products-list/{id}', 'view')->name('packing.products.view');
        Route::get('/download-packing-products-excel', 'downloadPackagingProducts')->name('download.packing.products.excel');
        Route::post('/update-packaging-products', 'updatePackagingProducts')->name('update.packing.products');
    });

    //
    Route::controller(ReadyToShip::class)->group(function () {
        Route::get('/ready-to-ship', 'index')->name('readyToShip.index');
        Route::get('/ready-to-ship-detail/{id}', 'view')->name('readyToShip.view');
        Route::get('/ready-to-ship-detail-view/{id}/{c_id}', 'viewDetail')->name('readyToShip.view.detail');
        Route::get('/product-issues', 'issuesProducts')->name('exceed.shortage.products');
        Route::get('/return-accept', 'returnAccept')->name('return.accept');
        Route::get('/accept-vendor-products/{id}', 'acceptVendorProducts')->name('accept.vendor.products');
        Route::get('/return-vendor-products/{id}', 'returnVendorProducts')->name('return.vendor.products');
    });

    Route::controller(ProductReturnController::class)->group(function () {
        Route::get('/customer-returns', 'customerReturns')->name('customer.returns');
        Route::get('/create-customer-returns', 'createCustomerReturn')->name('customer.returns.create');
        Route::post('/customer-returns', 'storeCustomerReturn')->name('customer.returns.store');
        Route::get('/customer-returns-view/{id}', 'viewCustomerReturn')->name('customer.returns.view');
        Route::get('/customer-returns-edit/{id}', 'editCustomerReturn')->name('customer.returns.edit');
        Route::put('/customer-returns-update', 'updateCustomerReturn')->name('customer.returns.update');
        Route::delete('/customer-returns-delete/{id}', 'deleteCustomerReturn')->name('customer.returns.delete');
        Route::put('/customer-returns-status', 'changeCustomerReturnStatus')->name('change.customer.return.status');
    });

    // Track order
    Route::controller(TrackOrderController::class)->group(function () {
        Route::get('/track-order', 'index')->name('trackOrder.index');
        Route::post('/track-order', 'index')->name('trackOrder.index');
        Route::get('/track-order/{id}', 'view')->name('trackOrder.view');
    });

    // Report Details List
    Route::controller(ReportController::class)->group(function () {
        Route::get('/vendor-purchase-history', 'vendorPurchaseHistory')->name('vendor-purchase-history');
        Route::get('/inventory-stock-history', 'inventoryStockHistory')->name('inventory-stock-history');
        Route::get('/customer-sales-history', 'customerSalesHistory')->name('customer-sales-history');
        Route::get('/vendor-purchase-history-excel', 'vendorPurchaseHistoryExcel')->name('vendor.purchase.history.excel');
        Route::get('/inventory-stock-history-excel', 'inventoryStockHistoryExcel')->name('inventory.stock.history.excel');
        Route::get('/customer-sales-history-excel', 'customerSalesHistoryExcel')->name('customer.sales.history.excel');
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
        Route::get('/invoices-details/{id}', 'invoiceDetails')->name('invoices-details');

        // Manual Invoice Routes
        Route::get('/invoices/manual/create', 'createManualInvoice')->name('invoices.manual.create');
        Route::post('/invoices/manual/store', 'storeManualInvoice')->name('invoices.manual.store');
        Route::post('/invoices/get-products', 'getProducts')->name('invoices.get-products');
        Route::post('/invoices/check-stock', 'checkStock')->name('invoices.check-stock');

        // updating invoice details appointment, grn, dn, and payment
        Route::post('/invoice-appointment-update/{id}', 'invoiceAppointmentUpdate')->name('invoices.appointment.update');
        Route::post('/invoice-dn-update/{id}', 'invoiceDnUpdate')->name('invoice.dn.update');
        Route::post('/invoice-payment-update/{id}', 'invoicePaymentUpdate')->name('invoice.payment.update');

        // Check PO Number 
        Route::post('/check-po-number', 'checkPoNumber')->name('check.po.number');
    });

    Route::view('/excel-file-formats', 'excel-file-formats')->name('excel-file-formats');

    // Activity Log
    Route::controller(ActivityController::class)->group(function () {
        Route::get('activity-log', 'index')->name('activity.log');
    });

    // Notifications
    Route::controller(NotificationController::class)->group(function () {
        Route::get('/notifications/get', 'getNotifications')->name('notifications.get');
        Route::post('/notifications/{id}/mark-read', 'markAsRead')->name('notifications.mark-read');
        Route::post('/notifications/{id}/remove', 'removeNotification')->name('notifications.remove');
        Route::post('/notifications/mark-all-read', 'markAllAsRead')->name('notifications.mark-all-read');
    });

    Route::get('/report/lr-pending', function () {
        return redirect()->route('packaging.list.index');
    })->name('report.lr-pending');

    Route::get('/report/appt-grn-pending', function () {
        return redirect()->route('invoices');
    })->name('report.appt-grn-pending');

    Route::get('/report/appt-pending', function () {
        return redirect()->route('invoices');
    })->name('report.appt-pending');
});

Route::view('/404', 'errors.404');

// Test notification routes
Route::get('/test-notifications', function () {
    // Create some test notifications
    \App\Services\NotificationService::orderCreated('sales', 12345);
    \App\Services\NotificationService::orderCreated('purchase', 67890);
    \App\Services\NotificationService::invoiceGenerated(111, 12345);
    \App\Services\NotificationService::statusChanged('sales', 12345, 'pending', 'ready_to_ship');
    \App\Services\NotificationService::warehouseProductAdded('Test Product ABC', 50);
    \App\Services\NotificationService::productsReceived('purchase', 67890, 25);

    return redirect()->route('index')->with('success', 'Test notifications created! Check the notification dropdown.');
})->name('test.notifications');

// Test notification deletion
Route::get('/test-notification-delete', function () {
    $notifications = \App\Models\Notification::where('user_id', auth()->id())->get();
    $count = $notifications->count();

    return response()->json([
        'total_notifications' => $count,
        'notifications' => $notifications->map(function ($n) {
            return [
                'id' => $n->id,
                'title' => $n->title,
                'message' => $n->message,
                'is_read' => $n->is_read,
                'created_at' => $n->created_at,
            ];
        }),
    ]);
})->name('test.notification.delete');

// vendor code in product file so that product fixed for that vendor
// add vendor code in product
