<?php

namespace App\Http\Controllers;

use App\DTOs\BillDTO;
use App\DTOs\BillItemDTO;
use App\Http\Requests\StoreBillRequest;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\BillingService;
use App\Services\CustomerService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function __construct(
        private BillingService $billingService,
        private CustomerService $customerService,
        private ProductRepositoryInterface $productRepository
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $bills = $this->billingService->getPaginatedBills(15);
        return view('billing.index', compact('bills'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $customers = $this->customerService->getActiveCustomers();
        $products = $this->productRepository->getActiveProducts();
        return view('billing.create', compact('customers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBillRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        
        // Prepare items
        $items = [];
        foreach ($validated['items'] as $item) {
            $product = $this->productRepository->find($item['product_id']);
            if ($product) {
                $items[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_code' => $product->code,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'tax_rate' => $item['tax_rate'] ?? $product->tax_rate,
                    'discount_amount' => $item['discount_amount'] ?? 0,
                ];
            }
        }

        $billData = [
            'customer_id' => $validated['customer_id'] ?? null,
            'bill_date' => $validated['bill_date'],
            'payment_status' => $validated['payment_status'] ?? 'paid',
            'paid_amount' => $validated['paid_amount'] ?? 0,
            'notes' => $validated['notes'] ?? null,
            'items' => $items,
        ];

        $dto = BillDTO::fromArray($billData);
        $bill = $this->billingService->createBill($dto);

        return redirect()->route('billing.show', $bill->id)
            ->with('success', 'Bill created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $bill = $this->billingService->getBill($id);
        if (!$bill) {
            abort(404);
        }
        $billModel = $this->billingRepository->find($id);
        return view('billing.show', ['bill' => $bill, 'billModel' => $billModel]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $bill = $this->billingService->getBill($id);
        if (!$bill) {
            abort(404);
        }
        $customers = $this->customerService->getActiveCustomers();
        $products = $this->productRepository->getActiveProducts();
        return view('billing.edit', compact('bill', 'customers', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreBillRequest $request, int $id): RedirectResponse
    {
        $validated = $request->validated();
        
        // Prepare items
        $items = [];
        foreach ($validated['items'] as $item) {
            $product = $this->productRepository->find($item['product_id']);
            if ($product) {
                $items[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_code' => $product->code,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'tax_rate' => $item['tax_rate'] ?? $product->tax_rate,
                    'discount_amount' => $item['discount_amount'] ?? 0,
                ];
            }
        }

        $billData = [
            'customer_id' => $validated['customer_id'] ?? null,
            'bill_date' => $validated['bill_date'],
            'payment_status' => $validated['payment_status'] ?? 'paid',
            'paid_amount' => $validated['paid_amount'] ?? 0,
            'notes' => $validated['notes'] ?? null,
            'items' => $items,
        ];

        $dto = BillDTO::fromArray($billData);
        $this->billingService->updateBill($id, $dto);

        return redirect()->route('billing.show', $id)
            ->with('success', 'Bill updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->billingService->deleteBill($id);

        return redirect()->route('billing.index')
            ->with('success', 'Bill deleted successfully.');
    }

    /**
     * Generate PDF invoice
     */
    public function pdf(int $id)
    {
        $bill = $this->billingService->getBill($id);
        if (!$bill) {
            abort(404);
        }

        $pdf = Pdf::loadView('billing.invoice-pdf', compact('bill'));
        return $pdf->download("invoice-{$bill->billNumber}.pdf");
    }

    /**
     * Search products for POS (API endpoint)
     */
    public function searchProducts(Request $request): JsonResponse
    {
        $term = $request->get('q', '');
        $products = $this->productRepository->search($term)
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'code' => $product->code,
                    'name' => $product->name,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'tax_rate' => $product->tax_rate,
                    'unit' => $product->unit,
                ];
            });
        
        return response()->json($products);
    }
}
