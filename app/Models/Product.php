<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();
        // self::observe(ProductObserver::class);
    }

    protected $fillable = [
        'name',
        'description',
        'color',
        'image_url',
        'price',
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
        return $this->hasMany(Sales::class);
    }

    /**
     * Get the purchase records for this product.
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Get the inventory records for this product.
     */
    public function inventory(): HasMany
    {
        return $this->hasMany(Inventory::class);
    }
}
