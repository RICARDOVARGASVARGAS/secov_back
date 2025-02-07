<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;


// Obtener usuarios
Route::get('getUsers', [UserController::class, 'getUsers'])->name('user.getUsers');

// Obtener usuario
Route::get('getUser/{user}', [UserController::class, 'getUser'])->name('user.getUser');

// Registrar usuario
Route::post('registerUser', [UserController::class, 'registerUser'])->name('user.registerUser');

// Actualizar usuario
Route::put('updateUser/{user}', [UserController::class, 'updateUser'])->name('user.updateUser');

// Eliminar usuario
Route::delete('deleteUser/{user}', [UserController::class, 'deleteUser'])->name('user.deleteUser');

// Actualizar contraseña
Route::post('changePasswordUser/{user}', [UserController::class, 'changePasswordUser'])->name('user.changePasswordUser');

// Restablecer contraseña
Route::post('resetPasswordUser/{user}', [UserController::class, 'resetPasswordUser'])->name('user.resetPasswordUser');

// Asignar Rol a Usuario
Route::post('assignRoleToUser/{user}', [UserController::class, 'assignRoleToUser'])->name('user.assignRoleToUser');
