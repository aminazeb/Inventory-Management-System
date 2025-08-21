<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Events\ProductsSold;
use App\Policies\SalePolicy;
use Orion\Http\Requests\Request;
use App\Http\Resources\SaleResource;
use Illuminate\Support\Facades\Auth;
use Orion\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class SaleController extends Controller
{
    protected $model = Sale::class;
    protected $policy = SalePolicy::class;
    protected $resource = SaleResource::class;

    public function includes(): array
    {
        return ['product', 'user'];
    }

    public function filterableBy(): array
    {
        return [
            'id',
            'product_id',
            'user_id',
            'quantity',
            'amount',
            'action',
            'created_at',
            'updated_at'
        ];
    }

    public function sortableBy(): array
    {
        return [
            'id',
            'product_id',
            'user_id',
            'quantity',
            'amount',
            'action',
            'created_at',
            'updated_at'
        ];
    }

    public function searchableBy(): array
    {
        return [
            'id',
            'action'
        ];
    }

    protected function beforeStore(Request $request, $model)
    {
        if (!$request->has('user_id')) {
            $model->user_id = Auth::user()->id;
        }

        if ($request->has('product_id') && !$request->has('amount')) {
            $product = Product::find($request->product_id);
            if ($product) {
                $model->amount = $request->quantity * $product->price;
            }
        }
    }

    protected function afterStore(Request $request, Model $entity)
    {
        ProductsSold::dispatch($entity);
    }
}
