<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Listar usuarios
    public function getUsers(ListRequest $request)
    {
        $items = User::visible()->get();

        return UserResource::collection($items);
    }

    // Registrar usuario
    public function register(UserRequest $request)
    {
        $user = User::create([
            'document' => $request->document,
            'name' => $request->name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'image' => $request->image,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => bcrypt($request->document),
            'is_active' => $request->is_active
        ]);

        return new UserResource($user);
    }

    // Actualizar usuario
    public function update(UserRequest $request, User $user)
    {
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

        return new UserResource($user);
    }

    // Obtener usuario
    public function getUser($user)
    {
        $item = User::included()->find($user);
        return UserResource::make($item);
    }

    // Eliminar usuario
    public function delete(User $user)
    {
        $user->delete();
        return response()->json(['message' => 'Usuario Eliminado'], 200);
    }

    // Actualizar contraseña
    public function changePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required',
            'new_password' => 'required',
        ], [], [
            'password' => 'Contraseña Actual',
            'new_password' => 'Nueva Contraseña',
        ]);

        if (password_verify($request->password, $user->password)) {
            $user->update([
                'password' => bcrypt($request->new_password)
            ]);
            return response()->json(['message' => 'Contraseña Actualizada'], 200);
        } else {
            return response()->json(['message' => 'Contraseña Actual Incorrecta'], 400);
        }
    }

    // Roles
    
}
