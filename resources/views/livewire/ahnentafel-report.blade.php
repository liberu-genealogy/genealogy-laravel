<div>
    <div class="mb-6">
        <div class="flex items-end gap-4">
            <div class="flex-1">
                <label for="personSelect" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select a Person</label>
                <select wire:model="selectedPersonId" wire:change="generateReport($event.target.value)" id="personSelect"
                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">-- Select a person --</option>
                    @foreach(\App\Models\Person::all() as $person)
                        <option value="{{ $person->id }}">{{ $person->fullname() }}</option>
                    @endforeach
                </select>
            </div>
            <button wire:click="clearReport" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600">
                Clear
            </button>
        </div>
    </div>

    <div class="flex justify-center text-center mt-3" wire:loading>
        Generating report...
    </div>

    @if (!empty($reportData))
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">#</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Name</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Sex</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Birth</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Death</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($reportData as $entry)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $entry['number'] }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $entry['name'] }}</td>
                            <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400">{{ $entry['sex'] }}</td>
                            <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400">{{ $entry['birth_date'] ?? '-' }}</td>
                            <td class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400">{{ $entry['death_date'] ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @elseif ($selectedPersonId)
        <div class="text-center text-gray-500 dark:text-gray-400 mt-4">
            No ancestor data found for the selected person.
        </div>
    @endif
</div>
