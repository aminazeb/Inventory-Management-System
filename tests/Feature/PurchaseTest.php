<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Inventory;
use App\Events\ProductsPurchased;
use App\Listeners\CreateInventory;
use App\Listeners\CreateProduct;
use App\Listeners\UpdateInventory;
use App\Listeners\UpdateProduct;
use Illuminate\Support\Facades\Event;
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

    public function test_create_product_and_inventory_on_products_purchased()
    {
        Event::fake();
        $data = [
            'product_id' => null,
            'supplier' => 'Test Supplier',
            'manufacturer' => 'Test Manufacturer',
            'cost_per_unit' => 5.00,
            'amount' => 50.00,
            'quantity' => 10,
            'meta' => [
                'name' => 'Test Product',
                'description' => 'desc',
                'color' => 'blue',
                'image_url' => 'http://example.com/img.jpg',
                'price' => 5,
                'storage_location' => 'A1',
            ]
        ];

        $response = $this->actingAs($this->user)->postJson('/api/purchases', $data);
        $response->assertStatus(201)->assertJsonStructure(['data']);
        $purchase = Purchase::find($response->getData()->data->id);

        Event::assertDispatched(ProductsPurchased::class);
        $event = new ProductsPurchased($purchase);
        (new CreateProduct())->handle($event->purchase);
        (new CreateInventory())->handle($event->purchase);

        $purchase->refresh();
        $this->assertNotNull($purchase->product_id);
        $product = Product::find($purchase->product_id);
        $this->assertEquals('Test Product', $product->name);
        $inventory = Inventory::where('product_id', $product->id)->first();
        $this->assertEquals(10, $inventory->quantity);
    }

    public function test_update_product_and_inventory_on_products_purchased()
    {
        Event::fake();

        $product = Product::factory()->create([
            'name' => 'Product',
            'description' => 'desc',
            'color' => 'blue',
            'image_url' => 'http://example.com/img.jpg',
            'price' => 10
        ]);

        $inventory = Inventory::factory()->create([
            'product_id' => $product->id,
            'quantity' => 10,
            'storage_location' => 'A',
            'last_stocked_at' => now()
        ]);

        $data = [
            'product_id' => $product->id,
            'supplier' => 'Test Supplier',
            'manufacturer' => 'Test Manufacturer',
            'cost_per_unit' => 5.00,
            'amount' => 50.00,
            'quantity' => 10,
            'meta' => [
                'name' => 'Updated Product'
            ]
        ];

        $response = $this->actingAs($this->user)->postJson('/api/purchases', $data);
        $response->assertStatus(201)->assertJsonStructure(['data']);
        $purchase = Purchase::find($response->getData()->data->id);

        Event::assertDispatched(ProductsPurchased::class);
        $event = new ProductsPurchased($purchase);
        (new UpdateProduct())->handle($event->purchase);
        (new UpdateInventory())->handle($event);

        $this->assertEquals('Updated Product', $product->fresh()->name);
        $this->assertEquals(20, $inventory->fresh()->quantity);
    }
}
