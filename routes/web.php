<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Root redirect
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('billing.create');
    }
    return redirect()->route('login');
});

// Protected Routes
Route::middleware(['auth'])->group(function () {

    // Products - Stock Manager and Super Admin
    Route::middleware(['role:super-admin,stock-manager'])->group(function () {
        Route::resource('products', ProductController::class);
        Route::get('/api/products/search', [ProductController::class, 'search'])->name('api.products.search');
    });

    // Customers - All authenticated users
    Route::resource('customers', CustomerController::class);
    Route::get('/customers/{customer}/ledger', [CustomerController::class, 'ledger'])->name('customers.ledger');
    Route::get('/api/customers/search', [CustomerController::class, 'search'])->name('api.customers.search');

    // Billing - Sales Man and Super Admin
    Route::middleware(['role:super-admin,sales-man'])->group(function () {
        Route::resource('billing', BillingController::class)->except(['update']);
        Route::put('/billing/{billing}', [BillingController::class, 'update'])->name('billing.update');
        Route::get('/billing/{billing}/pdf', [BillingController::class, 'pdf'])->name('billing.pdf');
        Route::get('/api/billing/products/search', [BillingController::class, 'searchProducts'])->name('api.billing.products.search');
    });

    // Reports - Super Admin only (can be extended)
    Route::middleware(['role:super-admin'])->group(function () {
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
            Route::get('/stock', [ReportController::class, 'stock'])->name('stock');
            Route::get('/customer-statement', [ReportController::class, 'customerStatement'])->name('customer-statement');
        });
    });
});
