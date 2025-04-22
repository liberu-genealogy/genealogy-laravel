<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PersonLds;
use Illuminate\Auth\Access\HandlesAuthorization;

class PersonLdsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_person::lds');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PersonLds $personLds): bool
    {
        return $user->can('view_person::lds');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_person::lds');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PersonLds $personLds): bool
    {
        return $user->can('update_person::lds');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PersonLds $personLds): bool
    {
        return $user->can('delete_person::lds');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_person::lds');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, PersonLds $personLds): bool
    {
        return $user->can('force_delete_person::lds');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_person::lds');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, PersonLds $personLds): bool
    {
        return $user->can('restore_person::lds');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_person::lds');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, PersonLds $personLds): bool
    {
        return $user->can('replicate_person::lds');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_person::lds');
    }
}
