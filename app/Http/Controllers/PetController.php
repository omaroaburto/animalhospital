<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use App\Http\Resources\PetCollection;
use App\Http\Resources\PetResource;
use App\Models\Pet;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): PetCollection
    {
        $perPage = $request->query('per_page', 10);
        $pets = Pet::with(['breed.species', 'client'])->paginate($perPage);
        return new PetCollection($pets);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePetRequest $request): PetResource
    {
        $pet = Pet::create($request->validated());
        $pet->load(['breed.species','client']);
        return new PetResource($pet);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pet $pet): PetResource
    {
        $pet->load(['breed.species','client']);
        return new PetResource($pet);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePetRequest $request ,Pet $pet): PetResource
    {
        $pet->update($request->validated());
        $pet->load(['breed.species', 'client']);
        return new PetResource($pet);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pet $pet): JsonResponse
    {
        $pet->delete();
        return response()->json([
            'status'  => 'success',
            'message' => 'La mascota fue eliminada correctamente.'
        ], Response::HTTP_OK);
    }
}
