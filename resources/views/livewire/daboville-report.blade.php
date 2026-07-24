<div>
    <x-report-layout
        title="d'Aboville Report"
        :subject="!empty($reportData) ? 'Descendants of ' . ($reportData[0]['name'] ?? '') : null"
    >
        <x-slot:toolbar>
            <form wire:submit.prevent="generateReport" class="flex items-end gap-3">
                <div class="flex-1">{{ $this->form }}</div>
                <div>{{ $this->generateAction }}</div>
            </form>
        </x-slot:toolbar>

        @if (! empty($reportData))
            <div class="space-y-1">
                @foreach ($reportData as $entry)
                    <div class="report-node" style="--depth: {{ $entry['depth'] }}">
                        <span class="report-number">{{ $entry['number'] }}</span>
                        <span>{{ $entry['name'] }}</span>
                        @if ($entry['birth'] || $entry['death'])
                            <span class="text-gray-500 dark:text-gray-400">({{ $entry['birth'] ?? '?' }}–{{ $entry['death'] ?? '' }})</span>
                        @endif
                    </div>
                @endforeach
            </div>
        @elseif ($selectedPersonId)
            <div class="report-empty">No descendants found for the selected person.</div>
        @endif
    </x-report-layout>
</div>
