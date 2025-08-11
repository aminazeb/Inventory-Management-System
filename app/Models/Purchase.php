<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();
        // self::observe(PurchaseObserver::class);
    }

    protected $fillable = [
        'product_id',
        'supplier',
        'manufacturer',
        'cost_per_unit',
        'amount',
        'meta',
        'quantity',
    ];

    protected $casts = [
        'meta' => 'array',
        'amount' => 'decimal:2',
        'cost_per_unit' => 'decimal:2',
    ];
}
