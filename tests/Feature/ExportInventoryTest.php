<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ExportInventoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_export_inventory_without_filters()
    {
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'description' => 'Test Description',
            'color' => 'blue',
            'price' => 10.00
        ]);

        $inventory = Inventory::factory()->create([
            'product_id' => $product->id,
            'quantity' => 100,
            'storage_location' => 'Warehouse A',
            'amount' => 800.00,
            'last_stocked_at' => now()
        ]);

        $response = $this->actingAs($this->user)
            ->post('/api/inventory/export');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->assertHeader('content-disposition', 'attachment; filename=inventory_' . now()->format('Y-m-d_H-i-s') . '.xlsx');
    }

    public function test_export_inventory_with_product_filter()
    {
        $product1 = Product::factory()->create(['name' => 'Product A']);
        $product2 = Product::factory()->create(['name' => 'Product B']);

        $inventory1 = Inventory::factory()->create(['product_id' => $product1->id]);
        $inventory2 = Inventory::factory()->create(['product_id' => $product2->id]);

        $response = $this->actingAs($this->user)
            ->post('/api/inventory/export', [
                'product_id' => $product1->id
            ]);

        $response->assertStatus(200);
    }

    public function test_export_inventory_with_product_name_filter()
    {
        $product = Product::factory()->create(['name' => 'Blue Pen']);
        $inventory = Inventory::factory()->create(['product_id' => $product->id]);

        $response = $this->actingAs($this->user)
            ->post('/api/inventory/export', [
                'product_name' => 'Blue'
            ]);

        $response->assertStatus(200);
    }

    public function test_export_inventory_with_quantity_range_filter()
    {
        $product = Product::factory()->create();
        $inventory1 = Inventory::factory()->create(['product_id' => $product->id, 'quantity' => 50]);
        $inventory2 = Inventory::factory()->create(['product_id' => $product->id, 'quantity' => 150]);

        $response = $this->actingAs($this->user)
            ->post('/api/inventory/export', [
                'min_quantity' => 100,
                'max_quantity' => 200
            ]);

        $response->assertStatus(200);
    }

    public function test_requires_authentication()
    {
        $response = $this->post('/api/inventory/export');

        $response->assertStatus(500);
    }

    public function test_export_inventory_with_storage_location_filter()
    {
        $product = Product::factory()->create();
        $inventory1 = Inventory::factory()->create([
            'product_id' => $product->id,
            'storage_location' => 'Warehouse A'
        ]);
        $inventory2 = Inventory::factory()->create([
            'product_id' => $product->id,
            'storage_location' => 'Warehouse B'
        ]);

        $response = $this->actingAs($this->user)
            ->post('/api/inventory/export', [
                'storage_location' => 'Warehouse A'
            ]);

        $response->assertStatus(200);
    }

    public function test_export_inventory_with_price_range_filter()
    {
        $product = Product::factory()->create();
        $inventory1 = Inventory::factory()->create([
            'product_id' => $product->id,
            'amount' => 5.00
        ]);
        $inventory2 = Inventory::factory()->create([
            'product_id' => $product->id,
            'amount' => 25.00
        ]);

        $response = $this->actingAs($this->user)
            ->post('/api/inventory/export', [
                'min_price' => 10.00,
                'max_price' => 30.00
            ]);

        $response->assertStatus(200);
    }

    public function test_export_inventory_with_date_range_filter()
    {
        $product = Product::factory()->create();
        $inventory1 = Inventory::factory()->create([
            'product_id' => $product->id,
            'last_stocked_at' => now()->subDays(5)
        ]);
        $inventory2 = Inventory::factory()->create([
            'product_id' => $product->id,
            'last_stocked_at' => now()->subDays(15)
        ]);

        $response = $this->actingAs($this->user)
            ->post('/api/inventory/export', [
                'date_from' => now()->subDays(10)->format('Y-m-d'),
                'date_to' => now()->format('Y-m-d')
            ]);

        $response->assertStatus(200);
    }
}
