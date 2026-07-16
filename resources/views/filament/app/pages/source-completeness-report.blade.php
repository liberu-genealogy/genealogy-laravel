<x-filament-panels::page>
    @php($report = $this->report())
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Overall source coverage</h3>
            <div class="flex items-baseline gap-3">
                <span class="text-3xl font-bold text-primary-600 dark:text-primary-400">{{ $report['overall'] }}%</span>
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    of people and events have at least one linked source
                </span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 dark:text-gray-400">
                        <th class="py-2 pr-4">Record type</th>
                        <th class="py-2 pr-4">With source</th>
                        <th class="py-2 pr-4">Total</th>
                        <th class="py-2">Coverage</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr>
                        <td class="py-2 pr-4 text-gray-900 dark:text-white">People</td>
                        <td class="py-2 pr-4">{{ $report['persons']['with_source'] }}</td>
                        <td class="py-2 pr-4">{{ $report['persons']['total'] }}</td>
                        <td class="py-2 font-medium text-gray-900 dark:text-white">{{ $report['persons']['percentage'] }}%</td>
                    </tr>
                    <tr>
                        <td class="py-2 pr-4 text-gray-900 dark:text-white">Events</td>
                        <td class="py-2 pr-4">{{ $report['events']['with_source'] }}</td>
                        <td class="py-2 pr-4">{{ $report['events']['total'] }}</td>
                        <td class="py-2 font-medium text-gray-900 dark:text-white">{{ $report['events']['percentage'] }}%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
