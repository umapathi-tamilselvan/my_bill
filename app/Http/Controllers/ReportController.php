<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\BillingRepositoryInterface;
use App\Services\BillingService;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        private BillingService $billingService,
        private StockService $stockService,
        private BillingRepositoryInterface $billingRepository
    ) {}

    /**
     * Display sales report
     */
    public function sales(Request $request): View
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $report = $this->billingService->getSalesReport($startDate, $endDate);
        $bills = $this->billingRepository->getByDateRange($startDate, $endDate);

        $summary = [
            'total_bills' => $bills->count(),
            'total_sales' => $bills->sum('total_amount'),
            'total_paid' => $bills->sum('paid_amount'),
            'total_tax' => $bills->sum('tax_amount'),
            'total_discount' => $bills->sum('discount_amount'),
        ];

        return view('reports.sales', compact('report', 'bills', 'summary', 'startDate', 'endDate'));
    }

    /**
     * Display stock report
     */
    public function stock(): View
    {
        $stockReport = $this->stockService->getStockReport();
        
        $summary = [
            'total_products' => $stockReport->count(),
            'total_stock_value' => $stockReport->sum('stock_value'),
            'low_stock' => $stockReport->filter(fn($item) => $item['stock'] < 10)->count(),
        ];

        return view('reports.stock', compact('stockReport', 'summary'));
    }

    /**
     * Display customer statement
     */
    public function customerStatement(Request $request): View
    {
        $customerId = $request->get('customer_id');
        
        if (!$customerId) {
            return view('reports.customer-statement', ['ledger' => null]);
        }

        $ledger = app(\App\Services\CustomerService::class)->getCustomerLedger($customerId);
        
        return view('reports.customer-statement', compact('ledger'));
    }
}
