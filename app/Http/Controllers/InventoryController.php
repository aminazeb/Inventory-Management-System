<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Orion\Http\Controllers\Controller;

class InventoryController extends Controller
{
    protected $model  = Inventory::class;
    protected $policy = \App\Policies\InventoryPolicy::class;

    public function includes(): array
    {
        return ['product'];
    }

    public function filterableBy(): array
    {
        return ['id', 'product_id', 'quantity', 'storage_location', 'amount', 'last_stocked_at'];
    }

    public function searchableBy(): array
    {
        return ['id', 'product_id', 'quantity', 'storage_location', 'amount', 'last_stocked_at'];
    }

    public function sortableBy(): array
    {
        return ['id', 'product_id', 'quantity', 'amount', 'last_stocked_at'];
    }
}
