<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Sale;
use App\Models\User;
use App\Models\Product;
use App\Models\Inventory;
use App\Events\ProductsSold;
use App\Listeners\UpdateInventory;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SaleTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_index_Sale()
    {
        Sale::factory()->count(3)->create();
        $response = $this->actingAs($this->user)->getJson('/api/sales');
        $response->assertOk()->assertJsonStructure(['data']);
    }

    public function test_search_Sale()
    {
        $product = Product::factory()->create();
        Sale::factory()->create(['product_id' => $product->id]);
        $response = $this->actingAs($this->user)->postJson('/api/sales/search', ['product_id' => $product->id]);
        $response->assertOk()->assertJsonStructure(['data']);
    }

    public function test_show_sale()
    {
        $sale = Sale::factory()->create();
        $response = $this->actingAs($this->user)->getJson('/api/sales/' . $sale->id);
        $response->assertOk()->assertJsonStructure(['data']);
    }

    public function test_store_sale()
    {
        Event::fake();
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
        Event::assertDispatched(ProductsSold::class);
    }

    public function test_update_sale()
    {
        $sale = Sale::factory()->create();
        $response = $this->actingAs($this->user)->putJson('/api/sales/' . $sale->id, ['quantity' => 5]);
        $response->assertOk()->assertJsonStructure(['data']);
    }

    public function test_delete_sale()
    {
        $sale = Sale::factory()->create();
        $response = $this->actingAs($this->user)->deleteJson('/api/sales/' . $sale->id);
        $response->assertOk();
    }

    public function test_update_inventory_on_products_sold()
    {
        Event::fake();

        $product = Product::factory()->create(['price' => 10]);
        $inventory = Inventory::factory()->create(['product_id' => $product->id, 'quantity' => 20]);
        $data = [
            'product_id' => $product->id,
            'quantity' => 5,
            'user_id' => $this->user->id,
            'action' => 'sold',
            'meta' => []
        ];

        $response = $this->actingAs($this->user)->postJson('/api/sales', $data);
        $sale = Sale::find($response->getData()->data->id);

        $response->assertStatus(201)->assertJsonStructure(['data']);
        Event::assertDispatched(ProductsSold::class);

        (new UpdateInventory())->handle(new ProductsSold($sale));
        $this->assertEquals(15, $inventory->fresh()->quantity);
    }
}
