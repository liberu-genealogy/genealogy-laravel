<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header with User Stats -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg shadow-lg p-6 mb-8 text-white">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-3xl font-bold mb-2">Your Genealogy Journey</h1>
                <p class="text-blue-100">Track your progress and achievements</p>
            </div>
            <div class="mt-4 md:mt-0 flex flex-col md:flex-row gap-4">
                <!-- Level Badge -->
                <div class="bg-white/20 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold">Level {{ $user->level }}</div>
                    <div class="text-sm">{{ number_format($user->total_points) }} points</div>
                    <div class="w-full bg-white/20 rounded-full h-2 mt-2">
                        <div class="bg-white h-2 rounded-full" style="width: {{ $userStats['level_info']['progress_percentage'] }}%"></div>
                    </div>
                </div>

                <!-- Rank Badge -->
                <div class="bg-white/20 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold">#{{ $userStats['leaderboard_rank'] }}</div>
                    <div class="text-sm">Leaderboard Rank</div>
                </div>

                <!-- Achievements Badge -->
                <div class="bg-white/20 rounded-lg p-4 text-center">
                    <div class="text-2xl font-bold">{{ $userStats['achievements_count'] }}</div>
                    <div class="text-sm">Achievements</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8">
            <button wire:click="setActiveTab('overview')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'overview' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Overview
            </button>
            <button wire:click="setActiveTab('achievements')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'achievements' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Achievements
            </button>
            <button wire:click="setActiveTab('progress')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'progress' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Progress
            </button>
            <button wire:click="setActiveTab('leaderboard')" 
                    class="py-2 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'leaderboard' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Leaderboard
            </button>
        </nav>
    </div>

    <!-- Tab Content -->
    @if($activeTab === 'overview')
        <!-- Overview Tab -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Activity -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>

                    @if($recentPoints->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentPoints->take(5) as $point)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <span class="text-2xl mr-3">{{ $point->getActivityIcon() }}</span>
                                        <div>
                                            <p class="font-medium">{{ $point->getFormattedDescription() }}</p>
                                            <p class="text-sm text-gray-500">{{ $point->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <span class="text-green-600 font-semibold">+{{ $point->points }} pts</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No recent activity. Start researching to earn points!</p>
                    @endif
                </div>

                <!-- Recent Achievements -->
                @if($recentAchievements->count() > 0)
                    <div class="bg-white rounded-lg shadow p-6 mt-6">
                        <h3 class="text-lg font-semibold mb-4">Recent Achievements</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach($recentAchievements as $userAchievement)
                                <div class="achievement-card bg-gradient-to-br {{ $userAchievement->achievement->getBadgeColorClass() }} rounded-lg p-4 text-white">
                                    <div class="text-3xl mb-2">{{ $userAchievement->achievement->icon }}</div>
                                    <h4 class="font-bold">{{ $userAchievement->achievement->name }}</h4>
                                    <p class="text-sm opacity-90">{{ $userAchievement->achievement->description }}</p>
                                    <p class="text-xs mt-2">{{ $userAchievement->unlocked_at->diffForHumans() }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Daily Progress -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Today's Progress</h3>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ $userStats['daily_points'] }}</div>
                        <p class="text-gray-500">Points earned today</p>
                    </div>
                    @if($userStats['activity_streak'] > 0)
                        <div class="mt-4 text-center">
                            <div class="text-2xl">🔥</div>
                            <p class="text-sm text-gray-600">{{ $userStats['activity_streak'] }} day streak</p>
                        </div>
                    @endif
                </div>

                <!-- Top Researchers -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Top Researchers</h3>
                    <div class="space-y-3">
                        @foreach($topLeaderboard as $entry)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <span class="w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center text-sm font-medium mr-3">
                                        {{ $entry['rank'] }}
                                    </span>
                                    <span class="font-medium {{ $entry['user']->id === $user->id ? 'text-blue-600' : '' }}">
                                        {{ $entry['user']->name }}
                                    </span>
                                </div>
                                <span class="text-sm text-gray-500">{{ number_format($entry['points']) }} pts</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    @elseif($activeTab === 'achievements')
        <!-- Achievements Tab -->
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div class="flex flex-wrap gap-2">
                @foreach($this->getAchievementCategories() as $key => $label)
                    <button wire:click="setAchievementCategory('{{ $key }}')" 
                            class="px-3 py-1 rounded-full text-sm {{ $achievementCategory === $key ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
            <button wire:click="toggleShowOnlyUnlocked" 
                    class="px-4 py-2 rounded-lg text-sm {{ $showOnlyUnlocked ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                {{ $showOnlyUnlocked ? 'Show All' : 'Show Unlocked Only' }}
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($achievements as $item)
                <div class="achievement-card bg-white rounded-lg shadow-lg overflow-hidden {{ $item['unlocked'] ? '' : 'opacity-75' }}">
                    <div class="p-6">
                        <div class="text-center">
                            <div class="text-4xl mb-3">{{ $item['achievement']->icon }}</div>
                            <h3 class="font-bold text-lg mb-2">{{ $item['achievement']->name }}</h3>
                            <p class="text-gray-600 text-sm mb-4">{{ $item['achievement']->description }}</p>

                            @if($item['unlocked'])
                                <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                                    ✅ Unlocked {{ $item['unlocked_at']->diffForHumans() }}
                                </div>
                            @elseif($item['progress'])
                                <div class="mb-2">
                                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                                        <span>Progress</span>
                                        <span>{{ $item['progress']->getFormattedProgress() }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $item['progress_percentage'] }}%"></div>
                                    </div>
                                </div>
                            @endif

                            <div class="mt-4 text-blue-600 font-semibold">
                                {{ $item['achievement']->points }} points
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    @elseif($activeTab === 'progress')
        <!-- Progress Tab -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-6">Achievement Progress</h3>

                @if($progress->count() > 0)
                    <div class="space-y-6">
                        @foreach($progress as $progressItem)
                            <div class="border rounded-lg p-4">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center">
                                        <span class="text-2xl mr-3">{{ $progressItem->achievement->icon }}</span>
                                        <div>
                                            <h4 class="font-semibold">{{ $progressItem->achievement->name }}</h4>
                                            <p class="text-gray-600 text-sm">{{ $progressItem->achievement->description }}</p>
                                        </div>
                                    </div>
                                    <span class="text-blue-600 font-semibold">{{ $progressItem->achievement->points }} pts</span>
                                </div>

                                <div class="mb-2">
                                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                                        <span>Progress</span>
                                        <span>{{ $progressItem->getFormattedProgress() }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                        <div class="bg-blue-500 h-3 rounded-full transition-all duration-300" 
                                             style="width: {{ $progressItem->getProgressPercentage() }}%"></div>
                                    </div>
                                </div>

                                <p class="text-sm text-gray-500">
                                    {{ $progressItem->getRemainingProgress() }} more needed to unlock
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-6xl mb-4">🎯</div>
                        <h3 class="text-lg font-semibold mb-2">All caught up!</h3>
                        <p class="text-gray-600">You've unlocked all available achievements or haven't started any yet.</p>
                    </div>
                @endif
            </div>
        </div>

    @elseif($activeTab === 'leaderboard')
        <!-- Leaderboard Tab -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                    <h3 class="text-lg font-semibold">Leaderboard</h3>
                    <div class="flex flex-col sm:flex-row gap-4 mt-4 sm:mt-0">
                        <div class="flex gap-2">
                            @foreach($this->getLeaderboardPeriods() as $key => $label)
                                <button wire:click="setLeaderboardPeriod('{{ $key }}')" 
                                        class="px-3 py-1 rounded-full text-sm {{ $leaderboardPeriod === $key ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                        <button wire:click="toggleLeaderboardVisibility" 
                                class="px-4 py-2 rounded-lg text-sm {{ $user->show_on_leaderboard ? 'bg-green-500 text-white' : 'bg-gray-500 text-white' }}">
                            {{ $user->show_on_leaderboard ? 'Visible' : 'Hidden' }}
                        </button>
                    </div>
                </div>

                <div class="space-y-2">
                    @foreach($leaderboard as $entry)
                        <div class="flex items-center justify-between p-4 {{ $entry['user']->id === $user->id ? 'bg-blue-50 border border-blue-200' : 'bg-gray-50' }} rounded-lg">
                            <div class="flex items-center">
                                <div class="w-8 h-8 flex items-center justify-center rounded-full {{ $entry['rank'] <= 3 ? 'bg-gradient-to-r from-yellow-400 to-yellow-600 text-white' : 'bg-gray-200' }} font-bold mr-4">
                                    @if($entry['rank'] === 1) 🥇
                                    @elseif($entry['rank'] === 2) 🥈
                                    @elseif($entry['rank'] === 3) 🥉
                                    @else {{ $entry['rank'] }}
                                    @endif
                                </div>
                                <div>
                                    <p class="font-semibold {{ $entry['user']->id === $user->id ? 'text-blue-600' : '' }}">
                                        {{ $entry['user']->name }}
                                        @if($entry['user']->id === $user->id) (You) @endif
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Level {{ $entry['level'] }} • {{ $entry['achievements_count'] }} achievements
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-lg">{{ number_format($entry['points']) }}</p>
                                <p class="text-sm text-gray-500">points</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Listen for achievement unlocked events
    window.addEventListener('achievement-unlocked', event => {
        // Show notification
        if (typeof window.showNotification === 'function') {
            window.showNotification(event.detail.message, 'success');
        } else {
            alert(event.detail.message);
        }
    });

    // Listen for level up events
    window.addEventListener('user-leveled-up', event => {
        // Show notification
        if (typeof window.showNotification === 'function') {
            window.showNotification(event.detail.message, 'success');
        } else {
            alert(event.detail.message);
        }
    });
</script>
@endpush