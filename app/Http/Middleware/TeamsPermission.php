<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TeamsPermission
{
    /**
     * Handle an incoming request.
     *
     * Reads the tenant Filament has identified, falling back to the user's
     * stored team where there is no tenant — the admin panel, which does not
     * use tenancy, and any non-panel route.
     *
     * This used to read the stored team only, and ran in the panel's auth
     * middleware. Filament nests the tenant middleware group inside the auth
     * group, so it executed before the tenant was identified and before the
     * SwitchTeam listener updated that column: every role and permission check
     * in the request resolved against the team the user arrived on while the
     * interface rendered the one they had navigated to. It is now registered as
     * tenant middleware, which runs after IdentifyTenant.
     *
     * @param  Closure(Request):Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user) {
            $teamId = $this->tenantFromRoute($request, $user) ?? $user->current_team_id;

            if (! empty($teamId)) {
                app(PermissionRegistrar::class)->setPermissionsTeamId($teamId);
            }
        }

        return $next($request);
    }

    /**
     * The tenant named in the URL, if the user may actually reach it.
     *
     * Read from the route rather than from Filament, because this runs before
     * IdentifyTenant has identified anything. Membership is checked here so a
     * request for a team the user cannot access never sets a permission context
     * for it — that request is about to be refused anyway.
     */
    private function tenantFromRoute(Request $request, mixed $user): int|string|null
    {
        $route = $request->route();

        if (! $route || ! $route->hasParameter('tenant')) {
            return null;
        }

        $panel = Filament::getCurrentOrDefaultPanel();

        if (! $panel?->hasTenancy()) {
            return null;
        }

        try {
            $tenant = $panel->getTenant($route->parameter('tenant'));
        } catch (Throwable) {
            // Unknown tenant. IdentifyTenant will refuse the request; fall back
            // rather than failing here with a less useful error.
            return null;
        }

        return $user->canAccessTenant($tenant) ? $tenant->getKey() : null;
    }
}
