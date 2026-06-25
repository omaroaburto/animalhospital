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


/*************************************************************
 ***                      RUTAS SIN JWT                    ***
 *************************************************************/

//Registro de clientes
Route::post('v1/clients', [ClientController::class, 'store']);
//Login
Route::post('v1/login',[AuthController::class, 'login']);
//Verificación de email (API)
Route::get('v1/email/verify', [AuthController::class, 'verifyEmail']);


/*************************************************************
 ***      RUTAS CON JWT Y CUENTAS VERIFICADAS (email)      ***
 *************************************************************/

Route::middleware(['jwt.auth', 'verified'])->prefix('v1/')->group(function () {

    /***************************************************************************
     ***     RUTAS PARA TODOS LOS USUARIOS LOGEADOS (NO DEPENDEN DE ROLES)   ***
     ***************************************************************************/
    //Regiones
    Route::apiResource('regions/', RegionController::class)
        ->only(['index','show']);
    //Ciudades
    Route::apiResource('cities/', CityController::class)
        ->only(['index','show']);
    //Especies
    Route::apiResource('species/', SpeciesController::class)
        ->only(['index','show']);
    //Razas
    Route::apiResource('/breeds', BreedController::class)
        ->only(['index','show']);

    //USUARIOS

    //Cerrar sesión
    Route::post('logout/',[AuthController::class, 'logout']);
    //Refrescar token
    Route::post('refresh/',[AuthController::class, 'refresh']);


    /*******************************************
     ***      Rutas para Administradores     ***
     *******************************************/

    //Breeds (razas)
    Route::apiResource('/breeds', BreedController::class)
        ->only(['destroy','update','store']);

    //Api clientes
    Route::get('clients/{client}', [ClientController::class, 'show']);
    Route::get('clients', [ClientController::class, 'index']);
    Route::delete('clients/{client}', [ClientController::class, 'destroy']);
    Route::put('clients/{client}', [ClientController::class, 'update']);

    //Api pets
    Route::apiResource('pets/', PetController::class);

    /***********************************************
     ***      Rutas para Dueños del recurso      ***
     ***********************************************/

    //crear
    Route::post('clients/{client}/pets',[ClientPetController::class,'store']);
    //lista las mascotas de un cliente
    Route::get('clients/{client}/pets',[ClientPetController::class,'index']);
    //buscar mascota del cliente por su id o nombre
    //Route::get('clients/{client}/pets/{pet}',[ClientPetController::class,'index']);
    Route::get('clients/{client}/pets/{pet}',[ClientPetController::class, 'show'])->scopeBindings();
    //actualizar mascota de un cliente por el id la mascota
    Route::patch('clients/{client}/pets/{pet}',[ClientPetController::class,'patch'])->scopeBindings();
    //eliminar mascota
    Route::delete('clients/{client}/pets/{pet}',[ClientPetController::class,'destroy'])->scopeBindings();

});
