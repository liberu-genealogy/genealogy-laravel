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
