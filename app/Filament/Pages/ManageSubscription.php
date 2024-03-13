<?php

namespace App\Filament\Pages;

use App\Filament\Pages\CustomFilamentBasePage;
use App\Services\StripeSubscriptionService;
use Livewire\Component;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class ManageSubscription extends CustomFilamentBasePage
/**
 * Manages subscription operations within the Filament admin panel.
 * This includes viewing subscription details, updating subscriptions, and cancelling subscriptions.
 */
{
    protected static string $view = 'filament.pages.manage-subscription';

    public $subscriptionDetails;
    public $selectedPlan;
    public $plans = [];

    public function mount(StripeSubscriptionService $stripeService)
    {
        $this->subscriptionDetails = $stripeService->getCurrentSubscriptionDetails();
        $this->plans = $stripeService->getAvailablePlans();
    }

    public function updateSubscription()
    /**
     * Updates the user's subscription plan based on the selected option.
     * Validates the selected plan before proceeding with the update.
     */
    {
        $this->validate([
            'selectedPlan' => 'required|string',
        ]);

        resolve(StripeSubscriptionService::class)->updateSubscription($this->subscriptionDetails['id'], $this->selectedPlan);

        $this->subscriptionDetails = resolve(StripeSubscriptionService::class)->getCurrentSubscriptionDetails();
        $this->notify('success', 'Subscription updated successfully.');
    }

    public function cancelSubscription()
    {
        resolve(StripeSubscriptionService::class)->cancelSubscription($this->subscriptionDetails['id']);
        $this->subscriptionDetails = null;
        $this->notify('success', 'Subscription cancelled successfully.');
    }

    protected function getFormSchema(): array
    {
    /**
     * Mounts the component, initializing subscription details and available plans.
     * @param StripeSubscriptionService $stripeService The Stripe subscription service instance.
     */
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('selectedPlan')
                ->label('Select Plan')
                ->options($this->plans)
                ->required(),
            Forms\Components\Button::make('Update Subscription')
                ->onClick(fn () => $this->updateSubscription()),
            Forms\Components\Button::make('Cancel Subscription')
                ->onClick(fn () => $this->cancelSubscription())
                ->color('secondary'),
        ];
    }
}
    /**
     * Defines the form schema for managing subscriptions.
     * Includes a select input for plan selection and buttons for updating or cancelling the subscription.
     * @return array The form schema as an array of Filament components.
     */
