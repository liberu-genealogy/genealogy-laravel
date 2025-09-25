<?php

namespace App\Filament\App\Pages;

use Exception;
use App\Services\SubscriptionService;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class SubscriptionPage extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationLabel = 'Premium Subscription';

    protected static string | \UnitEnum | null $navigationGroup = '👤 Account & Settings';

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

            // Create trial subscription (no assignment needed)
            $subscriptionService->createPremiumSubscription($user);

            // Refresh user and show trial end date if available
            $user = $user->fresh();
            $endsAt = optional($user->trial_ends_at)->toFormattedDateString();
            $body = $endsAt
                ? "Welcome to Premium! Your trial ends on {$endsAt}."
                : 'Welcome to Premium! Your 7-day trial has begun.';

            Notification::make()
                ->title('Premium Trial Started!')
                ->body($body)
                ->success()
                ->send();

            $this->redirect(route('filament.app.pages.premium-dashboard'));

        } catch (Exception $e) {
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
