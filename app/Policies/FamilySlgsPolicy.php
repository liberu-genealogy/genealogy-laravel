<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\FamilySlgs;
use App\Models\User;

class FamilySlgsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any FamilySlgs');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FamilySlgs $familyslgs): bool
    {
        return $user->checkPermissionTo('view FamilySlgs');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create FamilySlgs');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FamilySlgs $familyslgs): bool
    {
        return $user->checkPermissionTo('update FamilySlgs');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FamilySlgs $familyslgs): bool
    {
        return $user->checkPermissionTo('delete FamilySlgs');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FamilySlgs $familyslgs): bool
    {
        return $user->checkPermissionTo('restore FamilySlgs');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FamilySlgs $familyslgs): bool
    {
        return $user->checkPermissionTo('force-delete FamilySlgs');
    }
}
