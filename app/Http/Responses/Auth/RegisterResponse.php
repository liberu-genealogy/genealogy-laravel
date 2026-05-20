<?php

namespace App\Http\Responses\Auth;

use Filament\Facades\Filament;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request): mixed
    {
        if ($request->wantsJson()) {
            return response()->json(['two_factor' => false]);
        }

        /** @var \App\Models\User|null $user */
        $user = auth()->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $panel = Filament::getPanel('app');

        // When the panel uses tenancy and the newly registered user has no
        // default tenant yet, send them to the team-creation page.
        if ($panel->hasTenancy() && ! $user->getDefaultTenant($panel)) {
            return redirect('/app/new');
        }

        return redirect()->intended(Filament::getUrl());
    }
}
