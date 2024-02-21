<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\PersonNameRomn;
use App\Models\User;

class PersonNameRomnPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any PersonNameRomn');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PersonNameRomn $personnameromn): bool
    {
        return $user->checkPermissionTo('view PersonNameRomn');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create PersonNameRomn');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PersonNameRomn $personnameromn): bool
    {
        return $user->checkPermissionTo('update PersonNameRomn');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PersonNameRomn $personnameromn): bool
    {
        return $user->checkPermissionTo('delete PersonNameRomn');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PersonNameRomn $personnameromn): bool
    {
        return $user->checkPermissionTo('restore PersonNameRomn');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PersonNameRomn $personnameromn): bool
    {
        return $user->checkPermissionTo('force-delete PersonNameRomn');
    }
}
