<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Policies\InventoryPolicy;
use Orion\Http\Controllers\Controller;
use App\Http\Resources\InventoryResource;

class InventoryController extends Controller
{
    protected $model  = Inventory::class;
    protected $policy = InventoryPolicy::class;
    protected $resource = InventoryResource::class;

    public function includes(): array
    {
        return ['product'];
    }

    public function filterableBy(): array
    {
        return ['id', 'product_id', 'quantity', 'storage_location', 'last_stocked_at'];
    }

    public function searchableBy(): array
    {
        return ['id', 'product_id', 'quantity', 'storage_location', 'last_stocked_at'];
    }

    public function sortableBy(): array
    {
        return ['id', 'product_id', 'quantity', 'last_stocked_at'];
    }
}
