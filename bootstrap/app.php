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
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
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

        // 1. Manejar errores 404 (Not Found y Model Not Found)
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                if ($e->getPrevious() instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                    $model = class_basename($e->getPrevious()->getModel());
                    return response()->json([
                        'status'  => 'error',
                        'message' => "El registro en el módulo '{$model}' no existe."
                    ], 404);
                }
            }
        });

        // 2. Manejar excepciones internas de JWT (Expirado, Inválido, No Proporcionado)
        $exceptions->render(function (UnauthorizedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                $previous = $e->getPrevious();

                // Token Expirado
                if ($previous instanceof TokenExpiredException) {
                    return response()->json([
                        'status' => 'error',
                        'error_code' => 'JWT_TOKEN_EXPIRED',
                        'message' => 'El token ha expirado. Por favor, solicita uno nuevo.'
                    ], 401);
                }

                // Token Inválido o Malformado
                if ($previous instanceof TokenInvalidException) {
                    return response()->json([
                        'status' => 'error',
                        'error_code' => 'JWT_TOKEN_INVALID',
                        'message' => 'El token proporcionado no es válido o ha sido alterado.'
                    ], 401);
                }

                // Token en Lista Negra (Ej: El usuario ya cerró sesión con este token)
                if ($previous instanceof TokenBlacklistedException) {
                    return response()->json([
                        'status' => 'error',
                        'error_code' => 'JWT_TOKEN_BLACKLISTED',
                        'message' => 'El token ya no es válido porque se ha cerrado la sesión.'
                    ], 401);
                }

                // Token no enviado (Evita la pantalla larga de error original)
                if (is_null($previous)) {
                    return response()->json([
                        'status' => 'error',
                        'error_code' => 'JWT_TOKEN_MISSING',
                        'message' => 'Token de autenticación no proporcionado en la petición.'
                    ], 401);
                }
            }
        });

        // 3. Manejar excepciones genéricas de autenticación de Laravel
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'error_code' => 'UNAUTHENTICATED',
                    'message' => 'Usuario no autenticado en el sistema.'
                ], 401);
            }
        });
    })->create();
