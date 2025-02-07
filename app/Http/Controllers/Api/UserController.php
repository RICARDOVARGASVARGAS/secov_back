<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Listar usuarios
    public function getUsers(ListRequest $request)
    {
        $items = User::visible()->included()->orderBy('id', $request->sort);

        $items = ($request->perPage == 'all' || $request->perPage == null) ? $items->get() : $items->paginate($request->perPage, ['*'], 'page', $request->page);

        return UserResource::collection($items)->additional([
            'message' => 'Usuarios Obtenidos.',
            'status' => 200,
            'success' => true
        ]);
    }

    // Obtener usuario
    public function getUser($user)
    {
        $user = User::included()->find($user);

        if ($user) {
            return UserResource::make($user)->additional([
                'message' => 'Usuario Obtenido.',
                'status' => 200,
                'success' => true
            ]);
        } else {
            return response()->json([
                'message' => 'Usuario No Encontrado',
                'status' => 404,
                'success' => false
            ], 404);
        }
    }

    // Registrar usuario
    public function registerUser(UserRequest $request)
    {
        $user = User::create([
            'document' => $request->document,
            'name' => $request->name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->document),
            'is_active' => true
        ]);

        return UserResource::make($user)->additional([
            'message' => 'Usuario Registrado.',
            'status' => 200,
            'success' => true
        ]);
    }

    // Actualizar usuario
    public function updateUser(UserRequest $request, $user)
    {
        $user = User::find($user);

        if (!$user) {
            return response()->json([
                'message' => 'Usuario No Encontrado',
                'status' => 404,
                'success' => false
            ], 404);
        }

        $user->update([
            'document' => $request->document,
            'name' => $request->name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'image' => $request->image,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'is_active' => $request->is_active
        ]);

        return UserResource::make($user)->additional([
            'message' => 'Usuario Actualizado.',
            'status' => 200,
            'success' => true
        ]);
    }

    // Eliminar usuario
    public function deleteUser($user)
    {
        $user = User::find($user);

        if (!$user) {
            return response()->json([
                'message' => 'Usuario No Encontrado',
                'status' => 404,
                'success' => false
            ], 404);
        }

        try {
            if ($user->image) {
                // Eliminar Imagen API
            }
            $user->delete();
            return response()->json([
                'message' => 'Usuario Eliminado',
                'status' => 200,
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 500,
                'success' => false
            ], 500);
        }
    }

    // Actualizar contraseña
    public function changePasswordUser(Request $request,    $user)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
        ], [], [
            'old_password' => 'Contraseña Actual',
            'new_password' => 'Nueva Contraseña',
        ]);

        $user = User::find($user);

        if (!$user) {
            return response()->json([
                'message' => 'Usuario No Encontrado',
                'status' => 404,
                'success' => false
            ], 404);
        }

        if (password_verify($request->old_password, $user->password)) {
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);
            return response()->json([
                'message' => 'Contraseña Actualizada',
                'status' => 200,
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'message' => 'Contraseña Actual Incorrecta',
                'status' => 400,
                'success' => false
            ], 400);
        }
    }

    // Restablecer contraseña
    public function resetPasswordUser($user)
    {
        $user = User::find($user);

        if (!$user) {
            return response()->json([
                'message' => 'Usuario No Encontrado',
                'status' => 404,
                'success' => false
            ], 404);
        }

        $user->update([
            'password' => Hash::make($user->document)
        ]);
        return response()->json([
            'message' => 'Contraseña Restablecida',
            'status' => 200,
            'success' => true
        ], 200);
    }

    // Mostrar Roles
    public function getRoles()
    {
        $roles = Role::with('permissions')->get();

        return response()->json($roles);
    }

    // Asignar Roles a un Usuario

    // Guardar archivos en este mismo proyecto
}
