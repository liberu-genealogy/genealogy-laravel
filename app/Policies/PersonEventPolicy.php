<?php

namespace App\Policies;

use App\Models\PersonEvent;
use App\Models\User;

class PersonEventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any PersonEvent');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PersonEvent $personevent): bool
    {
        return $user->checkPermissionTo('view PersonEvent');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create PersonEvent');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PersonEvent $personevent): bool
    {
        return $user->checkPermissionTo('update PersonEvent');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PersonEvent $personevent): bool
    {
        return $user->checkPermissionTo('delete PersonEvent');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PersonEvent $personevent): bool
    {
        return $user->checkPermissionTo('restore PersonEvent');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PersonEvent $personevent): bool
    {
        return $user->checkPermissionTo('force-delete PersonEvent');
    }
}
