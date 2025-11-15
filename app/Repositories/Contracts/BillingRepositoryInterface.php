<?php

namespace App\Repositories\Contracts;

use App\Models\Bill;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface BillingRepositoryInterface
{
    public function all(): Collection;
    public function paginate(int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?Bill;
    public function findByBillNumber(string $billNumber): ?Bill;
    public function create(array $data): Bill;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getByDateRange(string $startDate, string $endDate): Collection;
    public function getByCustomer(int $customerId): Collection;
    public function getSalesReport(string $startDate, string $endDate): array;
}

