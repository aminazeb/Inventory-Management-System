<?php

namespace App\Listeners;

use App\Models\Product;

class UpdateProduct
{
    public function __construct() {}

    public function handle($purchase): void
    {
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

    public function asListener(...$parameters): void
    {
        $event = $parameters[0];
        $purchase = $event->purchase;

        if ($purchase->meta) {
            $this->handle($purchase);
        }
    }
}
