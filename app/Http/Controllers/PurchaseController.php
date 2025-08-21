<?php

namespace App\Http\Controllers;

use App\Events\ProductsPurchased;
use App\Http\Resources\PurchaseResource;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Policies\PurchasePolicy;
use Illuminate\Support\Facades\Auth;
use Orion\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class PurchaseController extends Controller
{
    protected $model = Purchase::class;
    protected $policy = PurchasePolicy::class;
    protected $resource = PurchaseResource::class;

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

    protected function beforeStore(Request $request, $model)
    {
        if (!$request->has('user_id')) {
            $model->user_id = Auth::user()->id;
        }
    }

    protected function afterStore(Request $request, Model $entity)
    {
        ProductsPurchased::dispatch($entity);
    }
}
