<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">My Research Checklists</h2>
            <p class="text-gray-600 dark:text-gray-400">Track your genealogical research progress</p>
        </div>
        <button wire:click="$set('showCreateModal', true)" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            New Checklist
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <input wire:model.live="search" type="text" placeholder="Search checklists..." 
                       class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
            </div>
            <div>
                <select wire:model.live="statusFilter" 
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    <option value="all">All Statuses</option>
                    <option value="not_started">Not Started</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="on_hold">On Hold</option>
                </select>
            </div>
            <div>
                <select wire:model.live="priorityFilter" 
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    <option value="all">All Priorities</option>
                    <option value="urgent">Urgent</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
            </div>
            <div>
                <select wire:model.live="subjectFilter" 
                        class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                    <option value="all">All Subjects</option>
                    <option value="App\Models\Person">Persons</option>
                    <option value="App\Models\Family">Families</option>
                </select>
            </div>
            <div>
                <button wire:click="resetFilters" 
                        class="w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                    Reset Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Checklists Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($checklists as $checklist)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                <!-- Checklist Header -->
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-semibold text-lg text-gray-900 dark:text-white">{{ $checklist->name }}</h3>
                            @if($checklist->description)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ Str::limit($checklist->description, 100) }}</p>
                            @endif
                        </div>
                        <div class="flex gap-2 ml-2">
                            <button wire:click="editChecklist({{ $checklist->id }})" 
                                    class="text-blue-600 hover:text-blue-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button wire:click="deleteChecklist({{ $checklist->id }})" 
                                    wire:confirm="Are you sure you want to delete this checklist?"
                                    class="text-red-600 hover:text-red-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Status and Priority Badges -->
                    <div class="flex gap-2 mt-3">
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($checklist->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($checklist->status === 'in_progress') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                            @elseif($checklist->status === 'on_hold') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 @endif">
                            {{ ucfirst(str_replace('_', ' ', $checklist->status)) }}
                        </span>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($checklist->priority === 'urgent') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @elseif($checklist->priority === 'high') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                            @elseif($checklist->priority === 'medium') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @endif">
                            {{ ucfirst($checklist->priority) }}
                        </span>
                        @if($checklist->is_overdue)
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                Overdue
                            </span>
                        @endif
                    </div>

                    <!-- Progress Bar -->
                    <div class="mt-3">
                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-1">
                            <span>Progress</span>
                            <span>{{ $checklist->completion_percentage }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                                 style="width: {{ $checklist->completion_percentage }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Checklist Items -->
                <div class="p-4 max-h-64 overflow-y-auto">
                    @forelse($checklist->items as $item)
                        <div class="flex items-start gap-3 py-2 border-b border-gray-100 dark:border-gray-700 last:border-b-0">
                            <input type="checkbox" 
                                   wire:click="toggleItemCompletion({{ $item->id }})"
                                   @if($item->is_completed) checked @endif
                                   class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 dark:text-white 
                                   @if($item->is_completed) line-through text-gray-500 @endif">
                                    {{ $item->title }}
                                </p>
                                @if($item->description)
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                        {{ Str::limit($item->description, 80) }}
                                    </p>
                                @endif
                                @if($item->estimated_time)
                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                        Est. {{ $item->estimated_time }} min
                                    </p>
                                @endif
                            </div>
                            <button wire:click="editItem({{ $item->id }})" 
                                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">No items yet</p>
                    @endforelse
                </div>

                <!-- Checklist Footer -->
                <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 rounded-b-lg">
                    <div class="flex justify-between items-center text-sm text-gray-600 dark:text-gray-400">
                        <span>{{ $checklist->items->count() }} items</span>
                        @if($checklist->due_date)
                            <span>Due: {{ $checklist->due_date->format('M j, Y') }}</span>
                        @endif
                    </div>
                    <button wire:click="addCustomItem({{ $checklist->id }})" 
                            class="w-full mt-2 text-blue-600 hover:text-blue-800 text-sm font-medium">
                        + Add Custom Item
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No checklists</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new research checklist.</p>
                <div class="mt-6">
                    <button wire:click="$set('showCreateModal', true)" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Create Checklist
                    </button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $checklists->links() }}
    </div>

    <!-- Create/Edit Checklist Modal -->
    @if($showCreateModal || $showEditModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            {{ $showCreateModal ? 'Create New Checklist' : 'Edit Checklist' }}
                        </h3>
                        <button wire:click="$set('showCreateModal', false); $set('showEditModal', false); resetForm()" 
                                class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    @if($showCreateModal && !$selectedTemplate)
                        <!-- Template Selection -->
                        <div class="mb-6">
                            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-3">Choose a Template (Optional)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-48 overflow-y-auto">
                                @foreach($templates as $template)
                                    <button wire:click="createFromTemplate({{ $template->id }})" 
                                            class="text-left p-3 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $template->name }}</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ $template->templateItems->count() }} items</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-500">{{ ucfirst($template->category) }}</div>
                                    </button>
                                @endforeach
                            </div>
                            <div class="mt-3 text-center">
                                <button wire:click="$set('selectedTemplate', 'custom')" 
                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    Create Custom Checklist
                                </button>
                            </div>
                        </div>
                    @endif

                    @if($showEditModal || ($showCreateModal && $selectedTemplate))
                        <!-- Checklist Form -->
                        <form wire:submit.prevent="{{ $showCreateModal ? 'createChecklist' : 'updateChecklist' }}">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                    <input wire:model="name" type="text" required 
                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                    <textarea wire:model="description" rows="3" 
                                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700"></textarea>
                                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subject Type</label>
                                        <select wire:model="subject_type" 
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                            <option value="">None</option>
                                            <option value="App\Models\Person">Person</option>
                                            <option value="App\Models\Family">Family</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subject</label>
                                        <select wire:model="subject_id" 
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                            <option value="">Select Subject</option>
                                            @if($subject_type === 'App\Models\Person')
                                                @foreach($persons as $person)
                                                    <option value="{{ $person->id }}">{{ $person->name }}</option>
                                                @endforeach
                                            @elseif($subject_type === 'App\Models\Family')
                                                @foreach($families as $family)
                                                    <option value="{{ $family->id }}">{{ $family->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Priority</label>
                                        <select wire:model="priority" 
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                            <option value="low">Low</option>
                                            <option value="medium">Medium</option>
                                            <option value="high">High</option>
                                            <option value="urgent">Urgent</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Due Date</label>
                                        <input wire:model="due_date" type="date" 
                                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                                    <textarea wire:model="notes" rows="3" 
                                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700"></textarea>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end gap-3">
                                <button type="button" 
                                        wire:click="$set('showCreateModal', false); $set('showEditModal', false); resetForm()" 
                                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    Cancel
                                </button>
                                <button type="submit" 
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                                    {{ $showCreateModal ? 'Create Checklist' : 'Update Checklist' }}
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Item Modal -->
    @if($showItemModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-lg w-full mx-4">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Edit Item</h3>
                        <button wire:click="$set('showItemModal', false); resetItemForm()" 
                                class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="updateItem">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                                <input wire:model="item_title" type="text" required 
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                @error('item_title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                <textarea wire:model="item_description" rows="3" 
                                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700"></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estimated Time (min)</label>
                                    <input wire:model="item_estimated_time" type="number" min="1" 
                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Actual Time (min)</label>
                                    <input wire:model="item_actual_time" type="number" min="1" 
                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                                <textarea wire:model="item_notes" rows="3" 
                                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700"></textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" 
                                    wire:click="$set('showItemModal', false); resetItemForm()" 
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                                Update Item
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('message') }}
        </div>
    @endif
</div>