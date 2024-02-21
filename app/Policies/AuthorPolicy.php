<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Author;
use App\Models\User;

class AuthorPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Author');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Author $author): bool
    {
        return $user->checkPermissionTo('view Author');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Author');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Author $author): bool
    {
        return $user->checkPermissionTo('update Author');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Author $author): bool
    {
        return $user->checkPermissionTo('delete Author');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Author $author): bool
    {
        return $user->checkPermissionTo('restore Author');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Author $author): bool
    {
        return $user->checkPermissionTo('force-delete Author');
    }
}
