<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class StockService
{
    public function adjustStock(Product $product, int $quantity, string $type, $reference = null, ?string $notes = null): StockMovement
    {
        return DB::transaction(function () use ($product, $quantity, $type, $reference, $notes) {
            // Update product stock
            if ($type === 'in') {
                $product->increment('stock', $quantity);
            } else {
                $product->decrement('stock', $quantity);
            }

            $product->refresh();

            // Create stock movement record
            return StockMovement::create([
                'product_id' => $product->id,
                'type' => $type,
                'quantity' => $quantity,
                'balance_after' => $product->stock,
                'reference_type' => $reference ? get_class($reference) : null,
                'reference_id' => $reference?->id,
                'notes' => $notes,
            ]);
        });
    }

    public function getStockMovements(Product $product)
    {
        return StockMovement::where('product_id', $product->id)
            ->latest()
            ->get();
    }

    public function getStockReport()
    {
        return Product::select('id', 'code', 'name', 'stock', 'unit', 'price')
            ->orderBy('name')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'code' => $product->code,
                    'name' => $product->name,
                    'stock' => $product->stock,
                    'unit' => $product->unit,
                    'price' => $product->price,
                    'stock_value' => $product->stock * $product->price,
                ];
            });
    }
}

