<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\PersonName;
use App\Models\User;

class PersonNamePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any PersonName');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PersonName $personname): bool
    {
        return $user->checkPermissionTo('view PersonName');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create PersonName');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PersonName $personname): bool
    {
        return $user->checkPermissionTo('update PersonName');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PersonName $personname): bool
    {
        return $user->checkPermissionTo('delete PersonName');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PersonName $personname): bool
    {
        return $user->checkPermissionTo('restore PersonName');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PersonName $personname): bool
    {
        return $user->checkPermissionTo('force-delete PersonName');
    }
}
