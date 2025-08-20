<?php

namespace App\Listeners;

use App\Models\Inventory;
use App\Events\ProductCreated;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateInventory implements ShouldQueue
{
    public function handle(ProductCreated $event): void
    {
        $product = $event->product;

        if (!$product?->inventory) {
            $inventory = Inventory::create([
                'product_id' => $product->id,
                'quantity' => $product->meta['quantity'],
                'storage_location' => $product->meta['storage_location'],
                'last_stocked_at' => now(),
            ]);

            $product->inventory_id = $inventory->id;
            $product->meta = null;
            $product->save();
        }
    }
}
