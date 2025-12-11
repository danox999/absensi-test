<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // // Force HTTPS jika request tidak secure dan bukan localhost
        // if (!$request->secure() && !$request->is('localhost') && !str_contains($request->getHost(), 'localhost')) {
        //     return redirect()->secure($request->getRequestUri());
        // }

        if (str_ends_with($request->getHost(), 'ngrok-free.app')){
            \Illuminate\Support\Facades\URL :: forceScheme
            ('https');
        }
        return $next($request);
    }
}
