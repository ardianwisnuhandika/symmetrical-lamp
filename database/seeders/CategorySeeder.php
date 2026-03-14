<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'PJU', 'icon' => 'fa-lightbulb'],
            ['name' => 'Rambu', 'icon' => 'fa-sign'],
            ['name' => 'RPPJ', 'icon' => 'fa-traffic-light'],
            ['name' => 'Cermin', 'icon' => 'fa-mirror'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
