<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Concerns\AuthorizesCollaborationTier;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Model;

/**
 * Base for the app panel's relation managers, so a collaborator's tier governs
 * what hangs off a record as well as the record itself.
 *
 * Filament routes a relation manager's authorisation to the related model's
 * policy unless the manager names a related resource, and none of these did.
 * Several of the models involved have no policy at all, which allows. So a
 * viewer — who may legitimately open a person, because they hold read — could
 * delete every photo, source and association attached to them. Being let in to
 * the parent record is what made it reachable.
 *
 * Naming a related resource is the other way to fix this and was not taken: it
 * also changes how Filament routes that manager's create and edit pages, which
 * is a behavioural change bought for an authorisation problem.
 *
 * Every action funnels through the one method below, so unlike a resource there
 * is no list of hooks to keep complete.
 */
abstract class AppRelationManager extends RelationManager
{
    use AuthorizesCollaborationTier;

    #[\Override]
    public function getAuthorizationResponse(string $action, ?Model $record = null): Response
    {
        $permission = static::permissionForCollaborationAction($action);

        if ($permission === null) {
            return Response::deny();
        }

        return static::collaborationTierPermits($permission)
            ? Response::allow()
            : Response::deny();
    }
}
