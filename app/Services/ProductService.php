<?php

namespace App\Services;

use App\DTOs\ProductDTO;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Str;

class ProductService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function getAllProducts()
    {
        return $this->productRepository->all();
    }

    public function getPaginatedProducts(int $perPage = 15)
    {
        return $this->productRepository->paginate($perPage);
    }

    public function getProduct(int $id): ?ProductDTO
    {
        $product = $this->productRepository->find($id);
        return $product ? ProductDTO::fromArray($product->toArray()) : null;
    }

    public function createProduct(ProductDTO $dto): ProductDTO
    {
        $data = $dto->toArray();
        
        // Auto-generate code if not provided
        if (empty($data['code'])) {
            $data['code'] = $this->generateProductCode();
        }

        $product = $this->productRepository->create($data);
        return ProductDTO::fromArray($product->toArray());
    }

    public function updateProduct(int $id, ProductDTO $dto): bool
    {
        $data = $dto->toArray();
        unset($data['id'], $data['code']); // Don't allow updating ID or code
        return $this->productRepository->update($id, $data);
    }

    public function deleteProduct(int $id): bool
    {
        return $this->productRepository->delete($id);
    }

    public function searchProducts(string $term)
    {
        return $this->productRepository->search($term);
    }

    public function getActiveProducts()
    {
        return $this->productRepository->getActiveProducts();
    }

    protected function generateProductCode(): string
    {
        $prefix = 'PRD';
        $lastProduct = $this->productRepository->all()->sortByDesc('id')->first();
        
        if ($lastProduct && $lastProduct->code) {
            $lastNumber = (int) Str::after($lastProduct->code, $prefix);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }
}

