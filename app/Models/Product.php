<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'sku', 'name', 'buy_price', 'sell_price', 'stock', 'min_stock'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function stockLogs() {
        return $this->hasMany(StockLog::class);
    }
}