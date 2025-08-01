<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\Staff;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
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
        Paginator::useBootstrap();

        Gate::define('PermissionChecker', function ($user, $userPermission) {
            // return $user->hasPermission('update_profile', '');

            $role = Role::find($user->role_id);

            $permissions = json_decode($role->permissions, true);

            foreach ($permissions as $permission) {
                if ($permission === $userPermission) {
                    return true;
                }
            }


            // json_encode($role->permissions);
            // // dd($role->permissions);

            // foreach ($role->permissions as $role) {
            //     if ($role == 'update_profile') {
            //         return true;
            //     }
            // }
            // return $role->perimissions === 'customer-handler';
        });
    }
}
