<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        $clientId = $request->route('client');
        if(is_object($clientId)){
            $clientId =  $clientId->id;
        }
        $isOwner = ($user->id == $clientId);
        $isAdmin = ($user->role == Role::ADMIN);

        if(!$isOwner && !$isAdmin){
            return response()->json([
                'error' => 'No tienes permisos para gestionar este cliente.'
            ], Response::HTTP_FORBIDDEN );
        }

        return $next($request);
    }
}
