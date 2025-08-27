<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Pedigree Chart</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Explore your ancestral lineage</p>
                </div>
                <div class="flex space-x-2">
                    <x-filament::button
                        color="gray"
                        icon="heroicon-o-arrow-path"
                        size="sm"
                    >
                        Refresh
                    </x-filament::button>
                    <x-filament::button
                        color="primary"
                        icon="heroicon-o-arrow-down-tray"
                        size="sm"
                    >
                        Export
                    </x-filament::button>
                </div>
            </div>

            @livewire(\App\Http\Livewire\PedigreeChartWidget::class)
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Chart Tips</h3>
                <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                    <li>• Click on any person to make them the root</li>
                    <li>• Use zoom controls to navigate large trees</li>
                    <li>• Toggle dates and photos for different views</li>
                    <li>• Adjust generations to see more or fewer levels</li>
                </ul>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Legend</h3>
                <div class="space-y-2 text-xs">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-blue-500 rounded"></div>
                        <span class="text-gray-600 dark:text-gray-400">Male</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-pink-500 rounded"></div>
                        <span class="text-gray-600 dark:text-gray-400">Female</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-green-500 rounded border-2 border-green-700"></div>
                        <span class="text-gray-600 dark:text-gray-400">Root Person</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Quick Actions</h3>
                <div class="space-y-2">
                    <x-filament::button
                        color="gray"
                        icon="heroicon-o-user-plus"
                        size="xs"
                        tag="a"
                        href="{{ \Filament\Facades\Filament::getUrl() }}/people/create"
                        class="w-full justify-start"
                    >
                        Add Person
                    </x-filament::button>
                    <x-filament::button
                        color="gray"
                        icon="heroicon-o-chart-pie"
                        size="xs"
                        tag="a"
                        href="{{ \Filament\Facades\Filament::getUrl() }}/descendant-chart"
                        class="w-full justify-start"
                    >
                        Descendant Chart
                    </x-filament::button>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
