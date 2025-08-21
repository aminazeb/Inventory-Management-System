<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Events\ProductCreated;
use App\Policies\ProductPolicy;
use Orion\Http\Requests\Request;
use Orion\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProductController extends Controller
{
    protected $model = Product::class;
    protected $policy = ProductPolicy::class;
    protected $resource = ProductResource::class;

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

    protected function buildIndexFetchQuery(Request $request, array $requestedRelations): Builder
    {
        $query = parent::buildIndexFetchQuery($request, $requestedRelations);
        return $query->withTrashed();
    }

    protected function afterStore(Request $request, $model)
    {
        ProductCreated::dispatch($model);
    }

    protected function afterDestroy(Request $request, $entity)
    {
        $entity->inventory()->delete();
    }

    protected function afterRestore(Request $request, Model $entity)
    {
        $entity->inventory()->restore();
    }
}
