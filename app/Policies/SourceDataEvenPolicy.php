<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\SourceDataEven;
use App\Models\User;

class SourceDataEvenPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any SourceDataEven');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SourceDataEven $sourcedataeven): bool
    {
        return $user->checkPermissionTo('view SourceDataEven');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create SourceDataEven');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SourceDataEven $sourcedataeven): bool
    {
        return $user->checkPermissionTo('update SourceDataEven');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SourceDataEven $sourcedataeven): bool
    {
        return $user->checkPermissionTo('delete SourceDataEven');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SourceDataEven $sourcedataeven): bool
    {
        return $user->checkPermissionTo('restore SourceDataEven');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SourceDataEven $sourcedataeven): bool
    {
        return $user->checkPermissionTo('force-delete SourceDataEven');
    }
}
