<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Domain\Product\Models\Property;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Product\Models\Property>
 */
class PropertyFactory extends Factory
{
    protected $model = Property::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => ucfirst($this->faker->word()),
        ];
    }
}
