<?php

namespace App\Services;

use App\DTOs\CustomerDTO;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use Illuminate\Support\Str;

class CustomerService
{
    public function __construct(
        private CustomerRepositoryInterface $customerRepository
    ) {}

    public function getAllCustomers()
    {
        return $this->customerRepository->all();
    }

    public function getPaginatedCustomers(int $perPage = 15)
    {
        return $this->customerRepository->paginate($perPage);
    }

    public function getCustomer(int $id): ?CustomerDTO
    {
        $customer = $this->customerRepository->find($id);
        return $customer ? CustomerDTO::fromArray($customer->toArray()) : null;
    }

    public function createCustomer(CustomerDTO $dto): CustomerDTO
    {
        $data = $dto->toArray();
        
        // Auto-generate code if not provided
        if (empty($data['code'])) {
            $data['code'] = $this->generateCustomerCode();
        }

        $customer = $this->customerRepository->create($data);
        return CustomerDTO::fromArray($customer->toArray());
    }

    public function updateCustomer(int $id, CustomerDTO $dto): bool
    {
        $data = $dto->toArray();
        unset($data['id'], $data['code']); // Don't allow updating ID or code
        return $this->customerRepository->update($id, $data);
    }

    public function deleteCustomer(int $id): bool
    {
        return $this->customerRepository->delete($id);
    }

    public function searchCustomers(string $term)
    {
        return $this->customerRepository->search($term);
    }

    public function getActiveCustomers()
    {
        return $this->customerRepository->getActiveCustomers();
    }

    public function getCustomerLedger(int $customerId)
    {
        $customer = $this->customerRepository->find($customerId);
        if (!$customer) {
            return null;
        }

        $bills = $customer->bills()->with('items.product')->latest()->get();
        
        return [
            'customer' => $customer,
            'opening_balance' => $customer->opening_balance,
            'bills' => $bills,
            'total_balance' => $customer->total_balance,
        ];
    }

    protected function generateCustomerCode(): string
    {
        $prefix = 'CUST';
        $lastCustomer = $this->customerRepository->all()->sortByDesc('id')->first();
        
        if ($lastCustomer && $lastCustomer->code) {
            $lastNumber = (int) Str::after($lastCustomer->code, $prefix);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }
}

