<?php

namespace App\Filament\App\Pages;

use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Form;
use App\Services\SubscriptionService;

class EditTeam extends EditTenantProfile
{
    protected static string $view = 'filament.pages.edit-team';

    public static function getLabel(): string
    {
        return 'Edit Team';
    }

    #[\Override]
    public function form(Form $form): Form
    {
        $team = Filament::getTenant();
        $subscriptionService = app(SubscriptionService::class);
        return $form
            ->schema([
                Section::make('Subscription')
                    ->schema([
                        Placeholder::make('status')
                            ->label('Subscription Status')
                            ->content($subscriptionService->getSubscriptionStatus($team)),
                        // Add more subscription-related fields here
                    ]),
            ]);
    }
    
    #[\Override]
    protected function getViewData(): array
    {
        return [
            'team' => Filament::getTenant(),
        ];
    }
}
