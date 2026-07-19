<?php

namespace App\Filament\App\Resources;

use App\Concerns\AuthorizesCollaborationTier;
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
 * The tier check itself lives in AuthorizesCollaborationTier, shared with
 * AppRelationManager and with the Livewire components that write records on
 * plain web routes, so all three resolve the same team and the same rule.
 *
 * WHAT THIS DOES NOT COVER, because a base class for resources can only speak
 * for resources, and an unqualified claim here would be worse than none:
 *
 * - Some custom pages. Each is not a resource, so nothing here reaches it and
 *   it needs its own check. The pages that write shared records have one now —
 *   TreePrivacy gates publishing, the GEDCOM export is confined to its team's
 *   directory. The pages that remain unguarded write the acting user's own
 *   account (password, profile, tokens) or send messages between teammates,
 *   neither of which a collaboration tier governs.
 * - A few per-user surfaces with their own ownership rules rather than tiers —
 *   the checklist manager and the event RSVP — tracked separately, including a
 *   cross-user access gap in the former that is not a tier question.
 *
 * Relation managers, the tree builder, the transcription component and the
 * custom action closures — on resources and on the photos relation manager —
 * used to head this list and are now covered, each guarding at the tier rather
 * than trusting ->visible(), which Filament does not enforce on invocation.
 * "The app panel is fully authorised" is still not quite the claim to make,
 * which is why the exceptions above are named rather than waved at.
 */
abstract class AppResource extends Resource
{
    use AuthorizesCollaborationTier;

    #[\Override]
    public static function canViewAny(): bool
    {
        return static::collaborationTierPermits('read');
    }

    #[\Override]
    public static function canView(Model $record): bool
    {
        return static::collaborationTierPermits('read');
    }

    #[\Override]
    public static function canCreate(): bool
    {
        return static::collaborationTierPermits('create');
    }

    #[\Override]
    public static function canEdit(Model $record): bool
    {
        return static::collaborationTierPermits('update');
    }

    #[\Override]
    public static function canDelete(Model $record): bool
    {
        return static::collaborationTierPermits('delete');
    }

    #[\Override]
    public static function canDeleteAny(): bool
    {
        return static::collaborationTierPermits('delete');
    }

    #[\Override]
    public static function canForceDelete(Model $record): bool
    {
        return static::collaborationTierPermits('delete');
    }

    #[\Override]
    public static function canForceDeleteAny(): bool
    {
        return static::collaborationTierPermits('delete');
    }

    #[\Override]
    public static function canRestore(Model $record): bool
    {
        return static::collaborationTierPermits('update');
    }

    #[\Override]
    public static function canRestoreAny(): bool
    {
        return static::collaborationTierPermits('update');
    }

    /**
     * Reaching the resource at all requires being able to read it. Navigation
     * is driven from here, so a viewer still sees the resource listed and a
     * non-member sees nothing.
     */
    #[\Override]
    public static function canAccess(): bool
    {
        return static::collaborationTierPermits('read');
    }

    /**
     * What Filament consults for actions that have no can* hook of their own.
     *
     * The mapping lives in the shared trait, because relation managers answer
     * the same question through a different method and the two must not drift.
     * An unrecognised action is refused there rather than treated as reading —
     * an earlier version of this method did the latter and handed viewers
     * replicate, reorder, attach, detach, associate and dissociate.
     */
    #[\Override]
    public static function getAuthorizationResponse(string|UnitEnum $action, ?Model $record = null): Response
    {
        $name = $action instanceof UnitEnum ? $action->name : (string) $action;

        $permission = static::permissionForCollaborationAction($name);

        if ($permission === null) {
            return Response::deny();
        }

        return static::collaborationTierPermits($permission) ? Response::allow() : Response::deny();
    }
}
