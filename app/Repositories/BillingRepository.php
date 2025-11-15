<?php

namespace App\Repositories;

use App\Models\Bill;
use App\Repositories\Contracts\BillingRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class BillingRepository implements BillingRepositoryInterface
{
    public function all(): Collection
    {
        return Bill::with(['customer', 'items.product'])->latest()->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Bill::with(['customer', 'items.product'])
            ->latest()
            ->paginate($perPage);
    }

    public function find(int $id): ?Bill
    {
        return Bill::with(['customer', 'items.product'])->find($id);
    }

    public function findByBillNumber(string $billNumber): ?Bill
    {
        return Bill::with(['customer', 'items.product'])
            ->where('bill_number', $billNumber)
            ->first();
    }

    public function create(array $data): Bill
    {
        return DB::transaction(function () use ($data) {
            $items = $data['items'] ?? [];
            unset($data['items']);

            $bill = Bill::create($data);

            foreach ($items as $itemData) {
                $bill->items()->create($itemData);
            }

            return $bill->load(['customer', 'items.product']);
        });
    }

    public function update(int $id, array $data): bool
    {
        $bill = $this->find($id);
        if (!$bill) {
            return false;
        }

        return DB::transaction(function () use ($bill, $data) {
            if (isset($data['items'])) {
                $bill->items()->delete();
                foreach ($data['items'] as $itemData) {
                    $bill->items()->create($itemData);
                }
                unset($data['items']);
            }

            return $bill->update($data);
        });
    }

    public function delete(int $id): bool
    {
        $bill = $this->find($id);
        if (!$bill) {
            return false;
        }
        return $bill->delete();
    }

    public function getByDateRange(string $startDate, string $endDate): Collection
    {
        return Bill::with(['customer', 'items.product'])
            ->dateRange($startDate, $endDate)
            ->get();
    }

    public function getByCustomer(int $customerId): Collection
    {
        return Bill::with(['customer', 'items.product'])
            ->where('customer_id', $customerId)
            ->latest()
            ->get();
    }

    public function getSalesReport(string $startDate, string $endDate): array
    {
        return Bill::dateRange($startDate, $endDate)
            ->select(
                DB::raw('DATE(bill_date) as date'),
                DB::raw('COUNT(*) as total_bills'),
                DB::raw('SUM(total_amount) as total_sales'),
                DB::raw('SUM(paid_amount) as total_paid')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();
    }
}

