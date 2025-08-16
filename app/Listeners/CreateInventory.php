<?php

namespace App\Listeners;

use App\Models\Inventory;

class CreateInventory
{
    public function __construct()
    {
        //
    }

    public function handle($purchase): void
    {
        Inventory::create([
            'product_id' => $purchase->product_id,
            'quantity' => $purchase->quantity,
            'storage_location' => $purchase->meta['storage_location'],
            'last_stocked_at' => now(),
        ]);
    }

    public function asListener(...$parameters): void
    {
        $event = $parameters[0];
        $purchase = $event->purchase;

        if (!$purchase->product?->inventory) {
            $this->handle($purchase);
        }
    }
}
