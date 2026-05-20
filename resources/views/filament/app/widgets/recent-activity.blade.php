<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Recent Activity
        </x-slot>
        
        <x-slot name="description">
            Latest additions to your family tree
        </x-slot>

        <div class="space-y-3">
            @forelse($this->getViewData()['activities'] as $activity)
                <div class="flex items-center space-x-3 rounded-lg border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex-shrink-0">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-{{ $activity['color'] }}-100 text-{{ $activity['color'] }}-600 dark:bg-{{ $activity['color'] }}-900/20 dark:text-{{ $activity['color'] }}-400">
                            @svg($activity['icon'], 'h-4 w-4')
                        </div>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ $activity['title'] }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $activity['subtitle'] }}
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <p class="text-xs text-gray-400 dark:text-gray-500">
                            {{ $activity['date']->diffForHumans() }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="text-center py-6">
                    <div class="mx-auto h-12 w-12 text-gray-400">
                        @svg('heroicon-o-clock', 'h-12 w-12')
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No recent activity</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Start adding people and families to see activity here.
                    </p>
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>