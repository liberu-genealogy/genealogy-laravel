<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Pedigree Chart Visualization</h2>
                <p class="text-sm text-gray-600 mt-1">
                    Explore your direct ancestors in a traditional pedigree format. Each generation shows your parents, 
                    grandparents, great-grandparents, and beyond in a clear hierarchical layout.
                </p>
            </div>

            @livewire('pedigree-chart')
        </div>

        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">How to use the Pedigree Chart</h3>
                    <div class="mt-2 text-sm text-green-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Click on any person to make them the root of the chart</li>
                            <li>Use the generation controls to show more or fewer generations</li>
                            <li>Toggle dates visibility for cleaner viewing</li>
                            <li>The chart is color-coded by gender for easy identification</li>
                            <li>Hover over person boxes to see expand options</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>