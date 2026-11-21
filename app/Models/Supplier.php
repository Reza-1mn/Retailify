<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    // Pastikan 'image' masuk ke dalam array $fillable:
    protected $fillable = [
        'name',
        'image',
        'contact_person',
        'phone',
        'email',
        'address',
    ];

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}