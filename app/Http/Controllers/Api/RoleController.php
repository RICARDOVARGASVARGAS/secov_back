<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListRequest;
use App\Http\Requests\RoleRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // Listar Roles
    public function getRoles(ListRequest $request)
    {
        $items = Role::included()
            ->where('name', 'like', '%' . $request->search . '%')
            ->orderBy('id', $request->sort);

        $items = ($request->perPage == 'all' || $request->perPage == null) ? $items->get() : $items->paginate($request->perPage, ['*'], 'page', $request->page);

        return RoleResource::collection($items)->additional([
            'message' => 'Roles Obtenidos.',
            'status' => 200,
            'success' => true
        ]);
    }

    // Obtener Rol con sus permisos
    public function getRole($role)
    {
        $role = Role::included()->find($role);

        if (!$role) {
            return response()->json(['message' => 'Rol No Encontrado', 'status' => 404, 'success' => false], 404);
        }

        return RoleResource::make($role)->additional([
            'message' => 'Rol Obtenido.',
            'status' => 200,
            'success' => true
        ]);
    }

    // Registrar Rol
    public function registerRole(RoleRequest $request)
    {
        $role = Role::create([
            'name' => $request->name,
        ]);

        return RoleResource::make($role)->additional([
            'message' => 'Rol Registrado.',
            'status' => 200,
            'success' => true
        ]);
    }

    // Actualizar Rol
    public function updateRole(RoleRequest $request, $role)
    {
        $role = Role::find($role);

        if (!$role) {
            return response()->json(['message' => 'Rol No Encontrado', 'status' => 404, 'success' => false], 404);
        }

        $role->update([
            'name' => $request->name,
        ]);

        return RoleResource::make($role)->additional([
            'message' => 'Rol Actualizado.',
            'status' => 200,
            'success' => true
        ]);
    }

    // Eliminar Rol
    public function deleteRole($role)
    {
        $role = Role::find($role);

        if (!$role) {
            return response()->json(['message' => 'Rol No Encontrado', 'status' => 404, 'success' => false], 404);
        }

        try {
            $role->delete();
            return response()->json(['message' => 'Rol Eliminado.', 'status' => 200, 'success' => true], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 500, 'success' => false], 500);
        }
    }

    // Listar Permisos
    public function getPermissions()
    {
        $permissions = Permission::included()->get();

        return PermissionResource::collection($permissions)->additional([
            'message' => 'Permisos Obtenidos.',
            'status' => 200,
            'success' => true
        ]);
    }

    // Asignar Permisos a Rol
    public function updateRolePermissions(Request $request, $role)
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ], [], [
            'permissions' => 'Permisos',
        ]);

        $role = Role::find($role);

        if (!$role) {
            return response()->json(['message' => 'Rol No Encontrado', 'status' => 404, 'success' => false], 404);
        }

        $role->permissions()->sync($request->permissions);
        return response()->json(['message' => 'Permisos Asignados.', 'status' => 200, 'success' => true], 200);
    }
}
