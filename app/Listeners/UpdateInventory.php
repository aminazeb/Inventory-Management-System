<?php

namespace App\Listeners;

class UpdateInventory
{
    public function __construct() {}

    public function handle($event): void
    {
        if (isset($event->sale)) {
            $inventory = $event->sale->product->inventory;
            $inventory->quantity -= $event->sale->quantity;
            $inventory->save();
        } else if (isset($event->purchase)) {
            $inventory = $event->purchase->product->inventory;
            $inventory->quantity += $event->purchase->quantity;
            $inventory->save();
        }
    }

    public function asListener(...$parameters): void
    {
        $event = $parameters[0];
        if ($event->sale || ($event->purchase && $event->purchase->product?->inventory)) {
            $this->handle($event);
        }
    }
}
