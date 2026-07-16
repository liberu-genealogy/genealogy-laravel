<?php

declare(strict_types=1);

namespace App\Providers;

use App\Actions\Jetstream\AddTeamMember;
use App\Actions\Jetstream\CreateTeam;
use App\Actions\Jetstream\DeleteTeam;
use App\Actions\Jetstream\DeleteUser;
use App\Actions\Jetstream\InviteTeamMember;
use App\Actions\Jetstream\RemoveTeamMember;
use App\Actions\Jetstream\UpdateTeamName;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;
use Override;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configurePermissions();

        Jetstream::createTeamsUsing(CreateTeam::class);
        Jetstream::updateTeamNamesUsing(UpdateTeamName::class);
        Jetstream::addTeamMembersUsing(AddTeamMember::class);
        Jetstream::inviteTeamMembersUsing(InviteTeamMember::class);
        Jetstream::removeTeamMembersUsing(RemoveTeamMember::class);
        Jetstream::deleteTeamsUsing(DeleteTeam::class);
        Jetstream::deleteUsersUsing(DeleteUser::class);
    }

    /**
     * Configure the roles and permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        // Every permission the tiers below reference. `manage-team` is the only
        // privilege that separates admin from editor once records-CRUD is shared.
        Jetstream::permissions([
            'create',
            'read',
            'update',
            'delete',
            'manage-team',
        ]);

        // Collaboration tiers, most -> least privileged. Each tier is a strict
        // superset of the one below it (SCOPE §10).
        Jetstream::role('admin', 'Administrator', [
            'create',
            'read',
            'update',
            'delete',
            'manage-team',
        ])->description('Administrators can perform any action, including managing team members.');

        Jetstream::role('editor', 'Editor', [
            'create',
            'read',
            'update',
            'delete',
        ])->description('Editors can create, read, update, and delete records.');

        Jetstream::role('contributor', 'Contributor', [
            'create',
            'read',
            'update',
        ])->description('Contributors can create, read, and update records, but cannot delete them or manage the team.');

        Jetstream::role('viewer', 'Viewer', [
            'read',
        ])->description('Viewers have read-only access.');
    }
}
