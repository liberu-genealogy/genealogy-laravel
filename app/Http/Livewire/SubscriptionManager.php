/**
 * Livewire component for managing subscriptions from the user interface.
 * Allows users to view their current subscription, select a new plan, and cancel their subscription.
 */
&lt;?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Services\StripeSubscriptionService;

class SubscriptionManager extends Component
{
    public $subscriptionDetails;
    public $plans;
    public $selectedPlan;

    protected $rules = [
        'selectedPlan' => 'required|string',
    ];

    public function mount(StripeSubscriptionService $stripeService)
    {
        $this->subscriptionDetails = $stripeService->getCurrentSubscriptionDetails();
        $this->plans = $stripeService->getAvailablePlans();
    }

    public function updateSubscription(StripeSubscriptionService $stripeService)
    {
        $this->validate();

        try {
            $stripeService->updateSubscription($this->subscriptionDetails['id'], $this->selectedPlan);
            $this->subscriptionDetails = $stripeService->getCurrentSubscriptionDetails();
            $this->emit('notify', 'Subscription updated successfully.');
        } catch (\Exception $e) {
            $this->emit('notify', 'Error updating subscription: ' . $e->getMessage());
        }
    }

    public function cancelSubscription(StripeSubscriptionService $stripeService)
    {
        try {
            $stripeService->cancelSubscription($this->subscriptionDetails['id']);
            $this->subscriptionDetails = null;
            $this->emit('notify', 'Subscription cancelled successfully.');
        } catch (\Exception $e) {
            $this->emit('notify', 'Error cancelling subscription: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.subscription-manager', [
            'subscriptionDetails' => $this->subscriptionDetails,
            'plans' => $this->plans,
        ]);
    }
}
            'plans' => $this->plans,
        ]);
    }
}
            'subscriptionDetails' => $this->subscriptionDetails,
            'plans' => $this->plans,
        ]);
    }
}
            'plans' => $this->plans,
        ]);
    }
}
            'plans' => $this->plans,
        ]);
    }
}
