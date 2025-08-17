<?php

namespace App\Listeners;

use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateProduct implements ShouldQueue
{
    public function handle($purchase): void
    {
        if ($purchase->meta) {
            $product = Product::find($purchase->product_id);
            if ($product) {
                $product->update([
                    'name' => isset($purchase->meta['name']) ? $purchase->meta['name'] : $product->name,
                    'description' => isset($purchase->meta['description']) ? $purchase->meta['description'] : $product->description,
                    'color' => isset($purchase->meta['color']) ? $purchase->meta['color'] : $product->color,
                    'image_url' => isset($purchase->meta['image_url']) ? $purchase->meta['image_url'] : $product->image_url,
                    'price' => isset($purchase->meta['price']) ? $purchase->meta['price'] : $product->price,
                ]);
            }
        }
    }
}
