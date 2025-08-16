<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductsSold
{
    use Dispatchable, SerializesModels;

    public $sale;

    public function __construct($sale)
    {
        $this->sale = $sale;
    }
}
