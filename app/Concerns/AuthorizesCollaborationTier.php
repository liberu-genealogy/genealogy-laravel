<?php

declare(strict_types=1);

namespace App\Concerns;

use Filament\Facades\Filament;

/**
 * Resolves an action against the collaboration tier the current user holds in
 * the team being viewed.
 *
 * Shared by the app panel's resources and its relation managers because they
 * must agree. They are separate authorisation surfaces in Filament — a resource
 * answers can* hooks, a relation manager answers one response method — and if
 * each carried its own list of which actions count as writing, the two would
 * drift and a viewer would end up able to do through a relationship what they
 * cannot do directly. That is precisely the gap this trait exists to close: the
 * tiers were enforced on resources first, and relation managers went on
 * resolving against model policies, several of which do not exist and therefore
 * allow.
 */
trait AuthorizesCollaborationTier
{
    /**
     * The permission an action requires, or null when the action is not one
     * this application recognises.
     *
     * Reading is listed explicitly and everything else must be named. The
     * default is null — refuse — rather than 'read', which is the arrangement
     * that let a viewer replicate records and tear relationships apart: those
     * action names had simply never been thought of. An action added later is
     * far more likely to write than to read, and refusing it fails visibly,
     * where allowing it fails silently.
     */
    protected static function permissionForCollaborationAction(string $action): ?string
    {
        return match ($action) {
            'view', 'viewAny', 'read', 'access' => 'read',
            'create', 'replicate' => 'create',
            'update', 'edit', 'restore', 'restoreAny', 'reorder',
            'attach', 'attachAny', 'associate', 'associateAny' => 'update',

            // Detaching and dissociating destroy a link a researcher recorded.
            // The record survives, but the relationship they established does
            // not, so they sit with delete rather than with update.
            'delete', 'deleteAny', 'forceDelete', 'forceDeleteAny',
            'detach', 'detachAny', 'dissociate', 'dissociateAny' => 'delete',

            default => null,
        };
    }

    /**
     * Whether the current user's tier in the team they are working in carries
     * this permission.
     *
     * Denies when there is no authenticated user and when there is no team. The
     * second matters most: console commands, queued jobs and anything outside a
     * team context have none, and falling through to "allowed" there would open
     * every resource, relation manager and component at once while every test
     * still passed. Jetstream answers true for the team's owner, who holds no
     * membership row and so no tier.
     *
     * The team is the one in the panel URL where there is a panel, and the
     * user's current team otherwise — the app panel's resources run with a
     * tenant set, while the Livewire components on plain web routes do not, and
     * both must resolve to the same team. This mirrors BelongsToTenant, so the
     * team a check authorises against is the team its records are scoped to.
     */
    protected static function collaborationTierPermits(string $permission): bool
    {
        $user = auth()->user();
        $team = Filament::getTenant() ?? $user?->currentTeam;

        if (! $user || ! $team) {
            return false;
        }

        return $user->hasTeamPermission($team, $permission);
    }

    /**
     * Refuse the request unless the current tier carries this permission.
     *
     * For callers that act rather than answer a can* hook — a Livewire method
     * reachable over the wire, which must guard itself because nothing upstream
     * does it for them.
     */
    protected function authorizeCollaborationTier(string $permission): void
    {
        abort_unless(static::collaborationTierPermits($permission), 403);
    }
}
