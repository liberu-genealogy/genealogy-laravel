<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\PersonAlia;
use App\Models\User;

class PersonAliaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any PersonAlia');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PersonAlia $personalia): bool
    {
        return $user->checkPermissionTo('view PersonAlia');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create PersonAlia');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PersonAlia $personalia): bool
    {
        return $user->checkPermissionTo('update PersonAlia');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PersonAlia $personalia): bool
    {
        return $user->checkPermissionTo('delete PersonAlia');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PersonAlia $personalia): bool
    {
        return $user->checkPermissionTo('restore PersonAlia');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PersonAlia $personalia): bool
    {
        return $user->checkPermissionTo('force-delete PersonAlia');
    }
}
