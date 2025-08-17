<?php

namespace App\Listeners;

use App\Events\ProductsSold;
use App\Events\ProductsPurchased;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateInventory implements ShouldQueue
{
    public function handle(ProductsPurchased|ProductsSold $event): void
    {
        if (isset($event->sale)) {
            $inventory = $event->sale->product?->inventory;
            $inventory->quantity -= $event->sale->quantity;
            $inventory->save();
        } elseif (isset($event->purchase) && $event->purchase->product?->inventory) {
            $inventory = $event->purchase->product->inventory;
            $inventory->quantity += $event->purchase->quantity;
            $inventory->save();
        }
    }
}
