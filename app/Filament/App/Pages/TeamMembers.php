<?php

declare(strict_types=1);

namespace App\Filament\App\Pages;

use App\Models\Team;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Laravel\Jetstream\Jetstream;

/**
 * Lets a team owner set what each collaborator may do with the team's records.
 *
 * The tiers are Jetstream's, declared in the service provider and stored on the
 * membership, and AppResource enforces them. Until both existed together each
 * was useless: the tiers were assigned and read by nothing, and there was
 * nowhere in the application to assign them anyway — Jetstream's own team views
 * are not routed here and the tenant profile page has no member management. A
 * tier could only be changed by editing the database.
 *
 * The set of tiers is fixed on purpose. SPEC §10 names exactly four, and they
 * are meaningful only because AppResource knows what each one permits; a team
 * inventing its own would produce a tier nothing could enforce. This page
 * therefore assigns roles and never creates them, which also means it cannot
 * mint the team-less role that would confer administration everywhere — the
 * escalation route that has to be guarded whenever roles become editable.
 *
 * Ownership, not tier, gates this page. An admin-tier member holds
 * 'manage-team', but TeamPolicy restricts member changes to the owner, and
 * quietly widening that here would be a different decision made in the wrong
 * place.
 *
 * canAccess() is the whole authorisation, and that is deliberate rather than an
 * oversight. Filament re-runs it on every Livewire hydration, not only on
 * mount, so it also covers ownership lost while the page sits open — the case
 * a per-action check would normally exist for. An earlier version did carry a
 * Gate check inside the action; it was removed on finding that no test could
 * make it fire, because canAccess had already refused the request. A branch
 * that cannot execute is not defence in depth, it is a reader's false
 * assurance that the action authorises itself.
 *
 * TeamMembersPageTest pins the hydration behaviour this now leans on, so if
 * Filament stops re-authorising, a test says so rather than a user finding out.
 */
class TeamMembers extends Page
{
    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    #[\Override]
    protected string $view = 'filament.app.pages.team-members';

    #[\Override]
    protected static ?string $title = 'Team Members';

    #[\Override]
    protected static ?string $navigationLabel = 'Members';

    // Team membership is account/team administration — without this the page
    // floated as an ungrouped nav item outside the ten-group taxonomy.
    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = '👤 Account & Settings';

    #[\Override]
    public static function canAccess(): bool
    {
        $team = Filament::getTenant();
        $user = auth()->user();

        return $team && $user && $user->ownsTeam($team);
    }

    /**
     * The owner first, then members in the order they joined. The owner holds
     * no membership row, so they are prepended rather than queried.
     *
     * @return list<array{id: int, name: string, email: string, tier: string, is_owner: bool}>
     */
    public function getMembers(): array
    {
        $team = $this->team();

        if (! $team) {
            return [];
        }

        /** @var User|null $owner */
        $owner = $team->owner;

        $rows = [];

        if ($owner) {
            $rows[] = [
                'id' => (int) $owner->getKey(),
                'name' => (string) $owner->name,
                'email' => (string) $owner->email,
                'tier' => 'Owner',
                'is_owner' => true,
            ];
        }

        /** @var User $user */
        foreach ($team->users as $user) {
            $rows[] = [
                'id' => (int) $user->getKey(),
                'name' => (string) $user->name,
                'email' => (string) $user->email,
                'tier' => (string) ($user->membership->role ?? ''),
                'is_owner' => false,
            ];
        }

        return $rows;
    }

    public function setTierAction(): Action
    {
        return Action::make('setTier')
            ->label('Change tier')
            ->schema([
                Select::make('role')
                    ->label('Collaboration tier')
                    ->options($this->tierOptions())
                    ->required(),
            ])
            ->action(function (array $arguments, array $data): void {
                $team = $this->team();

                // Only an actual member can be retiered. This rejects the
                // owner, who has no membership row, and any id that names
                // someone outside the team — the argument arrives from the
                // browser and is not evidence of anything.
                /** @var User|null $member */
                $member = $team?->users->find($arguments['user'] ?? null);

                abort_unless((bool) ($team && $member), 403);

                $team->users()->updateExistingPivot($member->getKey(), ['role' => $data['role']]);
            });
    }

    /**
     * @return array<string, string>
     */
    protected function tierOptions(): array
    {
        $options = [];

        foreach (Jetstream::$roles as $role) {
            $options[$role->key] = $role->name;
        }

        return $options;
    }

    protected function team(): ?Team
    {
        $team = Filament::getTenant();

        return $team instanceof Team ? $team : null;
    }

    /**
     * @return array<string, mixed>
     */
    #[\Override]
    protected function getViewData(): array
    {
        return [
            'members' => $this->getMembers(),
        ];
    }
}
