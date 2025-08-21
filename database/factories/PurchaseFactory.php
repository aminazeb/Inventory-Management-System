<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory>
 */
class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => fake()->randomDigitNotNull(),
            'quantity' => fake()->numberBetween(1, 1000),
            'user_id' => User::factory(),
            'supplier' => fake()->company(),
            'manufacturer' => fake()->company(),
            'cost_per_unit' => fake()->randomFloat(2, 0.5, 50),
            'amount' => fake()->randomFloat(2, 0.5, 50),
        ];
    }
}
