<div class="max-w-4xl mx-auto p-6">
    @if($currentTag)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <!-- Header -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                    Review Facial Recognition Tags
                </h2>
                <p class="text-gray-600 dark:text-gray-400">
                    Tag {{ $currentTagIndex + 1 }} of {{ $totalTags }}
                </p>
            </div>

            <!-- Progress Bar -->
            <div class="mb-6">
                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ ($currentTagIndex + 1) / $totalTags * 100 }}%"></div>
                </div>
            </div>

            <!-- Photo Display -->
            <div class="mb-6 relative">
                <img src="{{ $currentTag['photo_url'] }}" 
                     alt="Photo to review" 
                     class="w-full rounded-lg shadow-md"
                     style="max-height: 500px; object-fit: contain;">
                
                <!-- Bounding Box Overlay (if needed) -->
                @if($currentTag['bounding_box'])
                    <div class="absolute border-2 border-green-500 rounded" 
                         style="
                            left: {{ $currentTag['bounding_box']['left'] * 100 }}%;
                            top: {{ $currentTag['bounding_box']['top'] * 100 }}%;
                            width: {{ $currentTag['bounding_box']['width'] * 100 }}%;
                            height: {{ $currentTag['bounding_box']['height'] * 100 }}%;
                         ">
                    </div>
                @endif
            </div>

            <!-- Suggested Person & Confidence -->
            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                @if($currentTag['person_name'])
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Suggested person:</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $currentTag['person_name'] }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Confidence: {{ number_format($currentTag['confidence'], 1) }}%
                    </p>
                @else
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        No person match found. Please select a person from the dropdown.
                    </p>
                @endif
            </div>

            <!-- Person Selection -->
            <div class="mb-6">
                <label for="person-select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Select Person
                </label>
                <select 
                    id="person-select"
                    wire:model="selectedPersonId"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
                    <option value="">-- Select a person --</option>
                    @foreach($peopleOptions as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Create Encoding Checkbox -->
            <div class="mb-6">
                <label class="flex items-center">
                    <input 
                        type="checkbox" 
                        wire:model="createEncoding"
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    >
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                        Save face encoding for future matches
                    </span>
                </label>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 justify-between">
                <button 
                    wire:click="previousTag"
                    @if($currentTagIndex === 0) disabled @endif
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 disabled:opacity-50 disabled:cursor-not-allowed dark:bg-gray-600 dark:text-gray-200"
                >
                    ← Previous
                </button>

                <div class="flex gap-3">
                    <button 
                        wire:click="rejectTag"
                        class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700"
                    >
                        Reject
                    </button>

                    <button 
                        wire:click="skipTag"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700"
                    >
                        Skip
                    </button>

                    <button 
                        wire:click="confirmTag"
                        @if(!$selectedPersonId) disabled @endif
                        class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 disabled:opacity-50 disabled:cursor-not-allowed dark:bg-green-600 dark:hover:bg-green-700"
                    >
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900 dark:text-white">No pending tags</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                All facial recognition tags have been reviewed!
            </p>
        </div>
    @endif
</div>
