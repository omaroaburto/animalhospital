<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePetRequest;
use App\Http\Resources\PetCollection;
use App\Http\Resources\PetResource;
use App\Models\Client;
use App\Models\Pet;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ClientPetController extends Controller
{
    public function index(Client $client): PetCollection
    {
        $pets =  $client->pets()->with(['breed.species'])->paginate(5);
        return new PetCollection($pets);
    }
    public function show(Client $client, Pet $pet): PetResource
    {
        $pet->load(['breed.species', 'client.user']);
        return new PetResource($pet);
    }
    public function patch(
        UpdatePetRequest $request,
        Client $client,
        Pet $pet): PetResource
    {
        //validamos datos
        $validatedData =  $request->validated();
        // Protegemos el controlador eliminando explícitamente el client_id por seguridad
        unset($validatedData['client_id']);
        //actualizamos
        $pet->update($validatedData);
        $pet->load('breed.species', 'client.user');
        return new PetResource($pet);
    }
    public function destroy(Client $client, Pet $pet): JsonResponse
    {
        $pet->delete();
        return response()->json([
            'status'  => 'success',
            'message' => 'La mascota fue eliminada correctamente.'
        ], Response::HTTP_OK);
    }
}
