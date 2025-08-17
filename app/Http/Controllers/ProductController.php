<?php

namespace App\Http\Controllers;

use App\Events\ProductCreated;
use App\Models\Product;
use Orion\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $model = Product::class;
    protected $policy = \App\Policies\ProductPolicy::class;

    public function includes(): array
    {
        return ['sales', 'purchases', 'inventory'];
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

    protected function afterStore(Request $request, $model)
    {
        ProductCreated::dispatch($model);
    }
}
