<?php

namespace App\DTOs;

class ProductDTO
{
    public function __construct(
        public ?int $id = null,
        public ?string $code = null,
        public string $name = '',
        public ?string $description = null,
        public float $price = 0.0,
        public int $stock = 0,
        public string $unit = 'pcs',
        public ?string $barcode = null,
        public float $taxRate = 0.0,
        public bool $isActive = true,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            code: $data['code'] ?? null,
            name: $data['name'] ?? '',
            description: $data['description'] ?? null,
            price: (float) ($data['price'] ?? 0),
            stock: (int) ($data['stock'] ?? 0),
            unit: $data['unit'] ?? 'pcs',
            barcode: $data['barcode'] ?? null,
            taxRate: (float) ($data['tax_rate'] ?? $data['taxRate'] ?? 0),
            isActive: (bool) ($data['is_active'] ?? $data['isActive'] ?? true),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'unit' => $this->unit,
            'barcode' => $this->barcode,
            'tax_rate' => $this->taxRate,
            'is_active' => $this->isActive,
        ];
    }
}

