<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Descendant Chart</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Explore your family's descendants</p>
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

            @livewire(\App\Http\Livewire\DescendantChartWidget::class)
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Chart Features</h3>
                <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                    <li>• Click on any person to expand from them</li>
                    <li>• Switch between vertical and horizontal layouts</li>
                    <li>• Toggle spouse information display</li>
                    <li>• Adjust generations to control tree depth</li>
                </ul>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Layout Options</h3>
                <div class="space-y-2 text-xs">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-blue-100 border border-blue-300 rounded"></div>
                        <span class="text-gray-600 dark:text-gray-400">Vertical Tree</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-green-100 border border-green-300 rounded"></div>
                        <span class="text-gray-600 dark:text-gray-400">Horizontal Tree</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-yellow-100 border border-yellow-300 rounded"></div>
                        <span class="text-gray-600 dark:text-gray-400">Compact View</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Related Charts</h3>
                <div class="space-y-2">
                    <x-filament::button
                        color="gray"
                        icon="heroicon-o-chart-bar"
                        size="xs"
                        tag="a"
                        href="{{ \Filament\Facades\Filament::getUrl() }}/pedigree-chart"
                        class="w-full justify-start"
                    >
                        Pedigree Chart
                    </x-filament::button>
                    <x-filament::button
                        color="gray"
                        icon="heroicon-o-chart-pie"
                        size="xs"
                        tag="a"
                        href="{{ \Filament\Facades\Filament::getUrl() }}/fan-chart"
                        class="w-full justify-start"
                    >
                        Fan Chart
                    </x-filament::button>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
