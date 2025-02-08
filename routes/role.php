<?php

use App\Http\Controllers\Api\RoleController;
use Illuminate\Support\Facades\Route;


// Obtener Roles
Route::get('getRoles', [RoleController::class, 'getRoles'])->name('rol.getRoles');

// Obtener Rol
Route::get('getRole/{role}', [RoleController::class, 'getRole'])->name('rol.getRole');

// Registrar Rol
Route::post('registerRole', [RoleController::class, 'registerRole'])->name('rol.registerRole');

// Actualizar Rol
Route::put('updateRole/{role}', [RoleController::class, 'updateRole'])->name('rol.updateRole');

// Eliminar Rol
Route::delete('deleteRole/{role}', [RoleController::class, 'deleteRole'])->name('rol.deleteRole');

// Listar Permisos
Route::get('getPermissions', [RoleController::class, 'getPermissions'])->name('rol.getPermissions');

// Asignar Permisos a Rol
Route::put('updateRolePermissions/{role}', [RoleController::class, 'updateRolePermissions'])->name('rol.updateRolePermissions');
