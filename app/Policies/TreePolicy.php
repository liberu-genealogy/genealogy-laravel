<?php

namespace App\Policies;

use App\Models\Tree;
use App\Models\User;

class TreePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Tree');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Tree $tree): bool
    {
        return $user->checkPermissionTo('view Tree');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Tree');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Tree $tree): bool
    {
        return $user->checkPermissionTo('update Tree');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tree $tree): bool
    {
        return $user->checkPermissionTo('delete Tree');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Tree $tree): bool
    {
        return $user->checkPermissionTo('restore Tree');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Tree $tree): bool
    {
        return $user->checkPermissionTo('force-delete Tree');
    }
}
