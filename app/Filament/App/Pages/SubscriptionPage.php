<?php

namespace App\Filament\App\Pages;

use App\Services\SubscriptionService;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class SubscriptionPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationLabel = 'Premium Subscription';

    protected static ?string $navigationGroup = '👤 Account & Settings';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.app.pages.subscription-page';

    protected static ?string $title = 'Premium Subscription';

    public function mount(): void
    {
        // Redirect if user is already premium
        if (Auth::user()->isPremium()) {
            $this->redirect(route('filament.app.pages.premium-dashboard'));
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('subscribe')
                ->label('Start Premium Trial')
                ->icon('heroicon-o-star')
                ->color('primary')
                ->size('lg')
                ->action('startTrial'),
        ];
    }

    public function startTrial(): void
    {
        try {
            $subscriptionService = app(SubscriptionService::class);
            $user = Auth::user();

            // Create trial subscription
            $subscription = $subscriptionService->createPremiumSubscription($user);

            Notification::make()
                ->title('Premium Trial Started!')
                ->body('Welcome to Premium! Your 7-day trial has begun.')
                ->success()
                ->send();

            $this->redirect(route('filament.app.pages.premium-dashboard'));

        } catch (\Exception $e) {
            Notification::make()
                ->title('Subscription Error')
                ->body('There was an error starting your trial. Please try again.')
                ->danger()
                ->send();
        }
    }

    public function getPricingData(): array
    {
        $subscriptionService = app(SubscriptionService::class);
        return $subscriptionService->getPricingInfo();
    }

    public function getDnaLimitData(): array
    {
        $subscriptionService = app(SubscriptionService::class);
        return $subscriptionService->checkDnaUploadLimit(Auth::user());
    }
}
