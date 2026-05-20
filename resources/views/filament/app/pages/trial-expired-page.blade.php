<x-filament-panels::page>
    <div class="space-y-8">
        <!-- Trial Expired Header -->
        <div class="bg-gradient-to-r from-orange-500 to-red-500 rounded-lg p-6 text-white">
            <div class="flex items-center space-x-4">
                <div class="bg-white/20 rounded-full p-3">
                    @svg('heroicon-o-clock', 'h-8 w-8')
                </div>
                <div>
                    <h1 class="text-2xl font-bold">Your Free Trial Has Ended</h1>
                    <p class="opacity-90 mt-1">
                        Subscribe for just $2.99/month to keep access to all premium features, or continue with the free plan.
                    </p>
                </div>
            </div>
        </div>

        <!-- Options Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Premium Option -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border-2 border-primary-500 p-6 relative">
                <div class="absolute top-4 right-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-300">
                        Recommended
                    </span>
                </div>

                <div class="flex items-center space-x-3 mb-4">
                    <div class="bg-primary-100 dark:bg-primary-900/20 rounded-lg p-2">
                        @svg('heroicon-o-star', 'h-6 w-6 text-primary-600 dark:text-primary-400')
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Premium Plan</h2>
                </div>

                <div class="mb-6">
                    <span class="text-4xl font-bold text-gray-900 dark:text-white">$2.99</span>
                    <span class="text-gray-500 dark:text-gray-400">/month</span>
                </div>

                <ul class="space-y-3 mb-6">
                    @foreach($this->getPricingData()['premium']['features'] as $feature)
                        <li class="flex items-center">
                            @svg('heroicon-o-check-circle', 'h-5 w-5 text-green-500 mr-3 flex-shrink-0')
                            <span class="text-gray-700 dark:text-gray-300">{{ $feature }}</span>
                        </li>
                    @endforeach
                </ul>

                <x-filament::button
                    color="primary"
                    size="lg"
                    class="w-full"
                    wire:click="redirectToStripeCheckout"
                    wire:target="redirectToStripeCheckout"
                    wire:loading.attr="disabled"
                    aria-label="Subscribe to Premium for $2.99/month"
                >
                    <span class="inline-flex items-center justify-center">
                        <svg wire:loading wire:target="redirectToStripeCheckout" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="redirectToStripeCheckout">Subscribe Now – $2.99/month</span>
                        <span wire:loading wire:target="redirectToStripeCheckout">Redirecting to checkout…</span>
                    </span>
                </x-filament::button>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center">
                    Secure payment via Stripe · Cancel anytime
                </p>
            </div>

            <!-- Free Option -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-2">
                        @svg('heroicon-o-user', 'h-6 w-6 text-gray-600 dark:text-gray-400')
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Free Plan</h2>
                </div>

                <div class="mb-6">
                    <span class="text-4xl font-bold text-gray-900 dark:text-white">$0</span>
                    <span class="text-gray-500 dark:text-gray-400">/forever</span>
                </div>

                <ul class="space-y-3 mb-6">
                    <li class="flex items-center">
                        @svg('heroicon-o-check-circle', 'h-5 w-5 text-green-500 mr-3 flex-shrink-0')
                        <span class="text-gray-700 dark:text-gray-300">Full family tree builder</span>
                    </li>
                    <li class="flex items-center">
                        @svg('heroicon-o-check-circle', 'h-5 w-5 text-green-500 mr-3 flex-shrink-0')
                        <span class="text-gray-700 dark:text-gray-300">Interactive charts</span>
                    </li>
                    <li class="flex items-center">
                        @svg('heroicon-o-check-circle', 'h-5 w-5 text-green-500 mr-3 flex-shrink-0')
                        <span class="text-gray-700 dark:text-gray-300">1 DNA kit upload</span>
                    </li>
                    <li class="flex items-center">
                        @svg('heroicon-o-check-circle', 'h-5 w-5 text-green-500 mr-3 flex-shrink-0')
                        <span class="text-gray-700 dark:text-gray-300">Media & document storage</span>
                    </li>
                    <li class="flex items-center">
                        @svg('heroicon-o-check-circle', 'h-5 w-5 text-green-500 mr-3 flex-shrink-0')
                        <span class="text-gray-700 dark:text-gray-300">Research tools</span>
                    </li>
                    <li class="flex items-center">
                        @svg('heroicon-o-x-circle', 'h-5 w-5 text-red-400 mr-3 flex-shrink-0')
                        <span class="text-gray-500 dark:text-gray-400">Duplicate Checker</span>
                    </li>
                    <li class="flex items-center">
                        @svg('heroicon-o-x-circle', 'h-5 w-5 text-red-400 mr-3 flex-shrink-0')
                        <span class="text-gray-500 dark:text-gray-400">Smart Matching</span>
                    </li>
                    <li class="flex items-center">
                        @svg('heroicon-o-x-circle', 'h-5 w-5 text-red-400 mr-3 flex-shrink-0')
                        <span class="text-gray-500 dark:text-gray-400">Unlimited DNA uploads</span>
                    </li>
                </ul>

                <x-filament::button
                    color="gray"
                    size="lg"
                    class="w-full"
                    wire:click="downgradeToFree"
                    wire:target="downgradeToFree"
                    wire:loading.attr="disabled"
                    aria-label="Continue with Free Plan"
                >
                    <span wire:loading.remove wire:target="downgradeToFree">Continue with Free Plan</span>
                    <span wire:loading wire:target="downgradeToFree">Processing…</span>
                </x-filament::button>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 text-center">
                    Keep all your data · Upgrade again anytime
                </p>
            </div>
        </div>

        <!-- FAQ -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Frequently Asked Questions</h3>

            <div class="space-y-4">
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-white">Will I lose my data if I choose the free plan?</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        No. All your family tree data, media, and documents are kept. You simply lose access to premium-only tools.
                    </p>
                </div>

                <div>
                    <h4 class="font-medium text-gray-900 dark:text-white">Can I re-subscribe later?</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Yes. You can subscribe again at any time from the Premium Subscription page.
                    </p>
                </div>

                <div>
                    <h4 class="font-medium text-gray-900 dark:text-white">What payment methods are accepted?</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        We accept all major credit and debit cards through Stripe's secure payment processing.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
