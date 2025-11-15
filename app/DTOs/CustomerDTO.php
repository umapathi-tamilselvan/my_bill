<?php

namespace App\DTOs;

class CustomerDTO
{
    public function __construct(
        public ?int $id = null,
        public ?string $code = null,
        public string $name = '',
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $address = null,
        public ?string $city = null,
        public ?string $state = null,
        public ?string $zipCode = null,
        public float $openingBalance = 0.0,
        public bool $isActive = true,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            code: $data['code'] ?? null,
            name: $data['name'] ?? '',
            email: $data['email'] ?? null,
            phone: $data['phone'] ?? null,
            address: $data['address'] ?? null,
            city: $data['city'] ?? null,
            state: $data['state'] ?? null,
            zipCode: $data['zip_code'] ?? $data['zipCode'] ?? null,
            openingBalance: (float) ($data['opening_balance'] ?? $data['openingBalance'] ?? 0),
            isActive: (bool) ($data['is_active'] ?? $data['isActive'] ?? true),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'zip_code' => $this->zipCode,
            'opening_balance' => $this->openingBalance,
            'is_active' => $this->isActive,
        ];
    }
}

