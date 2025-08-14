<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'user_id',
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

    /**
     * Get the product that was purchased.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who made the purchase.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
