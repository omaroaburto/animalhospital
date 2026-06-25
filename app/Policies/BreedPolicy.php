<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Breed;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BreedPolicy
{


    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->role === Role::ADMIN
            ? Response::allow()
            : Response::deny('No tienes permisos de administrador para listar los clientes.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Breed $breed): Response
    {
        return $user->role === Role::ADMIN
            ? Response::allow()
            : Response::deny('No tienes permisos de administrador para listar los clientes.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Breed $breed): Response
    {
        return $user->role === Role::ADMIN
            ? Response::allow()
            : Response::deny('No tienes permisos de administrador para listar los clientes.');
    }
}
