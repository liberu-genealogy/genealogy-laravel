<x-filament-panels::page>
    <div class="space-y-8">
        <!-- Premium Features Overview -->
        <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg p-8 text-white">
            <div class="text-center">
                <div class="flex justify-center mb-4">
                    <div class="bg-white/20 rounded-full p-3">
                        @svg('heroicon-o-star', 'h-8 w-8')
                    </div>
                </div>
                <h1 class="text-3xl font-bold mb-2">Upgrade to Premium</h1>
                <p class="text-lg opacity-90 mb-6">Unlock powerful genealogy tools and unlimited features</p>
                
                <div class="bg-white/10 rounded-lg p-4 inline-block">
                    <div class="text-4xl font-bold">£4.99</div>
                    <div class="text-sm opacity-75">per month</div>
                </div>
                
                <div class="mt-6">
                    <div class="bg-yellow-400 text-yellow-900 px-4 py-2 rounded-full inline-block font-semibold">
                        🎉 7-Day Free Trial
                    </div>
                </div>
            </div>
        </div>

        <!-- Feature Comparison -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Standard Plan -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="text-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Standard</h3>
                    <p class="text-gray-500 dark:text-gray-400">Free forever</p>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white mt-2">£0</div>
                </div>
                
                <ul class="space-y-3">
                    <li class="flex items-center">
                        @svg('heroicon-o-check', 'h-5 w-5 text-green-500 mr-3')
                        <span class="text-gray-700 dark:text-gray-300">Basic family tree</span>
                    </li>
                    <li class="flex items-center">
                        @svg('heroicon-o-check', 'h-5 w-5 text-green-500 mr-3')
                        <span class="text-gray-700 dark:text-gray-300">Standard charts</span>
                    </li>
                    <li class="flex items-center">
                        @svg('heroicon-o-check', 'h-5 w-5 text-green-500 mr-3')
                        <span class="text-gray-700 dark:text-gray-300">1 DNA kit upload</span>
                    </li>
                    <li class="flex items-center">
                        @svg('heroicon-o-x-mark', 'h-5 w-5 text-red-500 mr-3')
                        <span class="text-gray-400 line-through">Premium badge</span>
                    </li>
                    <li class="flex items-center">
                        @svg('heroicon-o-x-mark', 'h-5 w-5 text-red-500 mr-3')
                        <span class="text-gray-400 line-through">Duplicate checker</span>
                    </li>
                    <li class="flex items-center">
                        @svg('heroicon-o-x-mark', 'h-5 w-5 text-red-500 mr-3')
                        <span class="text-gray-400 line-through">Smart matching</span>
                    </li>
                </ul>
            </div>

            <!-- Premium Plan -->
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-lg border-2 border-purple-200 dark:border-purple-700 p-6 relative">
                <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                    <span class="bg-gradient-to-r from-purple-500 to-pink-500 text-white px-4 py-1 rounded-full text-sm font-semibold">
                        Most Popular
                    </span>
                </div>
                
                <div class="text-center mb-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Premium</h3>
                    <p class="text-gray-500 dark:text-gray-400">7-day free trial</p>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white mt-2">£4.99</div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">per month</p>
                </div>
                
                <ul class="space-y-3">
                    @foreach($this->getPricingData()['premium']['features'] as $feature)
                        <li class="flex items-center">
                            @svg('heroicon-o-check', 'h-5 w-5 text-green-500 mr-3')
                            <span class="text-gray-700 dark:text-gray-300">{{ $feature }}</span>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-6">
                    <x-filament::button
                        color="primary"
                        size="lg"
                        class="w-full"
                        wire:click="startTrial"
                    >
                        Start Free Trial
                    </x-filament::button>
                </div>
            </div>
        </div>

        <!-- Current Usage -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Current Usage</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                        {{ $this->getDnaLimitData()['remaining'] }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        DNA uploads remaining
                    </div>
                    <div class="text-xs text-gray-400 mt-1">
                        Limit: {{ $this->getDnaLimitData()['limit'] }}
                    </div>
                </div>
                
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                        {{ auth()->user()->dna_uploads_count }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        DNA kits uploaded
                    </div>
                </div>
                
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                        Standard
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Current plan
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Frequently Asked Questions</h3>
            
            <div class="space-y-4">
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-white">What happens during the free trial?</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        You get full access to all premium features for 7 days. No payment required upfront.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-white">Can I cancel anytime?</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Yes, you can cancel your subscription at any time. You'll continue to have access until the end of your billing period.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-white">What payment methods do you accept?</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        We accept all major credit cards through Stripe's secure payment processing.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>