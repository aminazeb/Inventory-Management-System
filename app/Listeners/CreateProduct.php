<?php

namespace App\Listeners;

use App\Models\Product;
use App\Events\ProductCreated;
use App\Events\ProductsPurchased;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateProduct implements ShouldQueue
{
    public function handle(ProductsPurchased $event): void
    {
        $purchase = $event->purchase;

        if (!$purchase->product_id) {
            $product = Product::create([
                'name' => $purchase->meta['name'],
                'description' => $purchase->meta['description'],
                'color' => $purchase->meta['color'],
                'image_url' => $purchase->meta['image_url'],
                'price' => $purchase->meta['price'],
                'meta' => [
                    'storage_location' => $purchase->meta['storage_location'],
                    'quantity' => $purchase->quantity
                ]
            ]);

            ProductCreated::dispatch($product);

            $purchase->product_id = $product->id;
            $purchase->save();
        }
    }
}
