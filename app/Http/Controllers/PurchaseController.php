<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Orion\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    protected $model = Purchase::class;
    protected $policy = \App\Policies\PurchasePolicy::class;

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
            'supplier',
            'manufacturer',
            'cost_per_unit',
            'amount',
            'quantity',
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
            'supplier',
            'manufacturer',
            'cost_per_unit',
            'amount',
            'quantity',
            'created_at',
            'updated_at'
        ];
    }

    public function searchableBy(): array
    {
        return [
            'id',
            'supplier',
            'manufacturer'
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
            'supplier',
            'manufacturer',
            'cost_per_unit',
            'amount',
            'meta',
            'quantity'
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
            'supplier',
            'manufacturer',
            'cost_per_unit',
            'amount',
            'meta',
            'quantity'
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

        // Calculate amount if not provided
        if (!$request->has('amount') && $request->has('cost_per_unit') && $request->has('quantity')) {
            $model->amount = $request->cost_per_unit * $request->quantity;
        }
    }

    /**
     * Handle any actions before updating the model
     */
    protected function beforeUpdate(Request $request, $model)
    {
        // Calculate amount if cost_per_unit or quantity changed
        if ($request->has('cost_per_unit') || $request->has('quantity')) {
            $costPerUnit = $request->get('cost_per_unit', $model->cost_per_unit);
            $quantity = $request->get('quantity', $model->quantity);
            $model->amount = $costPerUnit * $quantity;
        }
    }
}
