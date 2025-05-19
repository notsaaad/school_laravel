<?php

namespace App\Providers;

use App\Models\role;
use App\Models\order;

use App\Observers\orderObserve;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

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

        Gate::define('has', function ($user, $permissions) {
            $role = role::find($user->role_id);
            $userPermissions = json_decode($role->permissions ?? '[]', true);

            // دعم array أو string مفرد
            if (is_array($permissions)) {
                return count(array_intersect($permissions, $userPermissions)) > 0;
            }

            return in_array($permissions, $userPermissions);
        });


        Blade::if('hasAny', function (...$permissions) {
          $role = role::find(auth()->user()->role_id);
          $userPermissions = json_decode($role->permissions ?? '[]', true);
          return count(array_intersect($permissions, $userPermissions)) > 0;
      });

        View::composer('*', function ($view) {
            app()->setLocale(getLocale());
        });


    }
}
