<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Resources\ClientCollection;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): ClientCollection
    {
        $perPage = $request->query('per_page',10);
        //lista los clientes y pagina el resultado
        $clients = Client::with(['city.region', 'user'])->paginate($perPage);
        return new ClientCollection($clients);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request): ClientResource
    {

        // Ejecuta el proceso dentro de una transacción para asegurar la integridad de los datos
        $client = DB::transaction(function () use ($request){
            // Obtiene únicamente los datos que pasaron las reglas de validación
            $data = $request->validated();
            // Crea el registro de autenticación en la tabla de usuarios
            $user = User::create([
                "email" => $data["email"],
                "password" => $data["password"],
                "role" => Role::CLIENT,
            ]);
            // Remueve las credenciales para limpiar el arreglo antes de crear el cliente
            unset($data['email'], $data['password']);
            // Crea el perfil del cliente asociado al usuario recién creado y lo retorna
            return $user->client()->create($data);
        });
        //Carga de forma diferida (Eager Loading) las relaciones necesarias para evitar consultas extra
        $client->load(['city.region','user']);
        // Transforma el modelo en una respuesta JSON estructurada para la API
        return new ClientResource($client);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client): ClientResource
    {
        //retorna cliente, con su ciudad, region, mascotas con su raza y especie.
        $client->load(['city.region','user']);
        return new ClientResource($client);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client): ClientResource
    {

        $client = DB::transaction(function () use ($request, $client){
            //se obtienen los datos que pasaron las validaciones
            $data = $request->validated();
            //array que almacen los campos del user
            $userData = [];

             // Si se envió un email, lo movemos al array del usuario.
            if (array_key_exists('email', $data)) {
                $userData['email'] = $data['email'];

                // Eliminamos email para evitar intentar actualizarlo en Client.
                unset($data['email']);
            }

            // Si se envió una contraseña y no está vacía,
            // la movemos al array del usuario.
            if (
                array_key_exists('password', $data) &&
                !empty($data['password'])
            ) {
                $userData['password'] = $data['password'];

                // Eliminamos password para evitar intentar actualizarlo en Client.
                unset($data['password']);
            }

            // Actualiza el usuario solamente si hay datos para actualizar.
            if (!empty($userData)) {
                $client->user()->update($userData);
            }

            // Actualiza los datos propios del cliente.
            $client->update($data);
            return $client;
        });
        $client->load(['user','city.region']);
        return new ClientResource($client);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client): JsonResponse
    {
        DB::transaction(function () use ($client){
            $client->delete();
            $client->user()->delete();
        });
        return response()->json([
            'status'  => 'success',
            'message' => 'El cliente fue eliminado correctamente.'
        ], Response::HTTP_OK);
    }
}
