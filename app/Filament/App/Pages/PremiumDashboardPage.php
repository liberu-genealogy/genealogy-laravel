<?php

namespace App\Filament\App\Pages;

use App\Services\SubscriptionService;
use Exception;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class PremiumDashboardPage extends Page
{
    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-star';

    #[\Override]
    protected static ?string $navigationLabel = 'Premium Dashboard';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = '👤 Account & Settings';

    #[\Override]
    protected static ?int $navigationSort = 1;

    #[\Override]
    protected string $view = 'filament.app.pages.premium-dashboard-page';

    #[\Override]
    protected static ?string $title = 'Premium Dashboard';

    #[\Override]
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

    #[\Override]
    protected function getHeaderActions(): array
    {
        $user = Auth::user();
        $actions = [];

        // Only offer Resume while the subscription is still within its grace period –
        // that is the only state Cashier's resume() will accept.
        if ($user->subscription('premium')?->onGracePeriod()) {
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

        // Pause/resume: a paused subscription can be resumed; an active,
        // unpaused one can be paused. Pausing stops billing AND access (ADR 0002).
        $subscription = $user->subscription('premium');
        if ($subscription && $subscription->paused_at !== null) {
            $actions[] = Action::make('unpause')
                ->label('Resume from Pause')
                ->icon('heroicon-o-play')
                ->color('success')
                ->action('unpauseSubscription');
        } elseif ($subscription && $subscription->stripe_status === 'active' && ! $subscription->canceled()) {
            $actions[] = Action::make('pause')
                ->label('Pause Subscription')
                ->icon('heroicon-o-pause')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Pause Subscription')
                ->modalDescription('Billing stops and premium access is paused until you resume. Your data is kept.')
                ->modalSubmitActionLabel('Yes, pause')
                ->action('pauseSubscription');
        }

        // Only a real Stripe customer has a portal; a local-trial premium user
        // has no stripe_id, so offering it would try to create a customer / fail.
        if ($user->hasStripeId()) {
            $actions[] = Action::make('manageBilling')
                ->label('Manage Billing')
                ->icon('heroicon-o-credit-card')
                ->color('primary')
                ->action('manageBilling');
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

    public function manageBilling(): void
    {
        try {
            $portal = app(SubscriptionService::class)->createBillingPortalRedirect(Auth::user());

            $this->redirect($portal->getTargetUrl());
        } catch (Exception) {
            Notification::make()
                ->title('Billing Error')
                ->body('Unable to open the billing portal. Please try again.')
                ->danger()
                ->send();
        }
    }

    public function pauseSubscription(): void
    {
        try {
            app(SubscriptionService::class)->pausePremiumSubscription(Auth::user());

            Notification::make()
                ->title('Subscription Paused')
                ->body('Billing and premium access are paused until you resume.')
                ->warning()
                ->send();
        } catch (Exception) {
            Notification::make()
                ->title('Pause Error')
                ->body('There was an error pausing your subscription. Please try again.')
                ->danger()
                ->send();
        }
    }

    public function unpauseSubscription(): void
    {
        try {
            app(SubscriptionService::class)->unpausePremiumSubscription(Auth::user());

            Notification::make()
                ->title('Subscription Resumed')
                ->body('Your subscription is active again and premium access is restored.')
                ->success()
                ->send();
        } catch (Exception) {
            Notification::make()
                ->title('Resume Error')
                ->body('There was an error resuming your subscription. Please try again.')
                ->danger()
                ->send();
        }
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

            $this->redirect(Filament::getUrl().'/subscription');

        } catch (Exception) {
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
        } catch (Exception) {
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

        } catch (Exception) {
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
