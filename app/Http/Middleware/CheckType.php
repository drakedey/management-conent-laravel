<?php

namespace App\Http\Middleware;

use App\Rol;
use Closure;

class CheckType
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
        if($request->user()->rol->name == Rol::ADMIN) {
            return $next($request);
        } else {
            return \response(['error' => 'Must be an ADMIN User'], 401);
        }
    }
}
