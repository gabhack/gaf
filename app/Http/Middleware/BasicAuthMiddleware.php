<?php
namespace App\Http\Middleware;

use Closure;

class BasicAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        // Obtener el nombre de usuario y contraseña de la cabecera de la solicitud
        $user = $request->getUser();
        $password = $request->getPassword();

        if ($user !== env('API_USER') || $password !== env('API_PASSWORD')) {
            return response('Unauthorized', 401)->header('WWW-Authenticate', 'Basic');
        }

        return $next($request);
    }
}
