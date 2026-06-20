<?php

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
Route::apiResource('v1/clients', ClientController::class);
//lista las mascotas de un cliente
Route::get('v1/clients/{client}/pets',[ClientPetController::class,'index']);
//TODO: api mascota
Route::apiResource('v1/pets', PetController::class);
