<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Policies\InventoryPolicy;

class InventoryController
{
    protected $model  = Inventory::class;
    protected $policy = InventoryPolicy::class;

    public function includes(): array
    {
        return [];
    }

    public function filterableBy(): array
    {
        return ['id', 'product_id', 'quantity', 'storage_location', 'price_per_unit', 'last_stocked_at'];
    }

    public function searchableBy(): array
    {
        return ['id', 'product_id', 'quantity', 'storage_location', 'price_per_unit', 'last_stocked_at'];
    }

    public function sortableBy(): array
    {
        return ['id', 'product_id', 'quantity', 'price_per_unit', 'last_stocked_at'];
    }
}
