<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Dna;
use App\Models\User;

class DnaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Dna');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Dna $dna): bool
    {
        return $user->checkPermissionTo('view Dna');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Dna');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Dna $dna): bool
    {
        return $user->checkPermissionTo('update Dna');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Dna $dna): bool
    {
        return $user->checkPermissionTo('delete Dna');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Dna $dna): bool
    {
        return $user->checkPermissionTo('restore Dna');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Dna $dna): bool
    {
        return $user->checkPermissionTo('force-delete Dna');
    }
}
