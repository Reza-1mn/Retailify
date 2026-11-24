<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'sku',
        'name', 
        'image', 
        'buy_price', 
        'sell_price', 
        'stock', 
        'min_stock', 
        'expired_date',
        'is_active'
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'expired_date' => 'date',
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function stockLogs() {
        return $this->hasMany(StockLog::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function isExpired(): bool
    {
        return $this->expired_date !== null && $this->expired_date->isBefore(today());
    }

    public function isNearExpiry(int $days = 30): bool
    {
        if ($this->expired_date === null || $this->isExpired()) {
            return false;
        }

        return $this->expired_date->lte(today()->addDays($days));
    }
}