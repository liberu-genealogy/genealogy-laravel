<?php

namespace App\Filament\App\Pages;

use App\Services\SubscriptionService;
use Exception;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class TrialExpiredPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = null;

    protected static ?string $navigationLabel = 'Trial Expired';

    protected static string|\UnitEnum|null $navigationGroup = '👤 Account & Settings';

    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.app.pages.trial-expired-page';

    protected static ?string $title = 'Your Trial Has Ended';

    protected static ?string $slug = 'trial-expired';

    public function mount(): void
    {
        $user = Auth::user();

        // If premium is globally enabled or user is actively premium, go to dashboard
        if (config('premium.enabled') || $user->isPremium()) {
            $this->redirect(route('filament.app.pages.premium-dashboard'));
            return;
        }

        // If user never started a trial, redirect to subscription page
        if (! $user->hasExpiredTrial()) {
            $this->redirect(route('filament.app.pages.subscription'));
        }
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false; // Never show in navigation; accessed via redirect only
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('subscribe')
                ->label('Subscribe Now – $2.99/month')
                ->icon('heroicon-o-credit-card')
                ->color('primary')
                ->size('lg')
                ->action('redirectToStripeCheckout'),

            Action::make('downgrade')
                ->label('Continue with Free Plan')
                ->icon('heroicon-o-arrow-down-circle')
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading('Downgrade to Free Plan')
                ->modalDescription('You will lose access to premium features (Duplicate Checker, Smart Matching, unlimited DNA uploads). All your family tree data is kept. Are you sure?')
                ->modalSubmitActionLabel('Yes, downgrade to free')
                ->action('downgradeToFree'),
        ];
    }

    public function redirectToStripeCheckout(): void
    {
        try {
            $user = Auth::user();
            $priceId = config('subscription.premium.stripe_price_id', 'price_premium_monthly');

            $successUrl = route('filament.app.pages.premium-dashboard');
            $cancelUrl = route('filament.app.pages.trial-expired');

            // Use Cashier to create a Stripe Checkout Session for the subscription
            $checkout = $user->newSubscription('premium', $priceId)
                ->checkout([
                    'success_url' => $successUrl,
                    'cancel_url' => $cancelUrl,
                ]);

            // Mark as premium upon successful checkout (webhook will also handle this)
            $this->redirect($checkout->url);
        } catch (Exception $e) {
            Notification::make()
                ->title('Payment Error')
                ->body('Unable to start the checkout process. Please try again or contact support.')
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

    public function getPricingData(): array
    {
        return app(SubscriptionService::class)->getPricingInfo();
    }
}
