<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Citation;
use App\Models\User;

class CitationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Citation');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Citation $citation): bool
    {
        return $user->checkPermissionTo('view Citation');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Citation');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Citation $citation): bool
    {
        return $user->checkPermissionTo('update Citation');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Citation $citation): bool
    {
        return $user->checkPermissionTo('delete Citation');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Citation $citation): bool
    {
        return $user->checkPermissionTo('restore Citation');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Citation $citation): bool
    {
        return $user->checkPermissionTo('force-delete Citation');
    }
}
