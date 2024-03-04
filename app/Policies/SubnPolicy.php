<?php

namespace App\Policies;

use App\Models\Subn;
use App\Models\User;

class SubnPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Subn');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Subn $subn): bool
    {
        return $user->checkPermissionTo('view Subn');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Subn');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Subn $subn): bool
    {
        return $user->checkPermissionTo('update Subn');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Subn $subn): bool
    {
        return $user->checkPermissionTo('delete Subn');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Subn $subn): bool
    {
        return $user->checkPermissionTo('restore Subn');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Subn $subn): bool
    {
        return $user->checkPermissionTo('force-delete Subn');
    }
}
