<?php

namespace App\Http\Responses\Auth;

use Filament\Facades\Filament;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request): mixed
    {
        if ($request->wantsJson()) {
            return response()->json(['two_factor' => false]);
        }

        /** @var \App\Models\User|null $user */
        $user = auth()->user();
        $panel = Filament::getPanel('app');

        // When the panel uses tenancy and the logged-in user has no default
        // tenant yet, send them to the team-creation page instead of the
        // panel root so they are not left on a blank /app route.
        if ($panel->hasTenancy() && ! $user?->getDefaultTenant($panel)) {
            return redirect('/app/new');
        }

        return redirect()->intended(Filament::getUrl());
    }
}
