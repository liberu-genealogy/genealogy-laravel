<x-filament-panels::page>
    <div class="space-y-6">
        @livewire('gamification-dashboard')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-lg p-6 border border-blue-200">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-blue-900">How to Earn Points</h3>
                </div>
                <div class="space-y-2 text-sm text-blue-800">
                    <div class="flex justify-between">
                        <span>Add a person to your tree</span>
                        <span class="font-semibold">25 pts</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Create a family relationship</span>
                        <span class="font-semibold">50 pts</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Add a life event</span>
                        <span class="font-semibold">15-30 pts</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Upload a photo</span>
                        <span class="font-semibold">20 pts</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Complete your profile</span>
                        <span class="font-semibold">100 pts</span>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-lg p-6 border border-green-200">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="ml-3 text-lg font-semibold text-green-900">Achievement Categories</h3>
                </div>
                <div class="space-y-2 text-sm text-green-800">
                    <div class="flex items-center">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                        <span><strong>Milestones:</strong> First steps, level achievements</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                        <span><strong>Research:</strong> Family building, documentation</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-2 h-2 bg-purple-500 rounded-full mr-2"></span>
                        <span><strong>Social:</strong> Daily streaks, community participation</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-2 h-2 bg-orange-500 rounded-full mr-2"></span>
                        <span><strong>General:</strong> Profile completion, media uploads</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Privacy Notice</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>Your leaderboard visibility can be controlled in the dashboard. You can choose to hide your progress from public leaderboards while still tracking your personal achievements and points.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>