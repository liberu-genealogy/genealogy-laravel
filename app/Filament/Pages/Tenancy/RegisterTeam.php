<?php

namespace App\Filament\Pages\Tenancy;

use App\Models\Team;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;
use App\Services\StripeSubscriptionService;

class RegisterTeam extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register team';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
                TextInput::make('email')
                    ->label('Invite User by Email')
                    ->email()
                    ->required(false)
               /**
 ToggleButtons::make('Send Invitation')
                    ->action(function (array $data) {
                        $teamId = $this->record->id;
                        $email = $data['email'];

                        if (!empty($email) && !empty($teamId)) {
                            resolve(TeamInvitationController::class)->sendInvitation(new Request(['email' => $email, 'team_id' => $teamId]));
                        }
                    })
                    ->type('button'),
**/
            ]);

    }

    protected function handleRegistration(array $data): Team
    {
        $team = Team::create($data);

//        $team->users()->attach(auth()->user());

        $stripeService = resolve(StripeSubscriptionService::class);
        $stripeService->createTrialSubscription($team);

        return $team;
    }
}
