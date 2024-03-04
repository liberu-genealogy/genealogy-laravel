<?php

namespace App\Policies;

use App\Models\FamilyEvent;
use App\Models\User;

class FamilyEventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any FamilyEvent');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FamilyEvent $familyevent): bool
    {
        return $user->checkPermissionTo('view FamilyEvent');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create FamilyEvent');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FamilyEvent $familyevent): bool
    {
        return $user->checkPermissionTo('update FamilyEvent');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FamilyEvent $familyevent): bool
    {
        return $user->checkPermissionTo('delete FamilyEvent');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FamilyEvent $familyevent): bool
    {
        return $user->checkPermissionTo('restore FamilyEvent');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FamilyEvent $familyevent): bool
    {
        return $user->checkPermissionTo('force-delete FamilyEvent');
    }
}
