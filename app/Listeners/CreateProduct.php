<?php

namespace App\Listeners;

use App\Models\Product;

class CreateProduct
{
    public function __construct()
    {
        //
    }

    public function handle($purchase): void
    {
        $product = Product::create([
            'name' => $purchase->meta['name'],
            'description' => $purchase->meta['description'],
            'color' => $purchase->meta['color'],
            'image_url' => $purchase->meta['image_url'],
            'price' => $purchase->meta['price'],
        ]);

        $purchase->product_id = $product->id;
        $purchase->save();
    }

    public function asListener(...$parameters): void
    {
        $event = $parameters[0];
        $purchase = $event->purchase;

        if (!$purchase->product_id) {
            $this->handle($purchase);
        }
    }
}
