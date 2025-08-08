<?php

namespace App\Http\Middleware;

use App\Http\Controllers\AuthController;
use Closure;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class WebAuthenticate
{
    /**
     * Try to login user if the request contains user cookie.
     *
     * @see https://laravel.com/docs/master/middleware
     */
    public function handle($request, Closure $next)
    {
        if (Str::contains($request->route()->uri, 'nova')) {
            return $next($request);
        }

        $user_session = $request->session()->get('user_detail', false);
        $token = Cookie::get('token');

        $auth = new AuthController();

        if ($token) {
            $auth->loginWithAuthToken($token);
        } elseif ($user_session) {
            $auth->logout($request);
        }

        return $next($request);
    }
}
