<?php

namespace Database\Factories;

use App\Models\Product;
use Domain\Catalog\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => ucfirst($this->faker->words(2, true)),
            'brand_id' => Brand::query()->inRandomOrder()->value('id'),
            'thumbnail' => $this->faker->fixturesImage(
                'products',
                'products'
            ),
            'price' => $this->faker->numberBetween(10000, 1000000),
            'on_home_page' => $this->faker->boolean(),
            'sorting' => $this->faker->numberBetween(1, 999),
            'text' => $this->faker->realText(),
        ];
    }

//    определяем значение поля on_home_page через свойство state для тестирования главной страницы
    public function onHomePage(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'on_home_page' => true
            ];
        });
    }

    public function sorting(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'sorting' => 999
            ];
        });
    }
}
