<?php

namespace App\Policies;

use App\Models\PersonNameFone;
use App\Models\User;

class PersonNameFonePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any PersonNameFone');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PersonNameFone $personnamefone): bool
    {
        return $user->checkPermissionTo('view PersonNameFone');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create PersonNameFone');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PersonNameFone $personnamefone): bool
    {
        return $user->checkPermissionTo('update PersonNameFone');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PersonNameFone $personnamefone): bool
    {
        return $user->checkPermissionTo('delete PersonNameFone');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PersonNameFone $personnamefone): bool
    {
        return $user->checkPermissionTo('restore PersonNameFone');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PersonNameFone $personnamefone): bool
    {
        return $user->checkPermissionTo('force-delete PersonNameFone');
    }
}
