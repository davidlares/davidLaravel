<?php

namespace App\Http\Middleware;

use Closure;

class SignatureMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $header = 'X-name')
    {   // setting the app name as header to the request
        $response = $next($request);
        $response->headers->set($header, config('app.name'));
        return $response;
    }
}
