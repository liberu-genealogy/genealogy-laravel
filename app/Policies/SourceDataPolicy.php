<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\SourceData;
use App\Models\User;

class SourceDataPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any SourceData');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SourceData $sourcedata): bool
    {
        return $user->checkPermissionTo('view SourceData');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create SourceData');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SourceData $sourcedata): bool
    {
        return $user->checkPermissionTo('update SourceData');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SourceData $sourcedata): bool
    {
        return $user->checkPermissionTo('delete SourceData');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SourceData $sourcedata): bool
    {
        return $user->checkPermissionTo('restore SourceData');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SourceData $sourcedata): bool
    {
        return $user->checkPermissionTo('force-delete SourceData');
    }
}
