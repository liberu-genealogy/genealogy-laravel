<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Descendant Chart Visualization</h2>
                <p class="text-sm text-gray-600 mt-1">
                    Explore the descendants of any person in your family tree. This chart shows children, 
                    grandchildren, and future generations branching out from your selected ancestor.
                </p>
            </div>

            @livewire('descendant-chart-component')
        </div>

        <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-purple-800">How to use the Descendant Chart</h3>
                    <div class="mt-2 text-sm text-purple-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Click on any person to make them the root ancestor</li>
                            <li>Use generation controls to show more descendant levels</li>
                            <li>The chart displays children, grandchildren, and beyond</li>
                            <li>Each node shows birth/death years when available</li>
                            <li>Navigate through the family tree by clicking on descendants</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>