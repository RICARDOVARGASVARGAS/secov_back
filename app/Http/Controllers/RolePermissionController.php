<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    // Crear un nuevo rol
    public function storeRole(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|unique:roles,name_en',
            'name_es' => 'required|string|unique:roles,name_es',
        ]);

        $role = Role::create($validated);

        return response()->json(['message' => 'Role created successfully', 'role' => $role], 201);
    }

    // Crear un nuevo permiso
    public function storePermission(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|unique:permissions,name_en',
            'name_es' => 'required|string|unique:permissions,name_es',
        ]);

        $permission = Permission::create($validated);

        return response()->json(['message' => 'Permission created successfully', 'permission' => $permission], 201);
    }

    // Asignar un permiso a un rol
    public function assignPermissionToRole(Request $request, $roleId)
    {
        $role = Role::findOrFail($roleId);

        $validated = $request->validate([
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);

        $role->permissions()->syncWithoutDetaching($validated['permission_ids']);

        return response()->json(['message' => 'Permissions assigned successfully', 'role' => $role], 200);
    }

    // Obtener roles con sus permisos
    public function indexRolesWithPermissions()
    {
        $roles = Role::with('permissions')->get();

        return response()->json($roles);
    }

    // Obtener permisos
    public function indexPermissions()
    {
        $permissions = Permission::all();

        return response()->json($permissions);
    }
}
