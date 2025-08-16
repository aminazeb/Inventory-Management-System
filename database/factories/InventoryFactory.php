<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory>
 */
class InventoryFactory extends Factory
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
            'quantity' => fake()->numberBetween(1, 1000),
            'storage_location' => fake()->randomElement(['Warehouse A', 'Warehouse B', 'Shelf 1', 'Shelf 2']),
            'last_stocked_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
