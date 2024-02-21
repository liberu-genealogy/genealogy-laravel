<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Refn;
use App\Models\User;

class RefnPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Refn');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Refn $refn): bool
    {
        return $user->checkPermissionTo('view Refn');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Refn');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Refn $refn): bool
    {
        return $user->checkPermissionTo('update Refn');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Refn $refn): bool
    {
        return $user->checkPermissionTo('delete Refn');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Refn $refn): bool
    {
        return $user->checkPermissionTo('restore Refn');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Refn $refn): bool
    {
        return $user->checkPermissionTo('force-delete Refn');
    }
}
