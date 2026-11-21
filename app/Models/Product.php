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
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
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
}