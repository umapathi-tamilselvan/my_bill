<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function all(): Collection
    {
        return Customer::all();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Customer::latest()->paginate($perPage);
    }

    public function find(int $id): ?Customer
    {
        return Customer::with('bills')->find($id);
    }

    public function findByCode(string $code): ?Customer
    {
        return Customer::where('code', $code)->first();
    }

    public function create(array $data): Customer
    {
        return Customer::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $customer = $this->find($id);
        if (!$customer) {
            return false;
        }
        return $customer->update($data);
    }

    public function delete(int $id): bool
    {
        $customer = $this->find($id);
        if (!$customer) {
            return false;
        }
        return $customer->delete();
    }

    public function search(string $term): Collection
    {
        return Customer::search($term)->get();
    }

    public function getActiveCustomers(): Collection
    {
        return Customer::active()->get();
    }
}

