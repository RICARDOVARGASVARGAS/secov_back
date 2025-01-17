<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class JwtAccess
{
    public function handle(Request $request, Closure $next, $permissions = null)
    {
        try {
            // Verificar el token y autenticar al usuario
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token inválido o no proporcionado'], Response::HTTP_UNAUTHORIZED);
        }

        // Verificar permisos si se proporcionan
        if ($permissions) {
            // Convertir la lista de permisos en un array
            $permissionsArray = explode(',', $permissions);

            // Verificar si el usuario tiene **todos los permisos** (opcional)
            // if (!$user->hasAllPermissions($permissionsArray)) {
            //     return response()->json(['error' => 'No tienes los permisos necesarios'], Response::HTTP_FORBIDDEN);
            // }

            // Verificar si el usuario tiene **al menos uno de los permisos**
            if (!$user->hasAnyPermission($permissionsArray)) {
                return response()->json(['error' => 'No tienes los permisos necesarios'], Response::HTTP_FORBIDDEN);
            }
        }

        // Continuar con la solicitud
        return $next($request);
    }
}
