<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Sales;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SalesTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_index_sales()
    {
        Sales::factory()->count(3)->create();
        $response = $this->actingAs($this->user)->getJson('/api/sales');
        $response->assertOk()->assertJsonStructure(['data']);
    }

    public function test_search_sales()
    {
        $product = Product::factory()->create();
        Sales::factory()->create(['product_id' => $product->id]);
        $response = $this->actingAs($this->user)->postJson('/api/sales/search', ['product_id' => $product->id]);
        $response->assertOk()->assertJsonStructure(['data']);
    }

    public function test_show_sale()
    {
        $sale = Sales::factory()->create();
        $response = $this->actingAs($this->user)->getJson('/api/sales/' . $sale->id);
        $response->assertOk()->assertJsonStructure(['data']);
    }

    public function test_store_sale()
    {
        $product = Product::factory()->create();
        $data = [
            'product_id' => $product->id,
            'user_id' => $this->user->id,
            'quantity' => 2,
            'amount' => 20.00,
            'action' => 'sold',
            'meta' => []
        ];
        $response = $this->actingAs($this->user)->postJson('/api/sales', $data);
        $response->assertStatus(201)->assertJsonStructure(['data']);
    }

    public function test_update_sale()
    {
        $sale = Sales::factory()->create();
        $response = $this->actingAs($this->user)->putJson('/api/sales/' . $sale->id, ['quantity' => 5]);
        $response->assertOk()->assertJsonStructure(['data']);
    }

    public function test_delete_sale()
    {
        $sale = Sales::factory()->create();
        $response = $this->actingAs($this->user)->deleteJson('/api/sales/' . $sale->id);
        $response->assertOk();
    }
}
