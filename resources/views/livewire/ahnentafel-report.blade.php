<div>
    <x-report-layout
        title="Ahnentafel Report"
        :subject="!empty($reportData) ? 'Ancestors of ' . ($reportData[1]['name'] ?? '') : null"
    >
        <x-slot:toolbar>
            <form wire:submit.prevent="generateReport" class="flex items-end gap-3">
                <div class="flex-1">{{ $this->form }}</div>
                <div>{{ $this->generateAction }}</div>
            </form>
        </x-slot:toolbar>

        @if (! empty($reportData))
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">#</th>
                            <th class="px-4 py-2 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Name</th>
                            <th class="px-4 py-2 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Sex</th>
                            <th class="px-4 py-2 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Birth</th>
                            <th class="px-4 py-2 text-left text-xs font-medium uppercase text-gray-500 dark:text-gray-400">Death</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach ($reportData as $entry)
                            <tr>
                                <td class="px-4 py-2 text-sm"><span class="report-number">{{ $entry['number'] }}</span></td>
                                <td class="px-4 py-2 text-sm">{{ $entry['name'] }}</td>
                                <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400">{{ $entry['sex'] }}</td>
                                <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400">{{ $entry['birth_date'] ?? '-' }}</td>
                                <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400">{{ $entry['death_date'] ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif ($selectedPersonId)
            <div class="report-empty">No ancestor data found for the selected person.</div>
        @endif
    </x-report-layout>
</div>
