<?php

namespace App\Providers;

use App\Models\order;
use Illuminate\Foundation\AliasLoader;

use App\Models\role;
use App\Observers\orderObserve;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {


        Paginator::useBootstrapFive();

        Gate::define('has', function ($user, $role) {
            $user_role = role::find(auth()->user()->role_id);

            if (is_null($user_role)) {
                abort(403, 'لا يوجد لديك صلاحية ');
            }

            return in_array($role, (json_decode($user_role->permissions)));
        });

        View::composer('*', function ($view) {
            app()->setLocale(getLocale());
        });

        
    }
}
