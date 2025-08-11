<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
}
