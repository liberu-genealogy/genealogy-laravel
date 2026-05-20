<?php

namespace App\Filament\App\Pages;

use Filament\Facades\Filament;
use Exception;
use App\Services\SubscriptionService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class PremiumDashboardPage extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationLabel = 'Premium Dashboard';

    protected static string | \UnitEnum | null $navigationGroup = '👤 Account & Settings';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.app.pages.premium-dashboard-page';

    protected static ?string $title = 'Premium Dashboard';

    protected static ?string $slug = 'premium-dashboard';

    public function mount(): void
    {
        // Disable if premium feature is off
        if (! config('premium.enabled')) {
            $user = Auth::user();

            // Trial expired – send to the card-details / trial-expired page
            if ($user->hasExpiredTrial()) {
                $this->redirect(route('filament.app.pages.trial-expired', ['tenant' => auth()->user()->currentTeam]));
                return;
            }

            // Not premium at all – send to subscription page
            if (! $user->isPremium()) {
                $this->redirect(route('filament.app.pages.subscription', ['tenant' => auth()->user()->currentTeam]));
                return;
            }
        }
    }

    protected function getHeaderActions(): array
    {
        $user = Auth::user();
        $actions = [];

        if ($user->subscription('premium')?->cancelled()) {
            $actions[] = Action::make('resume')
                ->label('Resume Subscription')
                ->icon('heroicon-o-play')
                ->color('success')
                ->action('resumeSubscription');
        } else {
            $actions[] = Action::make('cancel')
                ->label('Cancel Subscription')
                ->icon('heroicon-o-x-mark')
                ->color('danger')
                ->requiresConfirmation()
                ->action('cancelSubscription');
        }

        $actions[] = Action::make('downgrade')
            ->label('Downgrade to Free')
            ->icon('heroicon-o-arrow-down-circle')
            ->color('gray')
            ->requiresConfirmation()
            ->modalHeading('Downgrade to Free Plan')
            ->modalDescription('You will lose access to premium features (Duplicate Checker, Smart Matching, unlimited DNA uploads). All your family tree data is kept. Are you sure?')
            ->modalSubmitActionLabel('Yes, downgrade to free')
            ->action('downgradeToFree');

        return $actions;
    }

    public function cancelSubscription(): void
    {
        try {
            $subscriptionService = app(SubscriptionService::class);
            $subscriptionService->cancelPremiumSubscription(Auth::user());

            Notification::make()
                ->title('Subscription Cancelled')
                ->body('Your subscription has been cancelled. You can continue using premium features until the end of your billing period.')
                ->warning()
                ->send();

            $this->redirect(Filament::getUrl() . '/subscription');

        } catch (Exception $e) {
            Notification::make()
                ->title('Cancellation Error')
                ->body('There was an error cancelling your subscription. Please try again.')
                ->danger()
                ->send();
        }
    }

    public function downgradeToFree(): void
    {
        try {
            $subscriptionService = app(SubscriptionService::class);
            $subscriptionService->downgradeToFree(Auth::user());

            Notification::make()
                ->title('Downgraded to Free Plan')
                ->body('You now have access to all standard features. You can upgrade again at any time.')
                ->success()
                ->send();

            $this->redirect(Filament::getUrl());
        } catch (Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('There was an error processing your request. Please try again.')
                ->danger()
                ->send();
        }
    }

    public function resumeSubscription(): void
    {
        try {
            $subscriptionService = app(SubscriptionService::class);
            $subscriptionService->resumePremiumSubscription(Auth::user());

            Notification::make()
                ->title('Subscription Resumed')
                ->body('Your premium subscription has been resumed successfully!')
                ->success()
                ->send();

        } catch (Exception $e) {
            Notification::make()
                ->title('Resume Error')
                ->body('There was an error resuming your subscription. Please try again.')
                ->danger()
                ->send();
        }
    }

    public function getSubscriptionData(): array
    {
        $user = Auth::user();
        $subscription = $user->subscription('premium');

        return [
            'is_premium' => $user->isPremium(),
            'on_trial' => $user->onPremiumTrial(),
            'trial_days_remaining' => $user->trialDaysRemaining(),
            'subscription_status' => $subscription?->stripe_status,
            'subscription_ends_at' => $subscription?->ends_at,
            'premium_started_at' => $user->premium_started_at,
        ];
    }

    public function getPremiumFeatures(): array
    {
        $subscriptionService = app(SubscriptionService::class);
        return $subscriptionService->getPremiumFeaturesStatus(Auth::user());
    }
}
