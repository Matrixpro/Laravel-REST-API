<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(2, true),
            'sku' => strtoupper($this->faker->word),
            'price' => $this->faker->randomFloat(2, 10, 5000),
            'description' => $this->faker->sentences(3, true),
        ];
    }
}
