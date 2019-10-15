<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class UsuarioMiddleware
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
        if ($request->user() && $request->user()->rol->id != 4)
        {
            return new Response(view('unauthorized'));
        }
        return $next($request);
    }
}
