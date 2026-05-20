<?php

namespace App\Policies;

use App\Models\ImportJob;
use App\Models\User;

class ImportJobPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any ImportJob');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ImportJob $importjob): bool
    {
        return $user->checkPermissionTo('view ImportJob');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create ImportJob');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ImportJob $importjob): bool
    {
        return $user->checkPermissionTo('update ImportJob');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ImportJob $importjob): bool
    {
        return $user->checkPermissionTo('delete ImportJob');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ImportJob $importjob): bool
    {
        return $user->checkPermissionTo('restore ImportJob');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ImportJob $importjob): bool
    {
        return $user->checkPermissionTo('force-delete ImportJob');
    }
}
