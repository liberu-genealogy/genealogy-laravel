<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\PersonLds;
use App\Models\User;

class PersonLdsPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any PersonLds');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PersonLds $personlds): bool
    {
        return $user->checkPermissionTo('view PersonLds');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create PersonLds');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PersonLds $personlds): bool
    {
        return $user->checkPermissionTo('update PersonLds');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PersonLds $personlds): bool
    {
        return $user->checkPermissionTo('delete PersonLds');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PersonLds $personlds): bool
    {
        return $user->checkPermissionTo('restore PersonLds');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PersonLds $personlds): bool
    {
        return $user->checkPermissionTo('force-delete PersonLds');
    }
}
