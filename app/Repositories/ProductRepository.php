<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ProductRepository implements ProductRepositoryInterface
{
    public function all(): Collection
    {
        return Product::all();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Product::latest()->paginate($perPage);
    }

    public function find(int $id): ?Product
    {
        return Product::find($id);
    }

    public function findByCode(string $code): ?Product
    {
        return Product::where('code', $code)->first();
    }

    public function create(array $data): Product
    {
        $product = Product::create($data);
        $this->clearCache();
        return $product;
    }

    public function update(int $id, array $data): bool
    {
        $product = $this->find($id);
        if (!$product) {
            return false;
        }
        $result = $product->update($data);
        $this->clearCache();
        return $result;
    }

    public function delete(int $id): bool
    {
        $product = $this->find($id);
        if (!$product) {
            return false;
        }
        $result = $product->delete();
        $this->clearCache();
        return $result;
    }

    public function search(string $term): Collection
    {
        return Product::search($term)->get();
    }

    public function getActiveProducts(): Collection
    {
        return Cache::remember('products.active', 3600, function () {
            return Product::active()->get();
        });
    }

    protected function clearCache(): void
    {
        Cache::forget('products.active');
    }
}

