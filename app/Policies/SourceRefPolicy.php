<?php

namespace App\Policies;

use App\Models\SourceRef;
use App\Models\User;

class SourceRefPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any SourceRef');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SourceRef $sourceref): bool
    {
        return $user->checkPermissionTo('view SourceRef');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create SourceRef');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SourceRef $sourceref): bool
    {
        return $user->checkPermissionTo('update SourceRef');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SourceRef $sourceref): bool
    {
        return $user->checkPermissionTo('delete SourceRef');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SourceRef $sourceref): bool
    {
        return $user->checkPermissionTo('restore SourceRef');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SourceRef $sourceref): bool
    {
        return $user->checkPermissionTo('force-delete SourceRef');
    }
}
