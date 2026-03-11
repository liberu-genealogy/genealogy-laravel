<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Search Controls --}}
        <x-filament::section>
            <x-slot name="heading">Search People</x-slot>
            <x-slot name="description">Search across genealogy records. Living individuals (born less than 100 years ago with no death record) from other teams are excluded for privacy.</x-slot>

            <div class="space-y-4">
                <div class="flex gap-4 items-end">
                    <div class="flex-1">
                        <label for="search-query" class="block text-sm font-medium mb-1">Search</label>
                        <input
                            type="text"
                            id="search-query"
                            wire:model.live.debounce.300ms="query"
                            placeholder="Enter name, place, or description..."
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                        >
                    </div>
                    <button
                        wire:click="search"
                        class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition"
                    >
                        Search
                    </button>
                </div>

                <div class="flex items-center gap-2">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="searchGlobal" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:after:border-gray-600 peer-checked:bg-primary-600"></div>
                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Search across all public teams</span>
                    </label>
                </div>
            </div>
        </x-filament::section>

        {{-- Loading indicator --}}
        <div wire:loading wire:target="search,query" class="flex justify-center">
            <x-filament::loading-indicator class="h-8 w-8" />
        </div>

        {{-- Results --}}
        @if($totalResults > 0)
            <x-filament::section>
                <x-slot name="heading">Results ({{ number_format($totalResults) }} found)</x-slot>

                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($results as $person)
                        <div class="py-4 first:pt-0 last:pb-0">
                            <div class="flex items-start gap-4">
                                {{-- Avatar --}}
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-lg font-bold text-gray-500">
                                        {{ strtoupper(substr($person->givn ?? '?', 0, 1)) }}{{ strtoupper(substr($person->surn ?? '?', 0, 1)) }}
                                    </div>
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-base font-semibold text-gray-900 dark:text-white truncate">
                                            {{ $person->givn }} {{ $person->surn }}
                                        </h3>
                                        @if($person->sex === 'M')
                                            <span class="text-blue-500 text-sm">♂</span>
                                        @elseif($person->sex === 'F')
                                            <span class="text-pink-500 text-sm">♀</span>
                                        @endif

                                        @if($person->team_id !== auth()->user()?->currentTeam?->id)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                Shared
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mt-1 text-sm text-gray-600 dark:text-gray-400 space-x-4">
                                        @if($person->birthday)
                                            <span>b. {{ $person->birthday->format('Y') }}</span>
                                        @elseif($person->birth_year)
                                            <span>b. {{ $person->birth_year }}</span>
                                        @endif

                                        @if($person->birthday_plac)
                                            <span>📍 {{ $person->birthday_plac }}</span>
                                        @endif

                                        @if($person->deathday)
                                            <span>d. {{ $person->deathday->format('Y') }}</span>
                                        @elseif($person->death_year)
                                            <span>d. {{ $person->death_year }}</span>
                                        @endif

                                        @if($person->deathday_plac)
                                            <span>📍 {{ $person->deathday_plac }}</span>
                                        @endif
                                    </div>

                                    @if($person->description)
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-500 truncate">{{ \Illuminate\Support\Str::limit($person->description, 120) }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($lastPage > 1)
                    <div class="flex items-center justify-between pt-4 border-t dark:border-gray-700">
                        <button
                            wire:click="previousPage"
                            @if($currentPage <= 1) disabled @endif
                            class="px-4 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 dark:hover:bg-gray-800"
                        >
                            Previous
                        </button>
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            Page {{ $currentPage }} of {{ $lastPage }}
                        </span>
                        <button
                            wire:click="nextPage"
                            @if($currentPage >= $lastPage) disabled @endif
                            class="px-4 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 dark:hover:bg-gray-800"
                        >
                            Next
                        </button>
                    </div>
                @endif
            </x-filament::section>
        @elseif(!empty(trim($query)) && strlen(trim($query)) >= 2)
            <x-filament::section>
                <div class="text-center py-8">
                    <div class="text-4xl mb-2">🔍</div>
                    <p class="text-gray-500 dark:text-gray-400">No results found for "{{ $query }}"</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Try different keywords or enable global search to include public teams.</p>
                </div>
            </x-filament::section>
        @elseif(empty(trim($query)))
            <x-filament::section>
                <div class="text-center py-8">
                    <div class="text-4xl mb-2">🌳</div>
                    <p class="text-gray-500 dark:text-gray-400">Enter a name, place, or description to search genealogy records.</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Results from other public teams will exclude living individuals for privacy.</p>
                </div>
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
