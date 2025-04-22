<?php

namespace App\Policies;

use App\Models\Geneanum;
use App\Models\User;

class GeneanumPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Geneanum');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Geneanum $geneanum): bool
    {
        return $user->checkPermissionTo('view Geneanum');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Geneanum');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Geneanum $geneanum): bool
    {
        return $user->checkPermissionTo('update Geneanum');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Geneanum $geneanum): bool
    {
        return $user->checkPermissionTo('delete Geneanum');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Geneanum $geneanum): bool
    {
        return $user->checkPermissionTo('restore Geneanum');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Geneanum $geneanum): bool
    {
        return $user->checkPermissionTo('force-delete Geneanum');
    }
}
