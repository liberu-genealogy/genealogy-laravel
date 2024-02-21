<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\PersonAsso;
use App\Models\User;

class PersonAssoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any PersonAsso');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PersonAsso $personasso): bool
    {
        return $user->checkPermissionTo('view PersonAsso');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create PersonAsso');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PersonAsso $personasso): bool
    {
        return $user->checkPermissionTo('update PersonAsso');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PersonAsso $personasso): bool
    {
        return $user->checkPermissionTo('delete PersonAsso');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PersonAsso $personasso): bool
    {
        return $user->checkPermissionTo('restore PersonAsso');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PersonAsso $personasso): bool
    {
        return $user->checkPermissionTo('force-delete PersonAsso');
    }
}
