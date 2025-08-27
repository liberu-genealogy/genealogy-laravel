<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Family Tree Overview
        </x-slot>

        <x-slot name="description">
            A quick glimpse of your family tree structure
        </x-slot>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Tree Statistics -->
            <div class="lg:col-span-1">
                <div class="space-y-4">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Total People</p>
                                <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $this->getViewData()['totalPeople'] }}</p>
                            </div>
                            <div class="h-12 w-12 bg-blue-100 dark:bg-blue-800 rounded-lg flex items-center justify-center">
                                @svg('heroicon-o-users', 'h-6 w-6 text-blue-600 dark:text-blue-400')
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-green-600 dark:text-green-400">Generations</p>
                                <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ $this->getViewData()['totalGenerations'] }}</p>
                            </div>
                            <div class="h-12 w-12 bg-green-100 dark:bg-green-800 rounded-lg flex items-center justify-center">
                                @svg('heroicon-o-chart-bar', 'h-6 w-6 text-green-600 dark:text-green-400')
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Quick Actions</h4>
                        <div class="space-y-1">
                            <a href="{{ \Filament\Facades\Filament::getUrl() }}/people/create"
                               class="flex items-center space-x-2 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                                @svg('heroicon-o-plus', 'h-4 w-4')
                                <span>Add Person</span>
                            </a>
                            <a href="{{ \Filament\Facades\Filament::getUrl() }}/pedigree-chart"
                               class="flex items-center space-x-2 text-sm text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-200">
                                @svg('heroicon-o-chart-bar', 'h-4 w-4')
                                <span>View Pedigree Chart</span>
                            </a>
                            <a href="{{ \Filament\Facades\Filament::getUrl() }}/descendant-chart"
                               class="flex items-center space-x-2 text-sm text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-200">
                                @svg('heroicon-o-chart-pie', 'h-4 w-4')
                                <span>View Descendant Chart</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mini Tree Visualization -->
            <div class="lg:col-span-2">
                @if($this->getViewData()['rootPerson'])
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-4">Sample Family Tree</h4>
                        <div class="flex justify-center">
                            <div class="space-y-4">
                                <!-- Root Person -->
                                <div class="flex justify-center">
                                    <div class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-2 rounded-lg text-sm font-medium">
                                        {{ $this->getViewData()['rootPerson']->fullname() }}
                                    </div>
                                </div>

                                <!-- Parents Level -->
                                @if($this->getViewData()['generations'])
                                    <div class="flex justify-center space-x-8">
                                        @if(isset($this->getViewData()['generations']['parents']['father']))
                                            <div class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-3 py-2 rounded-lg text-sm">
                                                {{ $this->getViewData()['generations']['parents']['father']['person']->fullname() }}
                                                <div class="text-xs opacity-75">Father</div>
                                            </div>
                                        @endif
                                        @if(isset($this->getViewData()['generations']['parents']['mother']))
                                            <div class="bg-pink-100 dark:bg-pink-900 text-pink-800 dark:text-pink-200 px-3 py-2 rounded-lg text-sm">
                                                {{ $this->getViewData()['generations']['parents']['mother']['person']->fullname() }}
                                                <div class="text-xs opacity-75">Mother</div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <a href="{{ \Filament\Facades\Filament::getUrl() }}/pedigree-chart"
                               class="inline-flex items-center space-x-2 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                                <span>View Full Tree</span>
                                @svg('heroicon-o-arrow-right', 'h-4 w-4')
                            </a>
                        </div>
                    </div>
                @else
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 text-center">
                        <div class="mx-auto h-12 w-12 text-gray-400 mb-4">
                            @svg('heroicon-o-users', 'h-12 w-12')
                        </div>
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">No Family Tree Data</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Start by adding people and families to build your tree.
                        </p>
                        <div class="mt-4">
                            <a href="{{ \Filament\Facades\Filament::getUrl() }}/people/create"
                               class="inline-flex items-center space-x-2 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                                @svg('heroicon-o-plus', 'h-4 w-4')
                                <span>Add First Person</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
