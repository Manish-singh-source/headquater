<?php

namespace App\Providers;

use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
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
            $receivedProductsCount = PurchaseOrder::where('status', 'pending')
                ->whereHas('vendorPI', function ($query) {
                    $query->where('status', 'pending');
                })
                ->count();

            $packagingListCount = SalesOrder::where('status', 'ready_to_package')->count();

            $view->with(compact('readyToShipCount', 'receivedProductsCount', 'packagingListCount'));
        });
    }
}
