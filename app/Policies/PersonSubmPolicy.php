<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PersonSubm;
use Illuminate\Auth\Access\HandlesAuthorization;

class PersonSubmPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_person::subm');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PersonSubm $personSubm): bool
    {
        return $user->can('view_person::subm');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_person::subm');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PersonSubm $personSubm): bool
    {
        return $user->can('update_person::subm');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PersonSubm $personSubm): bool
    {
        return $user->can('delete_person::subm');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_person::subm');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, PersonSubm $personSubm): bool
    {
        return $user->can('force_delete_person::subm');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_person::subm');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, PersonSubm $personSubm): bool
    {
        return $user->can('restore_person::subm');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_person::subm');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, PersonSubm $personSubm): bool
    {
        return $user->can('replicate_person::subm');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_person::subm');
    }
}
