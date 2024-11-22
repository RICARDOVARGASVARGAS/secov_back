<?php

use App\Http\Controllers\Api\BrandController;
use Illuminate\Support\Facades\Route;

Route::get('getBrands', [BrandController::class, 'getBrands'])->name('getBrands');
Route::post('getBrand/{item}', [BrandController::class, 'getBrand'])->name('getBrand');
Route::post('registerBrand', [BrandController::class, 'registerBrand'])->name('registerBrand');
Route::post('updateBrand/{brand}', [BrandController::class, 'updateBrand'])->name('updateBrand');
Route::delete('deleteBrand/{item}', [BrandController::class, 'deleteBrand'])->name('deleteBrand');
