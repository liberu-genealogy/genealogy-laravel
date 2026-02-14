<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Document Transcription</h1>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            Upload handwritten historical documents and let AI help you transcribe them.
        </p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Transcriptions</div>
            <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['total_transcriptions'] ?? 0 }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed</div>
            <div class="mt-1 text-2xl font-semibold text-green-600">{{ $stats['completed_transcriptions'] ?? 0 }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Corrections</div>
            <div class="mt-1 text-2xl font-semibold text-blue-600">{{ $stats['total_corrections'] ?? 0 }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Avg. Confidence</div>
            <div class="mt-1 text-2xl font-semibold text-purple-600">{{ number_format($stats['avg_confidence'] ?? 0, 1) }}%</div>
        </div>
    </div>

    <!-- Messages -->
    @if($successMessage)
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ $successMessage }}</span>
            <button wire:click="$set('successMessage', null)" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <span class="text-2xl">&times;</span>
            </button>
        </div>
    @endif

    @if($errorMessage)
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ $errorMessage }}</span>
            <button wire:click="$set('errorMessage', null)" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <span class="text-2xl">&times;</span>
            </button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Sidebar - Upload & List -->
        <div class="lg:col-span-1">
            <!-- Upload Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Upload Document</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Choose an image file
                        </label>
                        <input 
                            type="file" 
                            wire:model="document" 
                            accept="image/*"
                            class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100
                                dark:file:bg-blue-900 dark:file:text-blue-200"
                        />
                        @error('document') 
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    @if ($document)
                        <div class="mt-2">
                            <img src="{{ $document->temporaryUrl() }}" alt="Preview" class="max-w-full h-auto rounded border">
                        </div>
                    @endif

                    <button 
                        wire:click="uploadDocument" 
                        wire:loading.attr="disabled"
                        wire:target="uploadDocument"
                        @if(!$document) disabled @endif
                        class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white font-bold py-2 px-4 rounded transition duration-150"
                    >
                        <span wire:loading.remove wire:target="uploadDocument">Upload & Transcribe</span>
                        <span wire:loading wire:target="uploadDocument">Processing...</span>
                    </button>
                </div>
            </div>

            <!-- Transcription List -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Recent Transcriptions</h2>
                
                <div class="space-y-2 max-h-96 overflow-y-auto">
                    @forelse($transcriptions as $transcription)
                        <div 
                            wire:click="selectTranscription({{ $transcription['id'] }})"
                            class="p-3 border rounded cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition
                                {{ $currentTranscription && $currentTranscription->id === $transcription['id'] ? 'bg-blue-50 dark:bg-blue-900 border-blue-500' : 'border-gray-200 dark:border-gray-600' }}"
                        >
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        {{ $transcription['original_filename'] }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($transcription['created_at'])->diffForHumans() }}
                                    </p>
                                </div>
                                <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded
                                    @if($transcription['status'] === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($transcription['status'] === 'processing') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @elseif($transcription['status'] === 'failed') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                    @endif">
                                    {{ ucfirst($transcription['status']) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 py-4">No transcriptions yet</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Main Content - Viewer & Editor -->
        <div class="lg:col-span-2">
            @if($currentTranscription)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                                {{ $currentTranscription->original_filename }}
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Uploaded {{ $currentTranscription->created_at->diffForHumans() }}
                                @if($currentTranscription->hasCorrections())
                                    <span class="text-blue-600 dark:text-blue-400">• Corrected</span>
                                @endif
                            </p>
                        </div>
                        <div class="flex space-x-2">
                            @if(!$isEditing)
                                <button 
                                    wire:click="startEditing"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium transition"
                                >
                                    Edit
                                </button>
                            @endif
                            <button 
                                wire:click="deleteTranscription({{ $currentTranscription->id }})"
                                wire:confirm="Are you sure you want to delete this transcription?"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm font-medium transition"
                            >
                                Delete
                            </button>
                        </div>
                    </div>

                    <!-- Image and Text Side by Side -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Document Image -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Original Document</h3>
                            <div class="border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden">
                                <img 
                                    src="{{ asset('storage/' . $currentTranscription->document_path) }}" 
                                    alt="Document"
                                    class="w-full h-auto"
                                />
                            </div>
                            @if($currentTranscription->getConfidenceScore())
                                <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    Confidence: {{ number_format($currentTranscription->getConfidenceScore() * 100, 1) }}%
                                </div>
                            @endif
                        </div>

                        <!-- Transcription Text -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Transcription</h3>
                            
                            @if($isEditing)
                                <div class="space-y-4">
                                    <textarea 
                                        wire:model="transcriptionText"
                                        rows="15"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg 
                                            focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                            bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                    ></textarea>
                                    
                                    <div class="flex space-x-2">
                                        <button 
                                            wire:click="saveCorrection"
                                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-medium transition"
                                        >
                                            Save Correction
                                        </button>
                                        <button 
                                            wire:click="cancelEditing"
                                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm font-medium transition"
                                        >
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 bg-gray-50 dark:bg-gray-900 min-h-[300px]">
                                    <div class="whitespace-pre-wrap text-gray-900 dark:text-white">{{ $transcriptionText }}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Corrections History -->
                    @if($currentTranscription->corrections->count() > 0)
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Correction History</h3>
                            <div class="space-y-2">
                                @foreach($currentTranscription->corrections as $correction)
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        <span class="font-medium">{{ $correction->user->name }}</span> 
                                        made corrections {{ $correction->created_at->diffForHumans() }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No transcription selected</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Upload a document or select one from the list to get started.</p>
                </div>
            @endif
        </div>
    </div>
</div>
