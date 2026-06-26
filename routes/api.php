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
Route::post('v1/clients', [ClientController::class, 'store'])->middleware('guest');
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
    Route::get('regions', [RegionController::class, 'index']);
    Route::get('regions/{region}', [RegionController::class, 'show']);
    //Ciudades
    Route::get('cities', [CityController::class, 'index']);
    Route::get('cities/{city}', [CityController::class,'show']);
    //Especies
    Route::get('species', [SpeciesController::class, 'index']);
    Route::get('species/{species}', [SpeciesController::class, 'show']);

    //Razas
    Route::get('breeds', [BreedController::class, 'index']);
    Route::get('breeds/{breed}', [BreedController::class, 'show']);

    //USUARIOS

    //Cerrar sesión
    Route::post('logout',[AuthController::class, 'logout']);
    //Refrescar token
    Route::post('refresh',[AuthController::class, 'refresh']);


    /*******************************************
     ***      Rutas para Administradores     ***
     *******************************************/

    //Breeds (razas)
    Route::post('/breeds', [BreedController::class,'store']);
    Route::put('/breeds', [BreedController::class,'update']);
    Route::delete('/breeds', [BreedController::class,'destroy']);


    //Api clientes
    Route::get('clients', [ClientController::class, 'index']);
    Route::get('clients/{client}', [ClientController::class, 'show']);
    Route::delete('clients/{client}', [ClientController::class, 'destroy']);
    Route::put('clients/{client}', [ClientController::class, 'update']);
    Route::patch('clients/{client}', [ClientController::class, 'update']);

    //Api pets
    Route::get('pets', [PetController::class,'index']);
    Route::get('pets/{pet}', [PetController::class,'show']);
    Route::post('pets', [PetController::class,'store']);
    Route::put('pets/{pet}', [PetController::class,'update']);
    Route::patch('pets/{pet}', [PetController::class,'update']);
    Route::delete('pets/{pet}', [PetController::class,'destroy']);

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
