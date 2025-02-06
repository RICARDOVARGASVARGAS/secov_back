<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;


// Obtener usuarios
Route::get('getUsers', [UserController::class, 'getUsers'])->name('user.getUsers');
Route::get('getUser/{user}', [UserController::class, 'getUser'])->name('user.getUser');
Route::post('registerUser', [UserController::class, 'registerUser'])->name('user.registerUser');
Route::put('updateUser/{user}', [UserController::class, 'updateUser'])->name('user.updateUser');
Route::delete('deleteUser/{user}', [UserController::class, 'deleteUser'])->name('user.deleteUser');
Route::post('changePasswordUser/{user}', [UserController::class, 'changePasswordUser'])->name('user.changePasswordUser');
Route::post('resetPasswordUser/{user}', [UserController::class, 'resetPasswordUser'])->name('user.resetPasswordUser');
Route::get('getRoles', [UserController::class, 'getRoles'])->name('getRoles');

// Route::post('updatePermission', [UserController::class, 'updatePermission'])->name('users.updatePermission');
// Route::get('getModules', [UserController::class, 'getModules'])->name('getModules');