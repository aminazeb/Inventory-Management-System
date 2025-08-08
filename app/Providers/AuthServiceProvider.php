<?php

namespace App\Providers;

use App\Models\Translation;
use App\Policies\TranslationPolicy;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Translation::class => TranslationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        parent::register();
        //
    }
}
