<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Search Controls --}}
        <x-filament::section>
            <x-slot name="heading">Global Search</x-slot>
            <x-slot name="description">Search people, places, sources and events. Living individuals (born less than 100 years ago with no death record) from other teams are excluded for privacy.</x-slot>

            <div class="space-y-4">
                <div class="flex gap-4 items-end">
                    <div class="flex-1">
                        <label for="search-query" class="block text-sm font-medium mb-1">Search</label>
                        <input
                            type="text"
                            id="search-query"
                            wire:model.live.debounce.300ms="query"
                            placeholder="Enter name, place, source or event..."
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                        >
                    </div>
                    <div>
                        <label for="from-year" class="block text-sm font-medium mb-1">From year</label>
                        <input
                            type="number"
                            id="from-year"
                            wire:model.blur="fromYear"
                            placeholder="e.g. 1800"
                            class="w-28 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                        >
                    </div>
                    <div>
                        <label for="to-year" class="block text-sm font-medium mb-1">To year</label>
                        <input
                            type="number"
                            id="to-year"
                            wire:model.blur="toYear"
                            placeholder="e.g. 1900"
                            class="w-28 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 shadow-sm focus:border-primary-500 focus:ring-primary-500"
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
            {{-- People --}}
            @if(!empty($groups['people']))
                <x-filament::section>
                    <x-slot name="heading">People ({{ count($groups['people']) }})</x-slot>

                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($groups['people'] as $person)
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
                </x-filament::section>
            @endif

            {{-- Places --}}
            @if(!empty($groups['places']))
                <x-filament::section>
                    <x-slot name="heading">Places ({{ count($groups['places']) }})</x-slot>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($groups['places'] as $place)
                            <div class="py-3 first:pt-0 last:pb-0">
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white">📍 {{ $place->title }}</h3>
                                @if($place->description)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ \Illuminate\Support\Str::limit($place->description, 120) }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </x-filament::section>
            @endif

            {{-- Sources --}}
            @if(!empty($groups['sources']))
                <x-filament::section>
                    <x-slot name="heading">Sources ({{ count($groups['sources']) }})</x-slot>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($groups['sources'] as $source)
                            <div class="py-3 first:pt-0 last:pb-0">
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white">📄 {{ $source->name ?? $source->titl }}</h3>
                                @if($source->titl && $source->name)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $source->titl }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </x-filament::section>
            @endif

            {{-- Events --}}
            @if(!empty($groups['events']))
                <x-filament::section>
                    <x-slot name="heading">Events ({{ count($groups['events']) }})</x-slot>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($groups['events'] as $event)
                            <div class="py-3 first:pt-0 last:pb-0">
                                <h3 class="text-base font-semibold text-gray-900 dark:text-white">📅 {{ $event->title ?: $event->type }}</h3>
                                <div class="text-sm text-gray-500 dark:text-gray-400 space-x-4">
                                    @if($event->type)<span>{{ $event->type }}</span>@endif
                                    @if($event->year)<span>{{ $event->year }}</span>@endif
                                    @if($event->plac)<span>📍 {{ $event->plac }}</span>@endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-filament::section>
            @endif
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
                    <p class="text-gray-500 dark:text-gray-400">Enter a name, place, source or event to search genealogy records.</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Results from other public teams will exclude living individuals for privacy.</p>
                </div>
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
