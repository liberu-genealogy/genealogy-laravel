<?php

namespace App\Policies;

use App\Models\PersonSubm;
use App\Models\User;

class PersonSubmPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any PersonSubm');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PersonSubm $personsubm): bool
    {
        return $user->checkPermissionTo('view PersonSubm');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create PersonSubm');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PersonSubm $personsubm): bool
    {
        return $user->checkPermissionTo('update PersonSubm');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PersonSubm $personsubm): bool
    {
        return $user->checkPermissionTo('delete PersonSubm');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PersonSubm $personsubm): bool
    {
        return $user->checkPermissionTo('restore PersonSubm');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PersonSubm $personsubm): bool
    {
        return $user->checkPermissionTo('force-delete PersonSubm');
    }
}
