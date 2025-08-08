<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController
{
    protected $model  = Inventory::class;
    // protected $policy = InventoryPolicy::class;

    public function includes(): array
    {
        return [];
    }

    public function filterableBy(): array
    {
        return ['lang', 'key', 'tag', 'content'];
    }

    public function searchableBy(): array
    {
        return ['lang', 'key', 'content', 'tag'];
    }

    public function sortableBy(): array
    {
        return ['id', 'key'];
    }
}
