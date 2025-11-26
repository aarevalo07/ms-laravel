<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use Symfony\Component\HttpFoundation\Response;

class ValidateJwtToken
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Aqui Intenta parsear el token del encabezado (Authorization: Bearer <token>)
            // y verificar su firma usando el JWT_SECRET del .env
            $payload = JWTAuth::parseToken()->getPayload();

            // Para saber QUIÉN hace la petición,
            // puedo obtener el ID del usuario del token y pasarlo al request
            // $request->merge(['user_id_from_token' => $payload->get('sub')]);

        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'El token ha expirado'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'El token es inválido'], 401);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Token no encontrado o formato incorrecto',
                'exception' => class_basename($e),
                'message' => $e->getMessage(),
            ], 401);
        }

        return $next($request);
    }
}