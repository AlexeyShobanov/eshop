<?php

namespace Database\Seeders;

use App\Models\Product;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        CategoryFactory::new()->count(10)
            ->has(Product::factory(rand(5, 15)))
            ->create();
    }
}
