<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\DnaMatching;
use App\Models\User;

class DnaMatchingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any DnaMatching');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DnaMatching $dnamatching): bool
    {
        return $user->checkPermissionTo('view DnaMatching');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create DnaMatching');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DnaMatching $dnamatching): bool
    {
        return $user->checkPermissionTo('update DnaMatching');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DnaMatching $dnamatching): bool
    {
        return $user->checkPermissionTo('delete DnaMatching');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DnaMatching $dnamatching): bool
    {
        return $user->checkPermissionTo('restore DnaMatching');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DnaMatching $dnamatching): bool
    {
        return $user->checkPermissionTo('force-delete DnaMatching');
    }
}
