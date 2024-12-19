<?php

use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\ColorController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\ExampleController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\RolePermissionController;
use App\Http\Controllers\Api\TypeCarController;
use App\Http\Controllers\Api\YearController;
use Illuminate\Support\Facades\Route;

Route::get('getBrands', [BrandController::class, 'getBrands'])->name('getBrands');
Route::get('getBrand/{item}', [BrandController::class, 'getBrand'])->name('getBrand');
Route::post('registerBrand', [BrandController::class, 'registerBrand'])->name('registerBrand');
Route::put('updateBrand/{item}', [BrandController::class, 'updateBrand'])->name('updateBrand');
Route::delete('deleteBrand/{item}', [BrandController::class, 'deleteBrand'])->name('deleteBrand');

Route::get('getColors', [ColorController::class, 'getColors'])->name('getColors');
Route::get('getColor/{item}', [ColorController::class, 'getColor'])->name('getColor');
Route::post('registerColor', [ColorController::class, 'registerColor'])->name('registerColor');
Route::put('updateColor/{item}', [ColorController::class, 'updateColor'])->name('updateColor');
Route::delete('deleteColor/{item}', [ColorController::class, 'deleteColor'])->name('deleteColor');

Route::get('getExamples', [ExampleController::class, 'getExamples'])->name('getExamples');
Route::get('getExample/{item}', [ExampleController::class, 'getExample'])->name('getExample');
Route::post('registerExample', [ExampleController::class, 'registerExample'])->name('registerExample');
Route::put('updateExample/{item}', [ExampleController::class, 'updateExample'])->name('updateExample');
Route::delete('deleteExample/{item}', [ExampleController::class, 'deleteExample'])->name('deleteExample');

Route::get('getGroups', [GroupController::class, 'getGroups'])->name('getGroups');
Route::get('getGroup/{item}', [GroupController::class, 'getGroup'])->name('getGroup');
Route::post('registerGroup', [GroupController::class, 'registerGroup'])->name('registerGroup');
Route::put('updateGroup/{item}', [GroupController::class, 'updateGroup'])->name('updateGroup');
Route::delete('deleteGroup/{item}', [GroupController::class, 'deleteGroup'])->name('deleteGroup');

Route::get('getTypeCars', [TypeCarController::class, 'getTypeCars'])->name('getTypeCars');
Route::get('getTypeCar/{item}', [TypeCarController::class, 'getTypeCar'])->name('getTypeCar');
Route::post('registerTypeCar', [TypeCarController::class, 'registerTypeCar'])->name('registerTypeCar');
Route::put('updateTypeCar/{item}', [TypeCarController::class, 'updateTypeCar'])->name('updateTypeCar');
Route::delete('deleteTypeCar/{item}', [TypeCarController::class, 'deleteTypeCar'])->name('deleteTypeCar');

Route::get('getYears', [YearController::class, 'getYears'])->name('getYears');
Route::get('getYear/{item}', [YearController::class, 'getYear'])->name('getYear');
Route::post('registerYear', [YearController::class, 'registerYear'])->name('registerYear');
Route::put('updateYear/{item}', [YearController::class, 'updateYear'])->name('updateYear');
Route::delete('deleteYear/{item}', [YearController::class, 'deleteYear'])->name('deleteYear');

Route::get('getDrivers', [DriverController::class, 'getDrivers'])->name('getDrivers');
Route::get('getDriver/{item}', [DriverController::class, 'getDriver'])->name('getDriver');
Route::post('registerDriver', [DriverController::class, 'registerDriver'])->name('registerDriver');
Route::put('updateDriver/{item}', [DriverController::class, 'updateDriver'])->name('updateDriver');
Route::delete('deleteDriver/{item}', [DriverController::class, 'deleteDriver'])->name('deleteDriver');
Route::get('getDriverLicenses/{item}', [DriverController::class, 'getDriverLicenses'])->name('getDriverLicenses');
Route::post('registerDriverLicense', [DriverController::class, 'registerDriverLicense'])->name('registerDriverLicense');
Route::put('updateDriverLicense/{license}', [DriverController::class, 'updateDriverLicense'])->name('updateDriverLicense');
Route::delete('deleteDriverLicense/{license}', [DriverController::class, 'deleteDriverLicense'])->name('deleteDriverLicense');


Route::get('getCars', [CarController::class, 'getCars'])->name('getCars');
Route::get('getCar/{item}', [CarController::class, 'getCar'])->name('getCar');
Route::post('registerCar', [CarController::class, 'registerCar'])->name('registerCar');
Route::put('updateCar/{item}', [CarController::class, 'updateCar'])->name('updateCar');
Route::delete('deleteCar/{item}', [CarController::class, 'deleteCar'])->name('deleteCar');
Route::get('getCarsByDriver/{driver_id}', [CarController::class, 'getCarsByDriver'])->name('getCarsByDriver');


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});


Route::middleware('auth:api')->group(function () {
    Route::get('roles', [RolePermissionController::class, 'indexRolesWithPermissions']);
    Route::get('permissions', [RolePermissionController::class, 'indexPermissions']);
    Route::post('roles', [RolePermissionController::class, 'storeRole']);
    Route::post('permissions', [RolePermissionController::class, 'storePermission']);
    Route::post('roles/{roleId}/assign-permissions', [RolePermissionController::class, 'assignPermissionToRole']);
});
