<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['category_name' => 'Smartphones', 'icon' => null],
            ['category_name' => 'Laptops', 'icon' => null],
            ['category_name' => 'Gaming', 'icon' => null],
            ['category_name' => 'Cameras', 'icon' => null],
            ['category_name' => 'Audio', 'icon' => null],
            ['category_name' => 'Men Fashion', 'icon' => null],
            ['category_name' => 'Women Fashion', 'icon' => null],
            ['category_name' => 'Kids Fashion', 'icon' => null],
            ['category_name' => 'Home Appliances', 'icon' => null],
            ['category_name' => 'Furniture', 'icon' => null],
            ['category_name' => 'Kitchen Tools', 'icon' => null],
            ['category_name' => 'Health & Beauty', 'icon' => null],
            ['category_name' => 'Sports & Outdoor', 'icon' => null],
            ['category_name' => 'Stationery', 'icon' => null],
            ['category_name' => 'Automotive Parts', 'icon' => null],
            ['category_name' => 'Motor Accessories', 'icon' => null],
            ['category_name' => 'Pet Supplies', 'icon' => null],
            ['category_name' => 'Groceries', 'icon' => null],
            ['category_name' => 'Toys & Hobbies', 'icon' => null],
        ];

        foreach ($categories as $category) {
            Category::create([
                'category_name' => $category['category_name'],
                'icon'          => $category['icon'],
                'slug'          => Str::slug($category['category_name']),
            ]);
        }
    }
}
