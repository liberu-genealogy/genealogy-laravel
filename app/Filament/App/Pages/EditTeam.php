<?php

declare(strict_types=1);

namespace App\Filament\App\Pages;

use App\Services\SubscriptionService;
use Filament\Facades\Filament;
use Filament\Infolists\Components\TextEntry;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Override;

/**
 * The team profile editor.
 *
 * Authorization is owner-only, and it is inherited — do not add a member with
 * an editor or admin tier to this form expecting them to be able to save it.
 * EditTenantProfile::canView() runs authorize('update', $team), which routes
 * through TeamPolicy::update (ownership), and it is enforced on mount and
 * re-checked on every Livewire hydration. So any field added below is already
 * behind the ownership gate — save() is only reachable after canView passes
 * again. There is deliberately no canView override here; adding one that reads
 * ownsTeam() directly would bypass the policy this relies on.
 *
 * Owner-only rather than admin-tier is a decision, not an accident: editing the
 * team's identity and subscription is an owner/billing concern, not the
 * research a collaborator does. EditTeamAuthorizationTest pins it.
 */
class EditTeam extends EditTenantProfile
{
    #[Override]
    protected string $view = 'filament.pages.edit-team';

    public static function getLabel(): string
    {
        return 'Edit Team';
    }

    #[Override]
    public function form(Schema $schema): Schema
    {
        $team = Filament::getTenant();
        $subscriptionService = app(SubscriptionService::class);

        return $schema
            ->components([
                // Section::make('Subscription')
                //     ->schema([
                //         TextEntry::make('subscription_status')
                //             ->label('Subscription Status')
                //             ->state($subscriptionService->getSubscriptionStatus($team)),
                //         // Add more subscription-related fields here
                //     ]),
            ]);
    }

    #[Override]
    protected function getViewData(): array
    {
        return [
            'team' => Filament::getTenant(),
        ];
    }
}
