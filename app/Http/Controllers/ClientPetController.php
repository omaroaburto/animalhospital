<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePetRequest;
use App\Http\Requests\UpdatePetRequest;
use App\Http\Resources\PetCollection;
use App\Http\Resources\PetResource;
use App\Models\Client;
use App\Models\Pet;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ClientPetController extends Controller
{
    public function index(Client $client): PetCollection
    {
        Gate::authorize('viewAnyFromClient',[Pet::class, $client]);
        $pets =  $client->pets()->with(['breed.species'])->paginate(5);
        return new PetCollection($pets);
    }
    public function show(Client $client, Pet $pet): PetResource
    {
        Gate::authorize('manageFromClient',[$pet, $client]);
        $pet->load(['breed.species', 'client.user']);
        return new PetResource($pet);
    }

    public function store(StorePetRequest $request, Client $client): PetResource
    {
        // Pasamos el modelo Pet para que busque en PetPolicy, y el $client de la URL como contexto
        Gate::authorize('create', [new Pet, $client]);

        // Forzamos que el client_id sea el de la URL por seguridad redundante
        $data = $request->validated();
        $data['client_id'] = $client->id;

        $pet = Pet::create($data);
        $pet->load(['breed.species', 'client']);

        return new PetResource($pet);
    }
    public function patch(
        UpdatePetRequest $request,
        Client $client,
        Pet $pet): PetResource
    {
        Gate::authorize('manageFromClient',[$pet, $client]);
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
        //autorización de cliente
        Gate::authorize('manageFromClient',[$pet, $client]);
        $pet->delete();
        return response()->json([
            'status'  => 'success',
            'message' => 'La mascota fue eliminada correctamente.'
        ], Response::HTTP_OK);
    }
}
