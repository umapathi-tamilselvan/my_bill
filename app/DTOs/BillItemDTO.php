<?php

namespace App\DTOs;

class BillItemDTO
{
    public function __construct(
        public ?int $id = null,
        public ?int $productId = null,
        public string $productName = '',
        public string $productCode = '',
        public int $quantity = 0,
        public float $unitPrice = 0.0,
        public float $taxRate = 0.0,
        public float $taxAmount = 0.0,
        public float $discountAmount = 0.0,
        public float $totalAmount = 0.0,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            productId: $data['product_id'] ?? $data['productId'] ?? null,
            productName: $data['product_name'] ?? $data['productName'] ?? '',
            productCode: $data['product_code'] ?? $data['productCode'] ?? '',
            quantity: (int) ($data['quantity'] ?? 0),
            unitPrice: (float) ($data['unit_price'] ?? $data['unitPrice'] ?? 0),
            taxRate: (float) ($data['tax_rate'] ?? $data['taxRate'] ?? 0),
            taxAmount: (float) ($data['tax_amount'] ?? $data['taxAmount'] ?? 0),
            discountAmount: (float) ($data['discount_amount'] ?? $data['discountAmount'] ?? 0),
            totalAmount: (float) ($data['total_amount'] ?? $data['totalAmount'] ?? 0),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->productId,
            'product_name' => $this->productName,
            'product_code' => $this->productCode,
            'quantity' => $this->quantity,
            'unit_price' => $this->unitPrice,
            'tax_rate' => $this->taxRate,
            'tax_amount' => $this->taxAmount,
            'discount_amount' => $this->discountAmount,
            'total_amount' => $this->totalAmount,
        ];
    }
}

