<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BreedController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientPetController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\SpeciesController;
use Illuminate\Support\Facades\Route;

//api regiones
Route::apiResource('v1/regions', RegionController::class)
    ->only(['index','show']);
//api ciudades
Route::apiResource('v1/cities', CityController::class)
    ->only(['index','show']);
//api especies
Route::apiResource('v1/species', SpeciesController::class)
    ->only(['index','show']);;

//api razas
Route::apiResource('v1/breeds', BreedController::class);
//api clientes
/*Route::apiResource('v1/clients', ClientController::class)
    ->only(['store']);
Route::apiResource('v1/clients', ClientController::class)
        ->middleware(['jwt.auth'])
        ->only(['index','update', 'show', 'detroy']);
Route::apiResource('v1/clients', ClientController::class)
        ->middleware(['is_admin'])
        ->only(['index', 'detroy']);
Route::apiResource('v1/clients', ClientController::class)
        ->middleware(['is_owner'])
        ->only(['update', 'show']);*/

Route::post('v1/clients', [ClientController::class, 'store']);
Route::middleware(['jwt.auth'])->prefix('v1/clients')->group(function () {

    // Rutas para Administradores
    Route::middleware(['is_admin'])->group(function () {
        Route::get('/', [ClientController::class, 'index']);
        Route::delete('/{client}', [ClientController::class, 'destroy']);
    });

    // Rutas para Dueños del recurso
    Route::middleware(['is_owner'])->group(function () {
        Route::get('/{client}', [ClientController::class, 'show']);
        Route::put('/{client}', [ClientController::class, 'update']);
    });
});


//lista las mascotas de un cliente
Route::get('v1/clients/{client}/pets',[ClientPetController::class,'index']);
// api mascota
Route::apiResource('v1/pets', PetController::class);

//TODO: user
Route::post('v1/login',[AuthController::class, 'login']);
Route::post('v1/logout',[AuthController::class, 'logout']);
Route::post('v1/refresh',[AuthController::class, 'refresh']);
