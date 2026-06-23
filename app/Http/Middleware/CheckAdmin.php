<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!$request->user() || $request->user()->role()!==Role::ADMIN){
            return response()->json([
                'error' => 'Acceso denegado, se requieren roles de administrador.'
            ], Response::HTTP_FORBIDDEN);
        }
        return $next($request);
    }
}
