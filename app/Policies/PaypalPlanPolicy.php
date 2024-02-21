<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\PaypalPlan;
use App\Models\User;

class PaypalPlanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any PaypalPlan');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PaypalPlan $paypalplan): bool
    {
        return $user->checkPermissionTo('view PaypalPlan');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create PaypalPlan');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PaypalPlan $paypalplan): bool
    {
        return $user->checkPermissionTo('update PaypalPlan');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PaypalPlan $paypalplan): bool
    {
        return $user->checkPermissionTo('delete PaypalPlan');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PaypalPlan $paypalplan): bool
    {
        return $user->checkPermissionTo('restore PaypalPlan');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PaypalPlan $paypalplan): bool
    {
        return $user->checkPermissionTo('force-delete PaypalPlan');
    }
}
