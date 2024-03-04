<?php

namespace App\Policies;

use App\Models\Subm;
use App\Models\User;

class SubmPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Subm');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Subm $subm): bool
    {
        return $user->checkPermissionTo('view Subm');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Subm');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Subm $subm): bool
    {
        return $user->checkPermissionTo('update Subm');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Subm $subm): bool
    {
        return $user->checkPermissionTo('delete Subm');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Subm $subm): bool
    {
        return $user->checkPermissionTo('restore Subm');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Subm $subm): bool
    {
        return $user->checkPermissionTo('force-delete Subm');
    }
}
