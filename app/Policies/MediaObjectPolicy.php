<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\MediaObject;
use App\Models\User;

class MediaObjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any MediaObject');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MediaObject $mediaobject): bool
    {
        return $user->checkPermissionTo('view MediaObject');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create MediaObject');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MediaObject $mediaobject): bool
    {
        return $user->checkPermissionTo('update MediaObject');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MediaObject $mediaobject): bool
    {
        return $user->checkPermissionTo('delete MediaObject');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MediaObject $mediaobject): bool
    {
        return $user->checkPermissionTo('restore MediaObject');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MediaObject $mediaobject): bool
    {
        return $user->checkPermissionTo('force-delete MediaObject');
    }
}
