<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
//use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $validatedData =  $request->validated();
        $credentials = [
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
        ];

        try {
            if(!$token = JWTAuth::attempt($credentials)){
                return response()->json([
                    'error' => 'Usuario o contraseña incorrecta'],
                    Response::HTTP_UNAUTHORIZED
                );
            }
        } catch (JWTException $e) {
            return response()->json([
                'error' => 'No se pudo crear el token interno'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        return $this->respondWithToken($token);
    }

    public function logout(): JsonResponse
    {
        try {
            // 1. Intentar obtener el token de la petición
            $token = JWTAuth::getToken();

            if (!$token) {
                return response()->json([
                    'error' => 'Token no suministrado en la petición.'
                ], Response::HTTP_BAD_REQUEST); // Error 400
            }

            // 2. Añadir el token a la lista negra (Blacklist) para que no se pueda reutilizar
            JWTAuth::invalidate($token);

            return response()->json([
                'message' => 'Sesión cerrada correctamente.'
            ], Response::HTTP_OK); // Error 200

        } catch (TokenExpiredException $e) {
            // Si el token ya expiró, técnicamente ya no sirve, pero avisamos al cliente
            return response()->json([
                'error' => 'El token ya había expirado.'
            ], Response::HTTP_UNAUTHORIZED); // Error 401

        } catch (TokenInvalidException $e) {
            // Si el token está mal formado o fue alterado
            return response()->json([
                'error' => 'El token provisto es inválido.'
            ], Response::HTTP_UNAUTHORIZED); // Error 401

        } catch (JWTException $e) {
            // Error genérico del sistema (ej. problemas con Redis/Base de datos al guardar la blacklist)
            return response()->json([
                'error' => 'No se pudo procesar el cierre de sesión interno.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR); // Error 500
        }
    }

    public function refresh(): JsonResponse
    {
        try {
            // 1. Forzamos a JWTAuth a obtener el token desde la petición actual de forma explícita
            $token = JWTAuth::getToken();

            if (!$token) {
                return response()->json([
                    'error' => 'No se proporcionó ningún token en las cabeceras de la petición.'
                ], Response::HTTP_BAD_REQUEST); // Error 400
            }

            // 2. Intentamos refrescar el token capturado
            $newToken = JWTAuth::refresh($token);

            // 3. Retornamos el nuevo token generado
            return $this->respondWithToken($newToken);

        } catch (TokenBlacklistedException $e) {
            return response()->json([
                'error' => 'Este token ya se encuentra en la lista negra (ya fue utilizado o invalidado antes).'
            ], Response::HTTP_UNAUTHORIZED); // Error 401

        } catch (TokenExpiredException $e) {
            // Si el token expiró su tiempo normal (TTL), ¡aquí es donde se debería refrescar con éxito!
            // Pero si salta esta excepción en el catch, significa que el REFRESH_TTL (el tiempo máximo total de renovación) también caducó.
            return response()->json([
                'error' => 'El periodo máximo de renovación (Refresh TTL) ha expirado. Debes iniciar sesión de nuevo.',
                'detalle' => $e->getMessage()
            ], Response::HTTP_UNAUTHORIZED); // Error 401

        } catch (JWTException $e) {
            return response()->json([
                'error' => 'Error al procesar el token. Puede estar mal estructurado, alterado o inválido.',
                'detalle' => $e->getMessage() // Esto nos dirá el mensaje real del error interno
            ], Response::HTTP_UNAUTHORIZED); // Error 401
        }
    }

    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            // Obtenemos el tiempo de vida en segundos multiplicando los minutos por 60
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }
}
