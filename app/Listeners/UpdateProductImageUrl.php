<?php

namespace App\Listeners;

use Illuminate\Support\Str;
use App\Events\ProductCreated;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateProductImageUrl implements ShouldQueue
{
    public function handle(ProductCreated $event): void
    {
        $product = $event->product;

        if ($product->image_url && filter_var($product->image_url, FILTER_VALIDATE_URL)) {
            $imageContents = file_get_contents($product->image_url);

            if ($imageContents !== false) {
                $filename = 'products/' . Str::random(40) . '.jpg';
                Storage::disk('s3')->put($filename, $imageContents, 'public');
                $product->image_url = Storage::disk('s3')->url($filename);
                $product->save();
            }
        }
    }
}
