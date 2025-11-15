<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'opening_balance',
        'is_active',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the bills for the customer.
     */
    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    /**
     * Scope a query to only include active customers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to search customers.
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('code', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%");
        });
    }

    /**
     * Calculate total balance (opening + bills total - paid)
     */
    public function getTotalBalanceAttribute()
    {
        $totalBills = $this->bills()->sum('total_amount');
        $totalPaid = $this->bills()->sum('paid_amount');
        return $this->opening_balance + $totalBills - $totalPaid;
    }
}

