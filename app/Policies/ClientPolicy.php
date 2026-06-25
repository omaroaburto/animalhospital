<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Client;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClientPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->role === Role::ADMIN
            ? Response::allow()
            : Response::deny('No tienes permisos de administrador para listar los clientes.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Client $client): Response
    {
        $hasAccess = $user->role === Role::ADMIN || $user->id === $client->user_id;
        return $hasAccess
            ? Response::allow()
            : Response::deny('No tienes acceso para ver la información de este cliente.');
    }


    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Client $client): Response
    {
        $hasAccess = $user->role === Role::ADMIN || $user->id === $client->user_id;
        return $hasAccess
            ? Response::allow()
            : Response::deny('No tienes permisos para modificar este perfil de cliente.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Client $client): Response
    {
        return $user->role === Role::ADMIN
            ? Response::allow()
            : Response::deny('Acción denegada. Solo un administrador puede eliminar un cliente.');
    }
}
