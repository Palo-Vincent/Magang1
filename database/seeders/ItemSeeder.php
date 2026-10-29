<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['Alat Lab', 'Mikroskop', 'LAB-001', 5, 'baik'],
            ['Alat Lab', 'Gelas Ukur', 'LAB-002', 20, 'baik'],
            ['Alat Lab', 'Tabung Reaksi', 'LAB-003', 50, 'baik'],
            ['Alat Lab', 'Pembakar Bunsen', 'LAB-004', 8, 'rusak ringan'],
            ['Alat Olahraga', 'Bola Basket', 'OLR-001', 6, 'baik'],
            ['Alat Olahraga', 'Bola Voli', 'OLR-002', 6, 'baik'],
            ['Alat Olahraga', 'Raket Badminton', 'OLR-003', 10, 'rusak ringan'],
            ['Elektronik', 'Proyektor', 'ELK-001', 3, 'baik'],
            ['Elektronik', 'Laptop', 'ELK-002', 4, 'baik'],
            ['Elektronik', 'Speaker Portabel', 'ELK-003', 2, 'rusak berat'],
        ];

        foreach ($items as [$categoryName, $name, $code, $stock, $condition]) {
            $category = Category::where('name', $categoryName)->firstOrFail();

            Item::create([
                'category_id' => $category->id,
                'name' => $name,
                'code' => $code,
                'stock' => $stock,
                'condition' => $condition,
            ]);
        }
    }
}