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
        'last_stocked_at',
    ];

    protected $casts = [
        'last_stocked_at' => 'datetime',
    ];

    /**
     * Get the product for this inventory item.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
