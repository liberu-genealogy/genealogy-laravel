<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Research Progress Widget -->
        @livewire('research-progress-widget')

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('filament.app.pages.user-checklists') }}" 
                   class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Create New Checklist</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Start a new research project</p>
                    </div>
                </a>

                <a href="{{ route('filament.app.resources.checklist-templates.index') }}" 
                   class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Browse Templates</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Find research templates</p>
                    </div>
                </a>

                <a href="{{ route('filament.app.pages.user-checklists') }}?statusFilter=overdue" 
                   class="flex items-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">View Overdue Items</h4>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Catch up on research</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Research Tips -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Research Tips</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-md font-medium text-gray-900 dark:text-white mb-2">Getting Started</h4>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <li>• Start with what you know and work backwards</li>
                        <li>• Interview older family members first</li>
                        <li>• Document your sources as you go</li>
                        <li>• Use multiple sources to verify information</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-md font-medium text-gray-900 dark:text-white mb-2">Best Practices</h4>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <li>• Set realistic research goals and deadlines</li>
                        <li>• Keep detailed research logs</li>
                        <li>• Back up your research regularly</li>
                        <li>• Connect with other researchers</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>