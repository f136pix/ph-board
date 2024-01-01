<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    /**
     * @override
     */
    public function handle($request, Closure $next, ...$guards)
    {
        // set jwt cookie como http header
        $jwt = $request->cookie('jwt');
        if($jwt) {
            $request->headers->set('Authorization',"Bearer $jwt");
        }

        // com o header auth Bearer, realiza a autenticacao do req
        $this->authenticate($request, $guards);

        return $next($request);
    }
}
