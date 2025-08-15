<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orion\Http\Controllers\Controller;
use Orion\Http\Requests\Request as RequestsRequest;

class SalesController extends Controller
{
    protected $model = Sales::class;
    protected $policy = \App\Policies\SalesPolicy::class;

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

    /**
     * The attributes that are allowed for creating a new model.
     */
    public function creatableFields(): array
    {
        return [
            'product_id',
            'user_id',
            'meta',
            'quantity',
            'amount',
            'action'
        ];
    }

    /**
     * The attributes that are allowed for updating a model.
     */
    public function updatableFields(): array
    {
        return [
            'product_id',
            'user_id',
            'meta',
            'quantity',
            'amount',
            'action'
        ];
    }

    /**
     * Handle any actions before storing the model
     */
    protected function beforeStore(Request $request, $model)
    {
        // Set user_id to authenticated user if not provided
        if (!$request->has('user_id')) {
            $model->user_id = Auth::user()->id;
        }

        if ($request->has('product_id') && !$request->has('amount')) {
            $product = Product::find($request->product_id);
            if ($product) {
                $model->amount = $model->quantity * $product->price;
            }
        }
    }

    protected function afterStore(RequestsRequest $request, Model $entity)
    {
        $inventory = $entity->product->inventory;
        if ($inventory) {
            $inventory->quantity -= $entity->quantity;
            $inventory->save();
        }
    }
}
