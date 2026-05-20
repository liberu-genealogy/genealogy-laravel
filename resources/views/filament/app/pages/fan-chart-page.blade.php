<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Fan Chart Visualization</h2>
                <p class="text-sm text-gray-600 mt-1">
                    Explore your ancestry in a beautiful circular fan layout. Each generation radiates outward from the center, 
                    showing your direct ancestors in an intuitive visual format.
                </p>
            </div>

            @livewire('fan-chart')
        </div>

        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">How to use the Fan Chart</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Click on any person to make them the center of the chart</li>
                            <li>Use the generation controls to show more or fewer generations</li>
                            <li>Toggle names and dates visibility for cleaner viewing</li>
                            <li>The chart automatically color-codes by gender for easy identification</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>