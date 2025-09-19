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
            ['name' => 'Women',       'description' => 'Women clothing & accessories'],
            ['name' => 'Men',         'description' => 'Men clothing & accessories'],
            ['name' => 'Shoes',       'description' => 'All kinds of footwear'],
            ['name' => 'Bags',        'description' => 'Handbags, backpacks, wallets'],
            ['name' => 'Accessories', 'description' => 'Scarves, hats, sunglasses, jewelry'],
            ['name' => 'Outerwear',   'description' => 'Coats, jackets'],
            ['name' => 'Sale',        'description' => 'Discounted & clearance items'],
        ];

        foreach ($categories as $index => $c) {
            Category::updateOrCreate(
                ['slug' => Str::slug($c['name'])],
                [
                    'name' => $c['name'],
                    'description' => $c['description'],
                    'position' => $index + 1,
                    'active' => true,
                    'parent_id' => null,
                ]
            );
        }
    }
}
