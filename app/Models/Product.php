<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'price',
        'stock',
        'unit',
        'barcode',
        'tax_rate',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'tax_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the bill items for the product.
     */
    public function billItems()
    {
        return $this->hasMany(BillItem::class);
    }

    /**
     * Get the stock movements for the product.
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to search products.
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('code', 'like', "%{$term}%")
              ->orWhere('barcode', 'like', "%{$term}%");
        });
    }
}

