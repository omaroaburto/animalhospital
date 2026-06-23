<?php

use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\VerifyOwnership;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api:__DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'is_admin' => CheckAdmin::class,
            'is_owner' => VerifyOwnership::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        //borrar si no quiere trabajar errores en este lugar
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                // Si el error 404 fue causado por un modelo que no se encontró
                if ($e->getPrevious() instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                    $model = class_basename($e->getPrevious()->getModel());
                    return response()->json([
                        'status'  => 'error',
                        'message' => "El registro en el módulo '{$model}' no existe."
                    ], 404);
                }
            }
        });

        // Manejar excepciones de JWT
        $exceptions->render(function (UnauthorizedHttpException $e, Request $request) {
                if ($request->is('api/*')) {
                    $previous = $e->getPrevious();
                // Mensaje personalizado para Token Expirado
                    if ($previous instanceof TokenExpiredException) {
                        return response()->json([
                            'status' => 'error',
                            'error_code' => 'JWT_TOKEN_EXPIRED',
                            'message' => 'El token ha expirado. Por favor, solicita uno nuevo.'
                        ], 401);
                    }
                    // Otros errores de JWT (Inválido, etc.)
                    if ($previous instanceof TokenInvalidException) {
                        return response()->json(['error' => 'Token inválido'], 401);
                    }
                }
        });

         // Manejar cuando no se envía token
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json(['error' => 'No autenticado'], 401);
            }
        });
    })->create();
