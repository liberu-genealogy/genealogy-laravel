<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Family;
use App\Models\User;

class FamilyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Family');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Family $family): bool
    {
        return $user->checkPermissionTo('view Family');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Family');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Family $family): bool
    {
        return $user->checkPermissionTo('update Family');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Family $family): bool
    {
        return $user->checkPermissionTo('delete Family');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Family $family): bool
    {
        return $user->checkPermissionTo('restore Family');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Family $family): bool
    {
        return $user->checkPermissionTo('force-delete Family');
    }
}
