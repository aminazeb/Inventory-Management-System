<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'inventory_id' => fake()->randomDigitNotNull(),
            'description' => fake()->sentence(),
            'color' => fake()->randomElement(['red', 'blue', 'green', 'black', 'white']),
            'image_url' => fake()->imageUrl(),
            'price' => fake()->randomFloat(2, 1, 100),
        ];
    }
}
