<x-filament-panels::page>
    <div class="space-y-8">
        <!-- Premium Status Header -->
        <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-white/20 rounded-full p-3">
                        @svg('heroicon-o-star', 'h-8 w-8')
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">Premium Member</h1>
                        <p class="opacity-90">
                            @if($this->getSubscriptionData()['on_trial'])
                                Trial - {{ $this->getSubscriptionData()['trial_days_remaining'] }} days remaining
                            @else
                                Active subscription
                            @endif
                        </p>
                    </div>
                </div>
                
                <div class="text-right">
                    {!! auth()->user()->premium_badge !!}
                </div>
            </div>
        </div>

        <!-- Subscription Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="bg-green-100 dark:bg-green-900/20 rounded-lg p-3">
                        @svg('heroicon-o-check-circle', 'h-6 w-6 text-green-600 dark:text-green-400')
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Status</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white capitalize">
                            {{ $this->getSubscriptionData()['subscription_status'] ?? 'Active' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="bg-blue-100 dark:bg-blue-900/20 rounded-lg p-3">
                        @svg('heroicon-o-calendar', 'h-6 w-6 text-blue-600 dark:text-blue-400')
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Started</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $this->getSubscriptionData()['premium_started_at']?->format('M j, Y') ?? 'Today' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="bg-purple-100 dark:bg-purple-900/20 rounded-lg p-3">
                        @svg('heroicon-o-currency-pound', 'h-6 w-6 text-purple-600 dark:text-purple-400')
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Price</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">£4.99/month</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="bg-yellow-100 dark:bg-yellow-900/20 rounded-lg p-3">
                        @svg('heroicon-o-clock', 'h-6 w-6 text-yellow-600 dark:text-yellow-400')
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                            @if($this->getSubscriptionData()['on_trial'])
                                Trial Ends
                            @else
                                Next Billing
                            @endif
                        </p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                            @if($this->getSubscriptionData()['on_trial'])
                                {{ $this->getSubscriptionData()['trial_days_remaining'] }} days
                            @else
                                {{ now()->addMonth()->format('M j, Y') }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Premium Features Status -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Your Premium Features</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="flex items-center space-x-3">
                    <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg p-2">
                        @svg('heroicon-o-star', 'h-5 w-5 text-white')
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Premium Badge</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Show your premium status</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <div class="bg-blue-500 rounded-lg p-2">
                        @svg('heroicon-o-beaker', 'h-5 w-5 text-white')
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Unlimited DNA Uploads</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">No limits on DNA kit uploads</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <div class="bg-green-500 rounded-lg p-2">
                        @svg('heroicon-o-document-duplicate', 'h-5 w-5 text-white')
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Duplicate Checker</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Find duplicate people in your tree</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <div class="bg-yellow-500 rounded-lg p-2">
                        @svg('heroicon-o-magnifying-glass', 'h-5 w-5 text-white')
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Smart Matching</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Find matches in public trees</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <div class="bg-red-500 rounded-lg p-2">
                        @svg('heroicon-o-chat-bubble-left-ellipsis', 'h-5 w-5 text-white')
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Priority Support</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Get help faster</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <div class="bg-indigo-500 rounded-lg p-2">
                        @svg('heroicon-o-chart-bar', 'h-5 w-5 text-white')
                    </div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Advanced Charts</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Enhanced visualization tools</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Premium Tools</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('filament.app.resources.duplicate-checks.index') }}" 
                   class="flex items-center space-x-3 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                    @svg('heroicon-o-document-duplicate', 'h-6 w-6 text-green-600 dark:text-green-400')
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Run Duplicate Check</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Find potential duplicates</p>
                    </div>
                </a>

                <a href="{{ route('filament.app.resources.smart-matches.index') }}" 
                   class="flex items-center space-x-3 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                    @svg('heroicon-o-magnifying-glass', 'h-6 w-6 text-yellow-600 dark:text-yellow-400')
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Smart Matching</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Find ancestor matches</p>
                    </div>
                </a>

                <a href="{{ route('filament.app.resources.dna.create') }}" 
                   class="flex items-center space-x-3 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                    @svg('heroicon-o-beaker', 'h-6 w-6 text-blue-600 dark:text-blue-400')
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Upload DNA Kit</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Unlimited uploads</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-filament-panels::page>