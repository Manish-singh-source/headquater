<?php

namespace App\Providers;

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
            $receivedProductsCount = SalesOrder::where('status', 'received')->count();
            $packagingListCount = SalesOrder::where('status', 'ready_to_package')->count();

            $view->with(compact('readyToShipCount', 'receivedProductsCount', 'packagingListCount'));
        });
    }
}
