<div class="space-y-6">
    <!-- Header with Controls -->
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Research Progress</h3>
        <div class="flex gap-3">
            <select wire:model.live="selectedPeriod" 
                    class="text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                <option value="7">Last 7 days</option>
                <option value="30">Last 30 days</option>
                <option value="90">Last 90 days</option>
                <option value="365">Last year</option>
            </select>
            <select wire:model.live="selectedSubjectType" 
                    class="text-sm rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                <option value="all">All Subjects</option>
                <option value="App\Models\Person">Persons</option>
                <option value="App\Models\Family">Families</option>
            </select>
        </div>
    </div>

    <!-- Progress Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Checklists -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Checklists</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['total_checklists'] }}</p>
                </div>
            </div>
        </div>

        <!-- Completed Checklists -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Completed</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['completed_checklists'] }}</p>
                    <p class="text-xs text-green-600 dark:text-green-400">{{ $stats['completion_rate'] }}% complete</p>
                </div>
            </div>
        </div>

        <!-- In Progress -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">In Progress</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['in_progress_checklists'] }}</p>
                </div>
            </div>
        </div>

        <!-- Overdue -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Overdue</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['overdue_checklists'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Overall Progress Bar -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <div class="flex justify-between items-center mb-2">
            <h4 class="text-md font-medium text-gray-900 dark:text-white">Overall Research Progress</h4>
            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $stats['overall_progress'] }}%</span>
        </div>
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
            <div class="bg-gradient-to-r from-blue-500 to-green-500 h-3 rounded-full transition-all duration-500" 
                 style="width: {{ $stats['overall_progress'] }}%"></div>
        </div>
        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
            <span>{{ $stats['completed_checklists'] }} completed</span>
            <span>{{ $stats['total_checklists'] }} total</span>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Completions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h4 class="text-md font-medium text-gray-900 dark:text-white">Recent Activity</h4>
                <p class="text-sm text-gray-600 dark:text-gray-400">Last {{ $selectedPeriod }} days</p>
            </div>
            <div class="p-4 space-y-3 max-h-64 overflow-y-auto">
                @forelse($recentActivity['items'] as $item)
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->title }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                {{ $item->userChecklist->name }}
                                @if($item->userChecklist->subject)
                                    • {{ $item->userChecklist->subject->name ?? $item->userChecklist->subject->fullname() }}
                                @endif
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-500">
                                {{ $item->completed_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No recent activity</p>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Deadlines -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h4 class="text-md font-medium text-gray-900 dark:text-white">Upcoming Deadlines</h4>
            </div>
            <div class="p-4 space-y-3 max-h-64 overflow-y-auto">
                @if($upcomingDeadlines['overdue']->count() > 0)
                    <div class="mb-3">
                        <h5 class="text-sm font-medium text-red-600 dark:text-red-400 mb-2">Overdue</h5>
                        @foreach($upcomingDeadlines['overdue'] as $checklist)
                            <div class="flex items-start gap-3 mb-2">
                                <div class="flex-shrink-0 w-2 h-2 bg-red-500 rounded-full mt-2"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $checklist->name }}</p>
                                    @if($checklist->subject)
                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                            {{ $checklist->subject->name ?? $checklist->subject->fullname() }}
                                        </p>
                                    @endif
                                    <p class="text-xs text-red-600 dark:text-red-400">
                                        Due {{ $checklist->due_date->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @forelse($upcomingDeadlines['upcoming'] as $checklist)
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $checklist->name }}</p>
                            @if($checklist->subject)
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    {{ $checklist->subject->name ?? $checklist->subject->fullname() }}
                                </p>
                            @endif
                            <p class="text-xs text-gray-500 dark:text-gray-500">
                                Due {{ $checklist->due_date->format('M j, Y') }}
                            </p>
                        </div>
                    </div>
                @empty
                    @if($upcomingDeadlines['overdue']->count() === 0)
                        <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">No upcoming deadlines</p>
                    @endif
                @endforelse
            </div>
        </div>
    </div>

    <!-- Subject Progress (Expandable) -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <button wire:click="toggleDetails" class="flex justify-between items-center w-full text-left">
                <h4 class="text-md font-medium text-gray-900 dark:text-white">Research by Subject</h4>
                <svg class="w-5 h-5 text-gray-400 transform transition-transform {{ $showDetails ? 'rotate-180' : '' }}" 
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>

        @if($showDetails)
            <div class="p-4 space-y-4">
                <!-- Subject Type Progress -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Person Research</h5>
                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-1">
                            <span>{{ $subjectProgress['person_progress']['completed'] }}/{{ $subjectProgress['person_progress']['total'] }} completed</span>
                            <span>{{ $subjectProgress['person_progress']['progress_percentage'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $subjectProgress['person_progress']['progress_percentage'] }}%"></div>
                        </div>
                    </div>

                    <div>
                        <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Family Research</h5>
                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-1">
                            <span>{{ $subjectProgress['family_progress']['completed'] }}/{{ $subjectProgress['family_progress']['total'] }} completed</span>
                            <span>{{ $subjectProgress['family_progress']['progress_percentage'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ $subjectProgress['family_progress']['progress_percentage'] }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Top Researched Subjects -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($subjectProgress['top_persons']->count() > 0)
                        <div>
                            <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Most Researched Persons</h5>
                            <div class="space-y-2">
                                @foreach($subjectProgress['top_persons'] as $person)
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-900 dark:text-white">{{ $person->fullname() }}</span>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs text-gray-500">{{ $person->progress_percentage }}%</span>
                                            <div class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-1">
                                                <div class="bg-blue-500 h-1 rounded-full" style="width: {{ $person->progress_percentage }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($subjectProgress['top_families']->count() > 0)
                        <div>
                            <h5 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Most Researched Families</h5>
                            <div class="space-y-2">
                                @foreach($subjectProgress['top_families'] as $family)
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-900 dark:text-white">{{ $family->name ?? 'Family #' . $family->id }}</span>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs text-gray-500">{{ $family->progress_percentage }}%</span>
                                            <div class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-1">
                                                <div class="bg-green-500 h-1 rounded-full" style="width: {{ $family->progress_percentage }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>