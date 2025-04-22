<?php

namespace App\Policies;

use App\Models\PaypalSubscription;
use App\Models\User;

class PaypalSubscriptionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any PaypalSubscription');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PaypalSubscription $paypalsubscription): bool
    {
        return $user->checkPermissionTo('view PaypalSubscription');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create PaypalSubscription');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PaypalSubscription $paypalsubscription): bool
    {
        return $user->checkPermissionTo('update PaypalSubscription');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PaypalSubscription $paypalsubscription): bool
    {
        return $user->checkPermissionTo('delete PaypalSubscription');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PaypalSubscription $paypalsubscription): bool
    {
        return $user->checkPermissionTo('restore PaypalSubscription');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PaypalSubscription $paypalsubscription): bool
    {
        return $user->checkPermissionTo('force-delete PaypalSubscription');
    }
}
