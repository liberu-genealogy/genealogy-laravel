<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\SourceRefEven;
use App\Models\User;

class SourceRefEvenPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any SourceRefEven');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SourceRefEven $sourcerefeven): bool
    {
        return $user->checkPermissionTo('view SourceRefEven');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create SourceRefEven');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SourceRefEven $sourcerefeven): bool
    {
        return $user->checkPermissionTo('update SourceRefEven');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SourceRefEven $sourcerefeven): bool
    {
        return $user->checkPermissionTo('delete SourceRefEven');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SourceRefEven $sourcerefeven): bool
    {
        return $user->checkPermissionTo('restore SourceRefEven');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SourceRefEven $sourcerefeven): bool
    {
        return $user->checkPermissionTo('force-delete SourceRefEven');
    }
}
