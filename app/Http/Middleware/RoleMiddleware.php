<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ... $roles)
    {
        $user = Auth::user();
        $auth = false;
        foreach($roles as $role) {
            // Check if user has the role This check will depend on how your roles are set up
            if ($role == 'COMPANY') {
                if(IsCompany()) {
                    $auth = true;
                }
            } elseif ($role == 'CREAUSUARIOS') {
                if($user->creausuarios) {
                    $auth = true;
                }
            } else {
                if($user->hasRole($role)) {
                    $auth = true;
                }
            }
        }
        if ($auth) {
            return $next($request);
        } else {
            return new Response(view('unauthorized'));
        }
    }
}
