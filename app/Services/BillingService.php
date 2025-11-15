<?php

namespace App\Services;

use App\DTOs\BillDTO;
use App\DTOs\BillItemDTO;
use App\Models\Product;
use App\Repositories\Contracts\BillingRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BillingService
{
    public function __construct(
        private BillingRepositoryInterface $billingRepository,
        private ProductRepositoryInterface $productRepository,
        private StockService $stockService
    ) {}

    public function getAllBills()
    {
        return $this->billingRepository->all();
    }

    public function getPaginatedBills(int $perPage = 15)
    {
        return $this->billingRepository->paginate($perPage);
    }

    public function getBill(int $id): ?BillDTO
    {
        $bill = $this->billingRepository->find($id);
        if (!$bill) {
            return null;
        }

        $items = $bill->items->map(function ($item) {
            return BillItemDTO::fromArray($item->toArray());
        })->toArray();

        $data = $bill->toArray();
        $data['items'] = $items;
        
        return BillDTO::fromArray($data);
    }

    public function createBill(BillDTO $dto): BillDTO
    {
        return DB::transaction(function () use ($dto) {
            // Generate bill number if not provided
            if (empty($dto->billNumber)) {
                $dto->billNumber = $this->generateBillNumber();
            }

            // Calculate totals
            $this->calculateBillTotals($dto);

            // Prepare bill data
            $billData = $dto->toArray();
            $items = $billData['items'];
            unset($billData['items']);

            // Create bill
            $bill = $this->billingRepository->create(array_merge($billData, [
                'items' => $items,
            ]));

            // Update stock for each item
            foreach ($items as $itemData) {
                $product = $this->productRepository->find($itemData['product_id']);
                if ($product) {
                    $this->stockService->adjustStock(
                        $product,
                        $itemData['quantity'],
                        'out',
                        $bill,
                        "Sold via bill {$bill->bill_number}"
                    );
                }
            }

            return $this->getBill($bill->id);
        });
    }

    public function updateBill(int $id, BillDTO $dto): bool
    {
        return DB::transaction(function () use ($id, $dto) {
            $oldBill = $this->billingRepository->find($id);
            if (!$oldBill) {
                return false;
            }

            // Revert old stock movements
            foreach ($oldBill->items as $item) {
                $product = $this->productRepository->find($item->product_id);
                if ($product) {
                    $this->stockService->adjustStock(
                        $product,
                        $item->quantity,
                        'in',
                        null,
                        "Reverted from bill {$oldBill->bill_number}"
                    );
                }
            }

            // Calculate new totals
            $this->calculateBillTotals($dto);

            // Update bill
            $billData = $dto->toArray();
            $items = $billData['items'];
            unset($billData['items']);

            $result = $this->billingRepository->update($id, array_merge($billData, [
                'items' => $items,
            ]));

            // Update stock for new items
            $bill = $this->billingRepository->find($id);
            foreach ($items as $itemData) {
                $product = $this->productRepository->find($itemData['product_id']);
                if ($product) {
                    $this->stockService->adjustStock(
                        $product,
                        $itemData['quantity'],
                        'out',
                        $bill,
                        "Sold via bill {$bill->bill_number}"
                    );
                }
            }

            return $result;
        });
    }

    public function deleteBill(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $bill = $this->billingRepository->find($id);
            if (!$bill) {
                return false;
            }

            // Revert stock movements
            foreach ($bill->items as $item) {
                $product = $this->productRepository->find($item->product_id);
                if ($product) {
                    $this->stockService->adjustStock(
                        $product,
                        $item->quantity,
                        'in',
                        null,
                        "Reverted from deleted bill {$bill->bill_number}"
                    );
                }
            }

            return $this->billingRepository->delete($id);
        });
    }

    public function getSalesReport(string $startDate, string $endDate): array
    {
        return $this->billingRepository->getSalesReport($startDate, $endDate);
    }

    protected function calculateBillTotals(BillDTO $dto): void
    {
        $subtotal = 0;
        $totalTax = 0;
        $totalDiscount = 0;

        foreach ($dto->items as $item) {
            $itemSubtotal = $item->quantity * $item->unitPrice;
            $itemDiscount = $item->discountAmount;
            $itemSubtotalAfterDiscount = $itemSubtotal - $itemDiscount;
            $itemTax = $itemSubtotalAfterDiscount * ($item->taxRate / 100);
            
            $item->taxAmount = $itemTax;
            $item->totalAmount = $itemSubtotalAfterDiscount + $itemTax;

            $subtotal += $itemSubtotal;
            $totalTax += $itemTax;
            $totalDiscount += $itemDiscount;
        }

        $dto->subtotal = $subtotal;
        $dto->taxAmount = $totalTax;
        $dto->discountAmount = $totalDiscount;
        $dto->totalAmount = $subtotal - $totalDiscount + $totalTax;

        // Set payment status
        if ($dto->paidAmount >= $dto->totalAmount) {
            $dto->paymentStatus = 'paid';
        } elseif ($dto->paidAmount > 0) {
            $dto->paymentStatus = 'partial';
        } else {
            $dto->paymentStatus = 'unpaid';
        }
    }

    protected function generateBillNumber(): string
    {
        $prefix = 'BILL';
        $date = now()->format('Ymd');
        $lastBill = $this->billingRepository->all()->sortByDesc('id')->first();
        
        if ($lastBill && $lastBill->bill_number) {
            $lastDate = Str::substr($lastBill->bill_number, 4, 8);
            if ($lastDate === $date) {
                $lastNumber = (int) Str::after($lastBill->bill_number, $date);
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }
        } else {
            $newNumber = 1;
        }

        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}

