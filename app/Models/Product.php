<?php

namespace App\Models;

use App\Models\Sale;
use App\Models\Purchase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'color',
        'image_url',
        'price',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'price' => 'decimal:2',
    ];

    /**
     * Get the sales records for this product.
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get the purchase records for this product.
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Get the inventory record for this product.
     */
    public function inventory(): HasOne
    {
        return $this->hasOne(Inventory::class, 'product_id', 'id');
    }
}
