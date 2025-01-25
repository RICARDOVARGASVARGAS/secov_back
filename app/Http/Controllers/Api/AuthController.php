<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Constructor: Aplica middleware excepto para el método de login.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Inicio de sesión del usuario.
     * Permite iniciar sesión usando correo o documento.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        // Validar las credenciales de entrada
        $validator = Validator::make(request()->all(), [
            'username' => 'required',
            'password' => 'required',
        ], [], [
            'username' => 'Correo electrónico o Documento',
            'password' => 'Contraseña',
        ]);

        // Si la validación falla, retornar errores
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        // Obtener las credenciales proporcionadas
        $credentials = request(['password']);
        $userName = request('username');

        // Buscar usuario por correo o documento
        $user = User::where('email', $userName)
            ->orWhere('document', $userName)
            ->first();

        // Si no se encuentra el usuario, retornar error
        if (!$user) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        // Validar si el usuario está activo (opcional)
        if (isset($user->is_active) && !$user->is_active) {
            return response()->json(['message' => 'El usuario está inactivo.'], 403);
        }

        // Intentar autenticar usando las credenciales
        $credentials['email'] = $user->email; // Reemplazar con el correo del usuario
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'Credenciales inválidas'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al generar el token.'], 500);
        }

        // Retornar el token junto con los datos del usuario
        return $this->respondWithToken($token, $user);
    }

    /**
     * Obtener información del usuario autenticado.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth()->user();
        return response()->json([
            'user' => $this->getUserData($user),
        ]);
    }

    /**
     * Cerrar sesión y invalidar el token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Sesión cerrada con éxito']);
    }

    /**
     * Refrescar el token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            $token = auth()->refresh();
            $user = auth()->user();
            return $this->respondWithToken($token, $user);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al refrescar el token.'], 500);
        }
    }

    /**
     * Responder con el token y los datos del usuario.
     *
     * @param string $token
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $user)
    {
        return response()->json([
            'user' => $this->getUserData($user),
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ]);
    }

    /**
     * Obtener los datos del usuario, roles y permisos combinados.
     *
     * @param User $user
     * @return array
     */
    protected function getUserData($user)
    {
        // Cargar relaciones de roles y permisos
        $user->load('roles.permissions');

        // Obtener roles del usuario
        $roles = $user->roles->pluck('name_en')->toArray();

        // Obtener permisos combinados de todos los roles
        $permissions = $user->roles->flatMap(function ($role) {
            return $role->permissions->pluck('name_en');
        })->unique()->values()->toArray();

        return [
            'id' => $user->id,
            'document' => $user->document,
            'name' => $user->name,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'roles' => $roles,
            'permissions' => $permissions,
        ];
    }
}
