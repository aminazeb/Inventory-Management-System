<?php

namespace App\Http\Middleware;

use Closure;

class ApiLocalization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $language = ($request->hasHeader('accept-language')) ? str_replace(['.', '/', '\\'], '', $request->header('accept-language')) : config('app.fallback_locale');

        if (in_array($language, config('app.locales', ['en', 'ar']))) {
            app()->setLocale($language);
        } else {
            app()->setLocale(config('app.fallback_locale'));
        }

        return $next($request);
    }
}
