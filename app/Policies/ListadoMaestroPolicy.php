<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ListadoMaestro;
use Illuminate\Auth\Access\HandlesAuthorization;

class ListadoMaestroPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'superadmin']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ListadoMaestro $listadoMaestro): bool
    {
        return in_array($user->role, ['admin', 'superadmin']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'superadmin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ListadoMaestro $listadoMaestro): bool
    {
        return $user->role === 'superadmin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ListadoMaestro $listadoMaestro): bool
    {
        return $user->role === 'superadmin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ListadoMaestro $listadoMaestro): bool
    {
        return $user->role === 'superadmin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ListadoMaestro $listadoMaestro): bool
    {
        return $user->role === 'superadmin';
    }

    /**
     * Determine whether the user can approve the document.
     */
    public function aprobar(User $user): bool
    {
        return $user->role === 'superadmin';
    }

    /**
     * Determine whether the user can reject the document.
     */
    public function rechazar(User $user): bool
    {
        return $user->role === 'superadmin';
    }
}

