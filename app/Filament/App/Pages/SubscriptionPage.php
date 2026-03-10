<?php

namespace App\Filament\App\Pages;

use Exception;
use App\Services\SubscriptionService;
use Filament\Actions\Action;
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

    protected static ?string $slug = 'subscription';

    public function mount(): void
    {
        $user = Auth::user();

        // When premium features are globally enabled, everyone is premium
        if (config('premium.enabled')) {
            $this->redirect(route('filament.app.pages.premium-dashboard'));
            return;
        }

        // If trial has expired, redirect to the trial-expired page
        if ($user->hasExpiredTrial()) {
            $this->redirect(route('filament.app.pages.trial-expired'));
            return;
        }

        // Redirect if user is already premium
        if ($user->isPremium()) {
            $this->redirect(route('filament.app.pages.premium-dashboard'));
            return;
        }
    }

    public static function shouldRegisterNavigation(): bool
    {
        // Hide the subscription page when premium features are globally enabled
        if (config('premium.enabled')) {
            return false;
        }
        $user = Auth::user();
        return Auth::check() && ! $user->isPremium() && ! $user->hasExpiredTrial();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('checkout')
                ->label('Subscribe with Card')
                ->icon('heroicon-o-credit-card')
                ->color('success')
                ->size('lg')
                ->action('redirectToCheckout'),

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

            // Create trial subscription (no payment method provided)
            $subscriptionService->createPremiumSubscription($user);

            // Refresh user and show trial end date if available
            $user = $user->fresh();
            $trialDays = config('subscription.premium.trial_days', 14);
            $endsAt = optional($user->trial_ends_at)->toFormattedDateString();
            $body = $endsAt
                ? "Welcome to Premium! Your {$trialDays}-day trial ends on {$endsAt}."
                : "Welcome to Premium! Your {$trialDays}-day trial has begun.";

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

    /**
     * Redirect the user to a Stripe Checkout session so they can enter a
     * payment method and start a paid subscription (trial applied automatically).
     */
    public function redirectToCheckout(): void
    {
        $user = Auth::user();

        // delegate the heavy lifting to our service which already knows about
        // configuration and the proper price identifier
        $checkout = app(SubscriptionService::class)->createCheckoutRedirect($user);

        if (is_object($checkout) && property_exists($checkout, 'url') && $checkout->url) {
            $this->redirect($checkout->url);
        } elseif ($checkout instanceof \Illuminate\Http\RedirectResponse) {
            $this->redirect($checkout->getTargetUrl());
        } else {
            Notification::make()
                ->title('Subscription Error')
                ->body('Unable to start Stripe checkout.')
                ->danger()
                ->send();
        }
    }

    public function getDnaLimitData(): array
    {
        $subscriptionService = app(SubscriptionService::class);
        return $subscriptionService->checkDnaUploadLimit(Auth::user());
    }
}
