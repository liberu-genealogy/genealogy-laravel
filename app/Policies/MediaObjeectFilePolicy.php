<?php

namespace App\Policies;

use App\Models\MediaObjeectFile;
use App\Models\User;

class MediaObjeectFilePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any MediaObjeectFile');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MediaObjeectFile $mediaobjeectfile): bool
    {
        return $user->checkPermissionTo('view MediaObjeectFile');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create MediaObjeectFile');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MediaObjeectFile $mediaobjeectfile): bool
    {
        return $user->checkPermissionTo('update MediaObjeectFile');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MediaObjeectFile $mediaobjeectfile): bool
    {
        return $user->checkPermissionTo('delete MediaObjeectFile');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MediaObjeectFile $mediaobjeectfile): bool
    {
        return $user->checkPermissionTo('restore MediaObjeectFile');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MediaObjeectFile $mediaobjeectfile): bool
    {
        return $user->checkPermissionTo('force-delete MediaObjeectFile');
    }
}
