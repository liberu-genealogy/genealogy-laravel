<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Activation;
use App\Models\User;

class ActivationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Activation');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Activation $activation): bool
    {
        return $user->checkPermissionTo('view Activation');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Activation');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Activation $activation): bool
    {
        return $user->checkPermissionTo('update Activation');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Activation $activation): bool
    {
        return $user->checkPermissionTo('delete Activation');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Activation $activation): bool
    {
        return $user->checkPermissionTo('restore Activation');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Activation $activation): bool
    {
        return $user->checkPermissionTo('force-delete Activation');
    }
}
