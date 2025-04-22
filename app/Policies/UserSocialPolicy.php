<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserSocial;

class UserSocialPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any UserSocial');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UserSocial $usersocial): bool
    {
        return $user->checkPermissionTo('view UserSocial');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create UserSocial');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserSocial $usersocial): bool
    {
        return $user->checkPermissionTo('update UserSocial');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserSocial $usersocial): bool
    {
        return $user->checkPermissionTo('delete UserSocial');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserSocial $usersocial): bool
    {
        return $user->checkPermissionTo('restore UserSocial');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserSocial $usersocial): bool
    {
        return $user->checkPermissionTo('force-delete UserSocial');
    }
}
