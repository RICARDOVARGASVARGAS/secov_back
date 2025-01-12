<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('storage-link', function () {
    // Ejecutar los comandos Artisan
    Artisan::call('storage:link');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:cache');

    // Opcional: Mostrar los resultados en el navegador
    return response()->json([
        'message' => 'Comandos ejecutados correctamente.',
        'output' => [
            'storage_link' => Artisan::output(),
            'config_clear' => Artisan::output(),
            'cache_clear' => Artisan::output(),
            'config_cache' => Artisan::output(),
        ],
    ]);
});
