<?php

namespace App\Filament\Pages;

use App\Filament\Pages\CustomFilamentBasePage;
use App\Services\StripeSubscriptionService;
use Livewire\Component;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class ManageSubscription extends CustomFilamentBasePage
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
