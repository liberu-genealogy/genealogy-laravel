<x-filament-panels::page>
    <div class="space-y-6">
        @forelse ($this->reports() as $report)
            @php($stats = $report['stats'])
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                    {{ $report['tree']->name ?: 'Untitled tree' }}
                </h3>

                <div class="flex items-baseline gap-3 mb-3">
                    <span class="text-3xl font-bold text-primary-600 dark:text-primary-400">
                        {{ $stats['completeness'] }}%
                    </span>
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        of ancestry filled to {{ $stats['generations'] }} generations
                    </span>
                </div>

                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-4">
                    <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $stats['completeness'] }}%"></div>
                </div>

                <table class="min-w-full text-sm">
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <tr>
                            <td class="py-1 pr-4 text-gray-600 dark:text-gray-400">Ancestor slots ({{ $stats['generations'] }} gen)</td>
                            <td class="py-1 font-medium text-gray-900 dark:text-white">{{ $stats['total_slots'] }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 pr-4 text-gray-600 dark:text-gray-400">Filled slots</td>
                            <td class="py-1 font-medium text-gray-900 dark:text-white">{{ $stats['filled_slots'] }}</td>
                        </tr>
                        <tr>
                            <td class="py-1 pr-4 text-gray-600 dark:text-gray-400">Missing parents (of known people)</td>
                            <td class="py-1 font-medium text-gray-900 dark:text-white">{{ $stats['missing_parents'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @empty
            <div class="text-gray-600 dark:text-gray-400">
                No trees found. Create a tree with a root person to see its completeness.
            </div>
        @endforelse
    </div>
</x-filament-panels::page>
