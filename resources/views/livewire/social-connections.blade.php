<div class="space-y-6">
    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Loading Indicator --}}
    @if ($isLoading)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-5 rounded-lg shadow-xl">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 mx-auto"></div>
                <p class="mt-3 text-gray-700">Processing...</p>
            </div>
        </div>
    @endif

    {{-- Privacy Settings Section --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Privacy Settings</h2>
        
        <div class="space-y-4">
            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input wire:model="privacySettings.allow_family_discovery" 
                           type="checkbox" 
                           id="allow_family_discovery"
                           class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300">
                </div>
                <div class="ml-3">
                    <label for="allow_family_discovery" class="font-medium text-gray-900 dark:text-white">
                        Allow Family Discovery
                    </label>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Enable the system to identify potential family connections on your connected social networks.
                    </p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input wire:model="privacySettings.show_profile_to_matches" 
                           type="checkbox" 
                           id="show_profile_to_matches"
                           class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300">
                </div>
                <div class="ml-3">
                    <label for="show_profile_to_matches" class="font-medium text-gray-900 dark:text-white">
                        Show Profile to Matches
                    </label>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Allow potential family connections to see your profile information.
                    </p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input wire:model="privacySettings.share_tree_with_matches" 
                           type="checkbox" 
                           id="share_tree_with_matches"
                           class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300">
                </div>
                <div class="ml-3">
                    <label for="share_tree_with_matches" class="font-medium text-gray-900 dark:text-white">
                        Share Family Tree with Matches
                    </label>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Share your family tree data with accepted family connections.
                    </p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input wire:model="privacySettings.allow_contact_from_matches" 
                           type="checkbox" 
                           id="allow_contact_from_matches"
                           class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300">
                </div>
                <div class="ml-3">
                    <label for="allow_contact_from_matches" class="font-medium text-gray-900 dark:text-white">
                        Allow Contact from Matches
                    </label>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Allow potential family connections to contact you through the platform.
                    </p>
                </div>
            </div>

            <div class="pt-4">
                <button wire:click="updatePrivacySettings" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Save Privacy Settings
                </button>
            </div>
        </div>
    </div>

    {{-- Connected Accounts Section --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Connected Accounts</h2>
            <button wire:click="findMatches" 
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Find New Matches
            </button>
        </div>

        @if($connectedAccounts && $connectedAccounts->count() > 0)
            <div class="space-y-4">
                @foreach($connectedAccounts as $account)
                    <div class="border dark:border-gray-700 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                @if($account->avatar_path)
                                    <img src="{{ $account->avatar_path }}" alt="{{ $account->name }}" class="w-12 h-12 rounded-full">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-xl text-gray-600">{{ substr($account->provider, 0, 1) }}</span>
                                    </div>
                                @endif
                                
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ ucfirst($account->provider) }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $account->name ?? $account->email }}</p>
                                    @if($account->last_synced_at)
                                        <p class="text-xs text-gray-400">Last synced: {{ $account->last_synced_at->diffForHumans() }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center space-x-2">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           wire:click="toggleFamilyMatching({{ $account->id }})"
                                           {{ $account->enable_family_matching ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                    <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Family Matching</span>
                                </label>

                                <button wire:click="syncAccount({{ $account->id }})" 
                                        class="text-blue-600 hover:text-blue-800 text-sm">
                                    Sync
                                </button>

                                <button wire:click="disconnectAccount({{ $account->id }})" 
                                        onclick="return confirm('Are you sure you want to disconnect this account?')"
                                        class="text-red-600 hover:text-red-800 text-sm">
                                    Disconnect
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 dark:text-gray-400 text-center py-8">
                No social media accounts connected yet. Connect an account to start discovering family connections.
            </p>
        @endif
    </div>

    {{-- Pending Connections Section --}}
    @if($pendingConnections && $pendingConnections->count() > 0)
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Pending Family Connections</h2>
            
            <div class="space-y-4">
                @foreach($pendingConnections as $connection)
                    <div class="border dark:border-gray-700 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white">{{ $connection->matched_name ?? 'Unknown' }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    via {{ ucfirst($connection->connectedAccount->provider) }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                    Confidence: {{ $connection->confidence_score }}%
                                </p>
                                @if($connection->matching_criteria && isset($connection->matching_criteria['common_surnames']))
                                    <p class="text-xs text-gray-500 mt-1">
                                        Common surnames: {{ implode(', ', $connection->matching_criteria['common_surnames']) }}
                                    </p>
                                @endif
                            </div>

                            <div class="flex space-x-2">
                                <button wire:click="acceptConnection({{ $connection->id }})" 
                                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Accept
                                </button>
                                <button wire:click="rejectConnection({{ $connection->id }})" 
                                        class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Reject
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Accepted Connections Section --}}
    @if($acceptedConnections && $acceptedConnections->count() > 0)
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Accepted Family Connections</h2>
            
            <div class="space-y-4">
                @foreach($acceptedConnections as $connection)
                    <div class="border dark:border-gray-700 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white">{{ $connection->matched_name ?? 'Unknown' }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    via {{ ucfirst($connection->connectedAccount->provider) }}
                                </p>
                                @if($connection->matching_criteria && isset($connection->matching_criteria['common_surnames']))
                                    <p class="text-xs text-gray-500 mt-1">
                                        Common surnames: {{ implode(', ', $connection->matching_criteria['common_surnames']) }}
                                    </p>
                                @endif
                                <p class="text-xs text-gray-400 mt-1">
                                    Connected: {{ $connection->updated_at->diffForHumans() }}
                                </p>
                            </div>

                            <div>
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                    Connected
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
