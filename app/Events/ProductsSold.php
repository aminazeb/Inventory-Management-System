<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductsSold
{
    use Dispatchable;
    use SerializesModels;

    public $sale;

    public function __construct($sale)
    {
        $this->sale = $sale;
    }
}
