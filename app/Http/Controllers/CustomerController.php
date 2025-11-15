<?php

namespace App\Http\Controllers;

use App\DTOs\CustomerDTO;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Services\CustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function __construct(
        private CustomerService $customerService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $customers = $this->customerService->getPaginatedCustomers(15);
        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        $dto = CustomerDTO::fromArray($request->validated());
        $this->customerService->createCustomer($dto);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $customer = $this->customerService->getCustomer($id);
        if (!$customer) {
            abort(404);
        }
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $customer = $this->customerService->getCustomer($id);
        if (!$customer) {
            abort(404);
        }
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, int $id): RedirectResponse
    {
        $dto = CustomerDTO::fromArray($request->validated());
        $this->customerService->updateCustomer($id, $dto);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->customerService->deleteCustomer($id);

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    /**
     * Show customer ledger
     */
    public function ledger(int $id): View
    {
        $ledger = $this->customerService->getCustomerLedger($id);
        if (!$ledger) {
            abort(404);
        }
        return view('customers.ledger', $ledger);
    }

    /**
     * Search customers (API endpoint)
     */
    public function search(Request $request): JsonResponse
    {
        $term = $request->get('q', '');
        $customers = $this->customerService->searchCustomers($term);
        
        return response()->json($customers);
    }
}
