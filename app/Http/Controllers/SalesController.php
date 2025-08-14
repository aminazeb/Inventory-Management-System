<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Orion\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
            $model->user_id = auth()->id();
        }
    }

    /**
     * Handle any actions before updating the model
     */
    protected function beforeUpdate(Request $request, $model)
    {
        // Set user_id to authenticated user if not provided
        if (!$request->has('user_id')) {
            $model->user_id = auth()->id();
        }
    }
}
