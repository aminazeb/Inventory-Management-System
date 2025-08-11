<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sales extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();
        // self::observe(SalesObserver::class);
    }

    protected $fillable =     [
        'product_id',
        'user_id',
        'meta',
        'quantity',
        'amount',
        'action'
    ];

    protected $casts = [
        'meta' => 'array',
        'amount' => 'decimal:2',
    ];
}
