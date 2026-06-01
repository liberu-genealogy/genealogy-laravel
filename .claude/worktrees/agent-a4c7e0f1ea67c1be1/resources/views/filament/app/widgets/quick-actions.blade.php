<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Quick Actions
        </x-slot>
        
        <x-slot name="description">
            Common tasks to help you manage your family tree
        </x-slot>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($this->getViewData()['actions'] as $action)
                <a href="{{ $action['url'] }}" 
                   class="group relative overflow-hidden rounded-lg border border-gray-200 bg-white p-6 shadow-sm transition-all hover:shadow-md hover:border-{{ $action['color'] }}-300 dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-{{ $action['color'] }}-100 text-{{ $action['color'] }}-600 dark:bg-{{ $action['color'] }}-900/20 dark:text-{{ $action['color'] }}-400">
                                @svg($action['icon'], 'h-6 w-6')
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $action['label'] }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $action['description'] }}
                            </p>
                        </div>
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-r from-{{ $action['color'] }}-500/0 to-{{ $action['color'] }}-500/5 opacity-0 transition-opacity group-hover:opacity-100"></div>
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>