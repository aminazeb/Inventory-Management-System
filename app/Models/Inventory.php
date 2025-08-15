<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'inventory';


    protected $fillable = [
        'product_id',
        'quantity',
        'storage_location',
        'price',
        'last_stocked_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'last_stocked_at' => 'datetime',
    ];

    /**
     * Get the product for this inventory item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // /**
    //  * Get the price per unit (amount / quantity)
    //  */
    // public function getPricePerUnitAttribute()
    // {
    //     return $this->quantity > 0 ? $this->amount / $this->quantity : 0;
    // }

    // /**
    //  * Set the amount based on quantity and price per unit
    //  */
    // public function setPricePerUnitAttribute($value)
    // {
    //     $this->amount = $this->quantity * $value;
    // }
}
