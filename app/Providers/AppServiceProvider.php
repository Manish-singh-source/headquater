<?php

namespace App\Providers;

use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        // $this->registerPolicies();

        Gate::before(function ($user, string $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        // Share counts with all views
        View::composer('layouts.master', function ($view) {
            $readyToShipCount = SalesOrder::where('status', 'ready_to_ship')->count();

            // Count pending purchase orders with pending vendor PIs
            // Filter by warehouse if user is assigned to a specific warehouse
            $user = Auth::user();
            $userWarehouseId = $user ? $user->warehouse_id : null;

            $receivedProductsCount = PurchaseOrder::where('status', 'pending')
                ->whereHas('vendorPI', function ($query) use ($userWarehouseId) {
                    $query->where('status', 'pending');

                    // Filter by warehouse if user is assigned to a specific warehouse
                    if ($userWarehouseId) {
                        $query->where('warehouse_id', $userWarehouseId);
                    }
                })
                ->count();

            $packagingListCount = SalesOrder::where('status', 'ready_to_package')->count();

            // Count pending purchase orders
            $purchaseOrderCount = PurchaseOrder::where('status', 'pending')->count();

            // Count active sales orders (not completed)
            $salesOrderCount = SalesOrder::where('status', '!=', 'completed')->count();

            // Count unpaid or partially paid invoices
            $invoiceCount = \App\Models\Invoice::whereIn('payment_status', ['unpaid', 'partial'])->count();

            $view->with(compact('readyToShipCount', 'receivedProductsCount', 'packagingListCount', 'purchaseOrderCount', 'salesOrderCount', 'invoiceCount'));
        });
    }
}
