<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Reza Admin',
            'email' => 'admin@retailify.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Kasir Retailify',
            'email' => 'kasir@retailify.test',
            'password' => Hash::make('password'),
            'role' => 'kasir',
        ]);

        $minuman = Category::create(['name' => 'Minuman']);
        $makanan = Category::create(['name' => 'Makanan Ringan']);

        Supplier::create([
            'name' => 'PT Sumber Makmur Jaya',
            'phone' => '081234567890',
            'address' => 'Jl. Lintas Timur, Jambi',
        ]);

        Product::create([
            'category_id' => $minuman->id,
            'sku' => 'PRD-001',
            'name' => 'Susu UHT Full Cream 250ml',
            'buy_price' => 5000,
            'sell_price' => 6500,
            'stock' => 25,
            'min_stock' => 5,
        ]);

        Product::create([
            'category_id' => $makanan->id,
            'sku' => 'PRD-002',
            'name' => 'Roti Tawar Kupas',
            'buy_price' => 11000,
            'sell_price' => 14000,
            'stock' => 15,
            'min_stock' => 3,
        ]);
    }
}