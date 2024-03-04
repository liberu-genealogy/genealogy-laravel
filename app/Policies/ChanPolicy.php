<?php

namespace App\Policies;

use App\Models\Chan;
use App\Models\User;

class ChanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Chan');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Chan $chan): bool
    {
        return $user->checkPermissionTo('view Chan');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Chan');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Chan $chan): bool
    {
        return $user->checkPermissionTo('update Chan');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Chan $chan): bool
    {
        return $user->checkPermissionTo('delete Chan');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Chan $chan): bool
    {
        return $user->checkPermissionTo('restore Chan');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Chan $chan): bool
    {
        return $user->checkPermissionTo('force-delete Chan');
    }
}
