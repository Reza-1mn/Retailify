<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $defaultCategories = [
            'Minuman',
            'Makanan Ringan',
            'Sembako & Bahan Pokok',
            'Kebutuhan Rumah Tangga',
            'Perlengkapan Mandi & Kebersihan',
            'Bumbu & Bahan Masakan',
            'Produk Susu & Olahan',
            'Roti, Kue & Bakery',
            'Rokok & Korek',
            'Alat Tulis & Lain-lain',
        ];

        foreach ($defaultCategories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}