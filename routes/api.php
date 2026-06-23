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

//RUTAS SIN JWT
//Registro de clientes
Route::post('v1/clients', [ClientController::class, 'store']);
//Login
Route::post('v1/login',[AuthController::class, 'login']);

//RUTAS CON JWT
Route::middleware(['jwt.auth'])->prefix('v1/')->group(function () {
    //api regiones
    Route::apiResource('regions/', RegionController::class)
        ->only(['index','show']);
    //api ciudades
    Route::apiResource('cities/', CityController::class)
        ->only(['index','show']);
    //api especies
    Route::apiResource('species/', SpeciesController::class)
        ->only(['index','show']);
    //razas
    Route::apiResource('/breeds', BreedController::class)
        ->only(['index','show']);

    //registrar mascota
    Route::post('pet/',[PetController::class, 'store']);

    // Rutas para Administradores
    Route::middleware(['is_admin'])->group(function () {
        //Api pets
        Route::apiResource('pets/', PetController::class)
            ->only(['index','show','destroy','update']);
        //Api razas
        Route::apiResource('/breeds', BreedController::class)
            ->only(['destroy','update','store']);
        //Api clientes
        Route::get('clients', [ClientController::class, 'index']);
        Route::delete('clients/{client}', [ClientController::class, 'destroy']);
    });

    // Rutas para Dueños del recurso
    Route::middleware(['is_owner'])->group(function () {
        //lista las mascotas de un cliente
        Route::get('clients/{client}/pets',[ClientPetController::class,'index']);
        //buscar mascota del cliente por su id o nombre
        //Route::get('clients/{client}/pets/{pet}',[ClientPetController::class,'index']);
        Route::get('clients/{client}/pets/{pet}',[ClientPetController::class, 'show'])->scopeBindings();
        //actualizar mascota de un cliente por el id la mascota
        Route::patch('clients/{client}/pets/{pet}',[ClientPetController::class,'patch'])->scopeBindings();
        //eliminar mascota
        Route::delete('clients/{client}/pets/{pet}',[ClientPetController::class,'destroy'])->scopeBindings();
        //mostar información del cliente
        Route::get('clients/{client}', [ClientController::class, 'show']);
        //actualizar información del cliente
        Route::put('clients/{client}', [ClientController::class, 'update']);
    });

    //USUARIOS
    //cerrar sesión
    Route::post('logout/',[AuthController::class, 'logout']);
    //refrescar token
    Route::post('refresh/',[AuthController::class, 'refresh']);
});
