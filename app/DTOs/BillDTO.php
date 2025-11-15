<?php

namespace App\DTOs;

class BillDTO
{
    public function __construct(
        public ?int $id = null,
        public ?string $billNumber = null,
        public ?int $customerId = null,
        public string $billDate = '',
        public float $subtotal = 0.0,
        public float $taxAmount = 0.0,
        public float $discountAmount = 0.0,
        public float $totalAmount = 0.0,
        public string $paymentStatus = 'paid',
        public float $paidAmount = 0.0,
        public ?string $notes = null,
        public array $items = [],
    ) {}

    public static function fromArray(array $data): self
    {
        $items = [];
        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                $items[] = BillItemDTO::fromArray($item);
            }
        }

        return new self(
            id: $data['id'] ?? null,
            billNumber: $data['bill_number'] ?? $data['billNumber'] ?? null,
            customerId: $data['customer_id'] ?? $data['customerId'] ?? null,
            billDate: $data['bill_date'] ?? $data['billDate'] ?? now()->format('Y-m-d'),
            subtotal: (float) ($data['subtotal'] ?? 0),
            taxAmount: (float) ($data['tax_amount'] ?? $data['taxAmount'] ?? 0),
            discountAmount: (float) ($data['discount_amount'] ?? $data['discountAmount'] ?? 0),
            totalAmount: (float) ($data['total_amount'] ?? $data['totalAmount'] ?? 0),
            paymentStatus: $data['payment_status'] ?? $data['paymentStatus'] ?? 'paid',
            paidAmount: (float) ($data['paid_amount'] ?? $data['paidAmount'] ?? 0),
            notes: $data['notes'] ?? null,
            items: $items,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'bill_number' => $this->billNumber,
            'customer_id' => $this->customerId,
            'bill_date' => $this->billDate,
            'subtotal' => $this->subtotal,
            'tax_amount' => $this->taxAmount,
            'discount_amount' => $this->discountAmount,
            'total_amount' => $this->totalAmount,
            'payment_status' => $this->paymentStatus,
            'paid_amount' => $this->paidAmount,
            'notes' => $this->notes,
            'items' => array_map(fn($item) => $item instanceof BillItemDTO ? $item->toArray() : $item, $this->items),
        ];
    }
}

