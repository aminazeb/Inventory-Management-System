<?php

namespace Tests\Feature;

use App\Events\ProductCreated;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_index_products()
    {
        Product::factory()->count(3)->create();
        $response = $this->actingAs($this->user)->getJson('/api/products');
        $response->assertOk()->assertJsonStructure(['data']);
    }

    public function test_search_products()
    {
        Product::factory()->create(['name' => 'Blue Pen']);
        $response = $this->actingAs($this->user)->postJson('/api/products/search', ['name' => 'Blue']);
        $response->assertOk()->assertJsonStructure(['data']);
    }

    public function test_show_product()
    {
        $product = Product::factory()->create();
        $response = $this->actingAs($this->user)->getJson('/api/products/' . $product->id);
        $response->assertOk()->assertJsonStructure(['data']);
    }

    public function test_store_product()
    {
        Event::fake();
        $data = [
            'name' => 'Test Product',
            'description' => 'Test Desc',
            'color' => 'red',
            'price' => 10.5
        ];
        $response = $this->actingAs($this->user)->postJson('/api/products', $data);
        $response->assertStatus(201)->assertJsonStructure(['data']);
        Event::assertDispatched(ProductCreated::class);
    }

    public function test_update_product()
    {
        $product = Product::factory()->create();
        $response = $this->actingAs($this->user)->putJson('/api/products/' . $product->id, ['name' => 'Updated']);
        $response->assertOk()->assertJsonStructure(['data']);
    }

    public function test_delete_product()
    {
        $product = Product::factory()->create();
        $response = $this->actingAs($this->user)->deleteJson('/api/products/' . $product->id);
        $response->assertOk();
    }
}
