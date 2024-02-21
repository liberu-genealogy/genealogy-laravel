<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Repository;
use App\Models\User;

class RepositoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Repository');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Repository $repository): bool
    {
        return $user->checkPermissionTo('view Repository');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Repository');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Repository $repository): bool
    {
        return $user->checkPermissionTo('update Repository');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Repository $repository): bool
    {
        return $user->checkPermissionTo('delete Repository');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Repository $repository): bool
    {
        return $user->checkPermissionTo('restore Repository');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Repository $repository): bool
    {
        return $user->checkPermissionTo('force-delete Repository');
    }
}
