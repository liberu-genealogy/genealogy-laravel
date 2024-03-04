<?php

namespace App\Policies;

use App\Models\PersonAnci;
use App\Models\User;

class PersonAnciPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any PersonAnci');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PersonAnci $personanci): bool
    {
        return $user->checkPermissionTo('view PersonAnci');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create PersonAnci');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PersonAnci $personanci): bool
    {
        return $user->checkPermissionTo('update PersonAnci');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PersonAnci $personanci): bool
    {
        return $user->checkPermissionTo('delete PersonAnci');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PersonAnci $personanci): bool
    {
        return $user->checkPermissionTo('restore PersonAnci');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PersonAnci $personanci): bool
    {
        return $user->checkPermissionTo('force-delete PersonAnci');
    }
}
