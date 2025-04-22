<?php

namespace App\Filament\App\Pages;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Services\SubscriptionService;

class CreateTeam extends RegisterTenant
{

    #[\Override]
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Team Name')
                    ->required()
                    ->maxLength(255),
                Checkbox::make('premium_membership')
                    ->label('Subscribe to Premium Membership')
                    ->helperText('14-day free trial, then $9.99/month'),
            ]);
    }
    
    #[\Override]
    protected function handleRegistration(array $data): Model
    {
        $team = app(\App\Actions\Jetstream\CreateTeam::class)->create(auth()->user(), $data);
    
        if ($data['premium_membership']) {
            $subscriptionService = app(SubscriptionService::class);
            $subscriptionService->createTrialSubscription($team);
        }
    
        return $team;
    }

    // public function getBreadcrumbs(): array
    // {
    //     return [
    //         url()->current() => 'Create Team',
    //     ];
    // }

    public static function getLabel(): string
    {
        return 'Create Team';
    }
}
