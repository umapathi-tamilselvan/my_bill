<?php

use App\Http\Controllers\BillingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('billing.create');
});

// Products
Route::resource('products', ProductController::class);
Route::get('/api/products/search', [ProductController::class, 'search'])->name('api.products.search');

// Customers
Route::resource('customers', CustomerController::class);
Route::get('/customers/{customer}/ledger', [CustomerController::class, 'ledger'])->name('customers.ledger');
Route::get('/api/customers/search', [CustomerController::class, 'search'])->name('api.customers.search');

// Billing
Route::resource('billing', BillingController::class)->except(['update']);
Route::put('/billing/{billing}', [BillingController::class, 'update'])->name('billing.update');
Route::get('/billing/{billing}/pdf', [BillingController::class, 'pdf'])->name('billing.pdf');
Route::get('/api/billing/products/search', [BillingController::class, 'searchProducts'])->name('api.billing.products.search');

// Reports
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
    Route::get('/stock', [ReportController::class, 'stock'])->name('stock');
    Route::get('/customer-statement', [ReportController::class, 'customerStatement'])->name('customer-statement');
});
