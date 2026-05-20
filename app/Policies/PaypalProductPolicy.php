<?php

namespace App\Policies;

use App\Models\PaypalProduct;
use App\Models\User;

class PaypalProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any PaypalProduct');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PaypalProduct $paypalproduct): bool
    {
        return $user->checkPermissionTo('view PaypalProduct');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create PaypalProduct');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PaypalProduct $paypalproduct): bool
    {
        return $user->checkPermissionTo('update PaypalProduct');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PaypalProduct $paypalproduct): bool
    {
        return $user->checkPermissionTo('delete PaypalProduct');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PaypalProduct $paypalproduct): bool
    {
        return $user->checkPermissionTo('restore PaypalProduct');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PaypalProduct $paypalproduct): bool
    {
        return $user->checkPermissionTo('force-delete PaypalProduct');
    }
}
