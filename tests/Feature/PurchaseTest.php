<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Purchase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_index_purchases()
    {
        Purchase::factory()->count(3)->create();
        $response = $this->actingAs($this->user)->getJson('/api/purchases');
        $response->assertOk()->assertJsonStructure(['data']);
    }

    public function test_search_purchases()
    {
        $product = Product::factory()->create();
        Purchase::factory()->create(['product_id' => $product->id]);
        $response = $this->actingAs($this->user)->postJson('/api/purchases/search', ['product_id' => $product->id]);
        $response->assertOk()->assertJsonStructure(['data']);
    }

    public function test_show_purchase()
    {
        $purchase = Purchase::factory()->create();
        $response = $this->actingAs($this->user)->getJson('/api/purchases/' . $purchase->id);
        $response->assertOk()->assertJsonStructure(['data']);
    }

    public function test_store_purchase()
    {
        $product = Product::factory()->create();
        $data = [
            'product_id' => $product->id,
            'supplier' => 'Test Supplier',
            'manufacturer' => 'Test Manufacturer',
            'cost_per_unit' => 5.00,
            'amount' => 50.00,
            'quantity' => 10,
            'meta' => []
        ];
        $response = $this->actingAs($this->user)->postJson('/api/purchases', $data);
        $response->assertStatus(201)->assertJsonStructure(['data']);
    }

    public function test_update_purchase()
    {
        $purchase = Purchase::factory()->create();
        $response = $this->actingAs($this->user)->putJson('/api/purchases/' . $purchase->id, ['quantity' => 20]);
        $response->assertOk()->assertJsonStructure(['data']);
    }

    public function test_delete_purchase()
    {
        $purchase = Purchase::factory()->create();
        $response = $this->actingAs($this->user)->deleteJson('/api/purchases/' . $purchase->id);
        $response->assertOk();
    }
}
