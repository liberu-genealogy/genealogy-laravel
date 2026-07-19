<?php

namespace App\Filament\App\Resources;

use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

/**
 * Base for every app-panel resource. Authorisation for all 44 of them resolves
 * here, against the collaboration tier the member holds in the team they are
 * looking at.
 *
 * Every method below used to answer auth()->check(). The tiers SPEC §10 calls
 * for — viewer, contributor, editor, admin — were defined with explicit
 * permissions, assigned to members when they joined a team, and then read by
 * nothing: there was not one call to hasTeamPermission anywhere in the
 * application. A viewer invited to look at a family's research could delete
 * every person in it.
 *
 * The tier lives on the membership, so these questions are answered by
 * Jetstream rather than by the permission library. Those are two different
 * things and it is worth being explicit about which is which: the permission
 * library governs application-wide rights and the admin panel, while these
 * tiers govern what a collaborator may do to one team's records. A viewer in
 * this sense is not a user lacking a global role; they are a member of this
 * team, deliberately granted read access to it and nothing more.
 *
 * A resource needing different rules should override the specific method
 * rather than reinstating a blanket allow.
 *
 * WHAT THIS DOES NOT COVER, because a base class for resources can only speak
 * for resources, and an unqualified claim here would be worse than none:
 *
 * - Relation managers. Filament routes their authorisation to the model's
 *   policy unless the manager names a related resource, and none of the six
 *   here do. A viewer who may open a record can still act on what hangs off it.
 * - Custom pages. Not resources, so nothing here applies; each needs its own
 *   check. TreePrivacy has one, since publishing a family tree is the most
 *   consequential thing in the application. The rest do not yet.
 * - Livewire components addressed directly, and bare ->action() closures on
 *   resources, which mutate records without consulting any of this.
 *
 * Those are older than this class's enforcement and are a separate ticket.
 * They are listed because "the app panel is authorised now" is the conclusion a
 * reader would otherwise draw, and it would be wrong.
 */
abstract class AppResource extends Resource
{
    #[\Override]
    public static function canViewAny(): bool
    {
        return static::tierPermits('read');
    }

    #[\Override]
    public static function canView(Model $record): bool
    {
        return static::tierPermits('read');
    }

    #[\Override]
    public static function canCreate(): bool
    {
        return static::tierPermits('create');
    }

    #[\Override]
    public static function canEdit(Model $record): bool
    {
        return static::tierPermits('update');
    }

    #[\Override]
    public static function canDelete(Model $record): bool
    {
        return static::tierPermits('delete');
    }

    #[\Override]
    public static function canDeleteAny(): bool
    {
        return static::tierPermits('delete');
    }

    #[\Override]
    public static function canForceDelete(Model $record): bool
    {
        return static::tierPermits('delete');
    }

    #[\Override]
    public static function canForceDeleteAny(): bool
    {
        return static::tierPermits('delete');
    }

    #[\Override]
    public static function canRestore(Model $record): bool
    {
        return static::tierPermits('update');
    }

    #[\Override]
    public static function canRestoreAny(): bool
    {
        return static::tierPermits('update');
    }

    /**
     * Reaching the resource at all requires being able to read it. Navigation
     * is driven from here, so a viewer still sees the resource listed and a
     * non-member sees nothing.
     */
    #[\Override]
    public static function canAccess(): bool
    {
        return static::tierPermits('read');
    }

    /**
     * What Filament consults for actions that have no can* hook of their own.
     *
     * Only reading is listed explicitly, and everything else needs a stated
     * permission. An earlier version had it the other way round — a
     * `default => 'read'` arm — which quietly handed a viewer every action
     * nobody had thought to name. Filament passes replicate, reorder, attach,
     * detach, associate and dissociate through here, so a viewer could
     * duplicate records and pull relationships apart while holding read only.
     *
     * The default therefore denies. An unrecognised action is more likely to be
     * one added later than one that is safe, and refusing it produces a visible
     * failure and a line in this match, where allowing it produces neither.
     */
    #[\Override]
    public static function getAuthorizationResponse(string|UnitEnum $action, ?Model $record = null): Response
    {
        $name = $action instanceof UnitEnum ? $action->name : (string) $action;

        $permission = match ($name) {
            'view', 'viewAny', 'read', 'access' => 'read',
            'create', 'replicate' => 'create',
            'update', 'edit', 'restore', 'restoreAny', 'reorder',
            'attach', 'attachAny', 'detach', 'detachAny',
            'associate', 'associateAny', 'dissociate', 'dissociateAny' => 'update',
            'delete', 'deleteAny', 'forceDelete', 'forceDeleteAny' => 'delete',
            default => null,
        };

        if ($permission === null) {
            return Response::deny();
        }

        return static::tierPermits($permission) ? Response::allow() : Response::deny();
    }

    /**
     * Whether the current user's tier in the team being viewed carries this
     * permission.
     *
     * Denies when there is no authenticated user and when there is no tenant.
     * The second is the one worth stating: console commands, queued jobs and
     * any request outside the panel have no tenant, and a check that fell
     * through to "allowed" there would leave all 44 resources open while every
     * test still passed. Jetstream answers true for the team's owner, who holds
     * no membership row and therefore no tier.
     */
    protected static function tierPermits(string $permission): bool
    {
        $user = auth()->user();
        $team = Filament::getTenant();

        if (! $user || ! $team) {
            return false;
        }

        return $user->hasTeamPermission($team, $permission);
    }
}
