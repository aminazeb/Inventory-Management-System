<?php

namespace App\Providers;

use App\Events\UserCreated;
use App\Events\ProductsSold;
use App\Events\ProductCreated;
use App\Listeners\CreateProduct;
use App\Listeners\UpdateProduct;
use App\Events\ProductsPurchased;
use App\Listeners\CreateInventory;
use App\Listeners\UpdateInventory;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Listeners\UpdateProductImageUrl;
use App\Listeners\SendUserVerificationNotifications;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserCreated::class => [SendUserVerificationNotifications::class],
        ProductsSold::class => [UpdateInventory::class],
        ProductsPurchased::class => [CreateProduct::class, UpdateProduct::class, UpdateInventory::class],
        ProductCreated::class => [UpdateProductImageUrl::class, CreateInventory::class],
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
