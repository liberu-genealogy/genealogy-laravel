<x-filament-panels::page>
    <div class="space-y-4">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Trees are private by default. Make one public to share it beyond your team.
        </p>

        @forelse ($this->trees() as $tree)
            <div class="flex items-center justify-between bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                        {{ $tree->name ?: 'Untitled tree' }}
                    </h3>
                    <span class="text-xs {{ $tree->is_public ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400' }}">
                        {{ $tree->is_public ? 'Public' : 'Private' }}
                    </span>
                </div>

                <x-filament::button
                    wire:click="toggle({{ $tree->id }})"
                    :color="$tree->is_public ? 'gray' : 'primary'"
                >
                    {{ $tree->is_public ? 'Make private' : 'Make public' }}
                </x-filament::button>
            </div>
        @empty
            <div class="text-gray-600 dark:text-gray-400">
                No trees found.
            </div>
        @endforelse
    </div>
</x-filament-panels::page>
