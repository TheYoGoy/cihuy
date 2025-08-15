<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Single Origin Series
            [
                'name' => 'Sudut Timur Arabika Aceh Gayo', 
                'type' => 'Biji Utuh', 
                'description' => 'Single origin premium dari dataran tinggi Aceh'
            ],
            [
                'name' => 'Sudut Timur Arabika Toraja', 
                'type' => 'Biji Utuh', 
                'description' => 'Kopi arabika asli Tana Toraja'
            ],
            [
                'name' => 'Sudut Timur Arabika Kintamani', 
                'type' => 'Biji Utuh', 
                'description' => 'Arabika dari lereng Gunung Batur, Bali'
            ],
            [
                'name' => 'Sudut Timur Robusta Lampung', 
                'type' => 'Biji Utuh', 
                'description' => 'Robusta berkualitas dari Lampung'
            ],
            
            // Ground Coffee Series
            [
                'name' => 'Sudut Timur Medium Roast Ground', 
                'type' => 'Bubuk Sedang', 
                'description' => 'Kopi bubuk roasting sedang untuk drip'
            ],
            [
                'name' => 'Sudut Timur Dark Roast Ground', 
                'type' => 'Bubuk Halus', 
                'description' => 'Kopi bubuk roasting gelap untuk espresso'
            ],
            [
                'name' => 'Sudut Timur French Press Ground', 
                'type' => 'Bubuk Kasar', 
                'description' => 'Kopi bubuk kasar untuk french press'
            ],
            
            // Blend Series
            [
                'name' => 'Sudut Timur House Blend', 
                'type' => 'Bubuk Sedang', 
                'description' => 'Signature blend arabika-robusta 70:30'
            ],
            [
                'name' => 'Sudut Timur Morning Blend', 
                'type' => 'Bubuk Sedang', 
                'description' => 'Blend ringan untuk pagi hari'
            ],
            [
                'name' => 'Sudut Timur Espresso Blend', 
                'type' => 'Bubuk Halus', 
                'description' => 'Blend khusus untuk espresso dan latte'
            ],
            
            // Premium Series
            [
                'name' => 'Sudut Timur Premium Gold', 
                'type' => 'Biji Utuh', 
                'description' => 'Grade AAA premium selection'
            ],
            [
                'name' => 'Sudut Timur Luwak Coffee', 
                'type' => 'Biji Utuh', 
                'description' => 'Kopi luwak asli Jawa Timur'
            ],
            
            // Instant Series
            [
                'name' => 'Sudut Timur Instant Classic', 
                'type' => 'Instant', 
                'description' => 'Kopi instan rasa klasik'
            ],
            [
                'name' => 'Sudut Timur 3in1 Original', 
                'type' => 'Instant', 
                'description' => 'Kopi instan dengan gula dan krimer'
            ],
            [
                'name' => 'Sudut Timur 3in1 Less Sugar', 
                'type' => 'Instant', 
                'description' => 'Kopi instan rendah gula'
            ],
        ];

        foreach ($products as $productData) {
            Product::firstOrCreate(
                ['name' => $productData['name']],
                $productData
            );
        }

        echo "✅ " . count($products) . " products berhasil dibuat!\n";
    }
}