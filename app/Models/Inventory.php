<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'inventory';

    protected static function boot()
    {
        parent::boot();
        // self::observe(InventoryObserver::class);
    }

    protected $fillable = [
        'product_id',
        'quantity',
        'storage_location',
        'amount',
        'last_stocked_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];
}
