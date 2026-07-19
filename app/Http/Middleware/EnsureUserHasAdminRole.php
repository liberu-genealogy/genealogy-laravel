<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasAdminRole
{
    public function handle(Request $request, Closure $next): Response
    {
        // hasGlobalRole, not hasRole: this gates administration, which is not
        // scoped to a team, and hasRole answers for the current team only.
        // Currently unreachable — AdminPanelProvider has its registration
        // commented out — but left correct rather than one uncomment away from
        // granting admin on the basis of whichever team a user last opened.
        if (! $request->user() || ! $request->user()->hasGlobalRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
