<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory>
 */
class SalesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'user_id' => fake()->randomDigitNotNull(),
            'quantity' => fake()->numberBetween(1, 1000),
            'amount' => fake()->randomFloat(2, 0.5, 50),
            'action' => fake()->randomElement(['sold', 'returned']),
        ];
    }
}
