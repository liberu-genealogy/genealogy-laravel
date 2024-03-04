<?php

namespace App\Policies;

use App\Models\Addr;
use App\Models\User;

class AddrPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Addr');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Addr $addr): bool
    {
        return $user->checkPermissionTo('view Addr');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Addr');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Addr $addr): bool
    {
        return $user->checkPermissionTo('update Addr');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Addr $addr): bool
    {
        return $user->checkPermissionTo('delete Addr');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Addr $addr): bool
    {
        return $user->checkPermissionTo('restore Addr');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Addr $addr): bool
    {
        return $user->checkPermissionTo('force-delete Addr');
    }
}
