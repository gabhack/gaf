<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class SuperAdminMiddleware
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
        if ($request->user() && !IsAdmin())
        {
            return new Response(view('unauthorized'));
        }
        return $next($request);
    }
}
