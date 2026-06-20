<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBreedRequest;
use App\Http\Requests\UpdateBreedRequest;
use App\Http\Resources\BreedCollection;
use App\Http\Resources\BreedResource;
use App\Models\Breed;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BreedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): BreedCollection | JsonResponse
    {
        $breeds = Breed::all();
        return new BreedCollection($breeds);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBreedRequest $request): BreedResource | JsonResponse
    {
        // 1. Crear el registro con los datos ya validados
        $breed = Breed::create($request->validated());

        // 2. Cargar la relación para que el BreedResource la incluya en la respuesta
        $breed->load('species');

        // 3. Retornar el recurso con un código HTTP 201 (Created) implícito
        return new BreedResource($breed);
    }

    /**
     * Display the specified resource.
     */
    public function show(Breed $breed): BreedResource | JsonResponse
    {
        $breed->load('species');
        return new BreedResource($breed);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBreedRequest $request, Breed $breed): BreedResource | JsonResponse
    {
        // 1. Actualizar el registro con los datos validados
        $breed->update($request->validated());

        // 2. Asegurar que la relación esté cargada/refrescada
        $breed->load('species');

        // 3. Retornar el recurso modificado
        return new BreedResource($breed);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Breed $breed): JsonResponse
    {
        // 1. Eliminar de la base de datos
        $breed->delete();

        // 2. Responder con un JSON plano de confirmación o un código 204 No Content
        return response()->json([
            'status'  => 'success',
            'message' => 'La raza fue eliminada correctamente.'
        ], 200);
    }
}
