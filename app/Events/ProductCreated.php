<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductCreated
{
    use Dispatchable;
    use SerializesModels;

    public $product;

    public function __construct($product)
    {
        $this->product = $product;
    }
}
