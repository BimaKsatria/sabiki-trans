<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated as Middleware;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated extends Middleware
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect('/home'); // Atau redirect ke halaman utama kamu
            }
        }

        return $next($request);
    }
}
