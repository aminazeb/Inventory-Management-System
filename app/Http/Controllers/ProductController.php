<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Str;
use Orion\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $model = Product::class;
    protected $policy = \App\Policies\ProductPolicy::class;

    public function includes(): array
    {
        return [];
    }

    public function filterableBy(): array
    {
        return ['id', 'name', 'description', 'color', 'image_url', 'price'];
    }

    public function sortableBy(): array
    {
        return ['id', 'name', 'price'];
    }

    public function searchableBy(): array
    {
        return ['id', 'name', 'description', 'color', 'image_url', 'price'];
    }

    /**
     * Handle any actions before storing the model
     */
    protected function beforeStore(Request $request, $model)
    {
        if ($request->has('image_url') && filter_var($request->image_url, FILTER_VALIDATE_URL)) {
            $imageContents = file_get_contents($request->image_url);

            if ($imageContents !== false) {
                $filename = 'products/' . Str::random(40) . '.jpg';
                Storage::disk('s3')->put($filename, $imageContents, 'public');
                $model->image_url = Storage::disk('s3')->url($filename);
            }
        }
    }
}
