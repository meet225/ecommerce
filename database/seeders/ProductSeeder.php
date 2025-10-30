<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::truncate();

        $products = [
            ['name' => 'Laptop', 'price' => 60000, 'stock_quantity' => 10],
            ['name' => 'Smartphone', 'price' => 25000, 'stock_quantity' => 20],
            ['name' => 'Headphones', 'price' => 3000, 'stock_quantity' => 50],
            ['name' => 'Keyboard', 'price' => 1200, 'stock_quantity' => 40],
            ['name' => 'Monitor', 'price' => 15000, 'stock_quantity' => 15],
        ];

        foreach ($products as $data) {
            Product::create($data);
        }
    }
}
