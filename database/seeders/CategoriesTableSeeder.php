<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    $categories = [
        ['name' => 'Hot Coffee'],
        ['name' => 'Cold Coffee'],
        ['name' => 'Frappe Coffee'],
        ['name' => 'Fruit Tea'],
        ['name' => 'Iced Tea'],
        ['name' => 'Milktea Classic'],
        ['name' => 'Milktea Premium'],
        ['name' => 'Non-Coffee'],
        ['name' => 'Yakult Series'],
        ['name' => 'Add Ons'],
        ['name' => 'Rice Meals'],
        ['name' => 'Snacks'],
        ['name' => 'Fries'],
        ['name' => 'Chips and Cup Noodles'],
        ['name' => 'Croffle'],
        ['name' => 'Pastry'],
    ];

    foreach ($categories as $category) {
        Category::create($category);
    }
}
}
