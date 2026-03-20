<?php

namespace App\Filament\App\Pages;

use Filament\Infolists\Components\TextEntry;
use Override;
use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Forms\Components\Placeholder;
use App\Services\SubscriptionService;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class EditTeam extends EditTenantProfile
{
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
