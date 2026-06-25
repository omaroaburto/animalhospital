<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Client;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PetPolicy
{

    public function create(User $user, Client $client): Response
    {
        // Solo el Admin o el propio usuario dueño de ese perfil de cliente pueden crearle mascotas
        return $user->role === Role::ADMIN || $user->id === $client->user_id
            ? Response::allow()
            : Response::deny('No tienes permisos para registrar mascotas a este cliente.');
    }
    /**
     * Determina si el usuario puede listar las mascotas de un cliente específico.
     * Reemplaza/actúa como el viewAny en el contexto de ClientPetController.
     */
    public function viewAnyFromClient(User $user, Client $client): Response
    {
        // Un administrador o el usuario dueño de la cuenta de cliente pueden ver el listado
        $hasAccess = $user->role === Role::ADMIN || $user->id === $client->user_id;

        return $hasAccess
            ? Response::allow()
            : Response::deny('No tienes permisos para ver el listado de mascotas de este cliente.');
    }

    /**
     * Determina si el usuario puede ver, editar o eliminar una mascota bajo el contexto de un cliente.
     * Cubre las acciones: view, update (patch), y delete de ClientPetController.
     */
    public function manageFromClient(User $user, Client $client, Pet $pet): Response
    {
        // 1. Validamos primero que la mascota realmente pertenezca al cliente de la URL (Defensa en profundidad)
        if ($client->id !== $pet->client_id) {
            return Response::deny('Esta mascota no pertenece al cliente especificado.');
        }

        // 2. Si pertenece, validamos que el usuario sea ADMIN o el dueño de la cuenta de ese cliente
        $hasAccess = $user->role === Role::ADMIN || $user->id === $client->user_id;

        return $hasAccess
            ? Response::allow()
            : Response::deny('No tienes autorización para gestionar los datos de esta mascota.');
    }

    /**
     * Determine whether the user can view any models globally.
     * (Opcional: Por si tienes un PetController global solo para Administradores)
     */
    public function viewAny(User $user): Response
    {
        return $user->role === Role::ADMIN
            ? Response::allow()
            : Response::deny('No tienes permisos para ver el listado de mascotas.');
    }

    public function manageFromAdmin(User $user): Response
    {
        return $user->role === Role::ADMIN
            ? Response::allow()
            : Response::deny('Necesitas roles de administrador para acceder a estás funcionalidades d administración de las mascotas.');
    }

}
