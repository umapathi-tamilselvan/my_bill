<?php

namespace App\Providers;

use App\Repositories\BillingRepository;
use App\Repositories\Contracts\BillingRepositoryInterface;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\CustomerRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind Repository Interfaces to Implementations
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(BillingRepositoryInterface::class, BillingRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Define permission gates
        Gate::define('access-products', function ($user) {
            return $user->hasPermission('access-products') || $user->isSuperAdmin();
        });

        Gate::define('access-customers', function ($user) {
            return $user->hasPermission('access-customers') || $user->isSuperAdmin();
        });

        Gate::define('access-billing', function ($user) {
            return $user->hasPermission('access-billing') || $user->isSuperAdmin();
        });

        Gate::define('access-reports', function ($user) {
            return $user->hasPermission('access-reports') || $user->isSuperAdmin();
        });

        Gate::define('manage-products', function ($user) {
            return $user->hasPermission('manage-products') || $user->isSuperAdmin();
        });

        Gate::define('manage-customers', function ($user) {
            return $user->hasPermission('manage-customers') || $user->isSuperAdmin();
        });
    }
}
