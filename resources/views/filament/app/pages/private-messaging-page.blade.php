<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <x-slot name="heading">Private Messaging</x-slot>
            <x-slot name="description">Send and receive messages with other users.</x-slot>

            {{-- User Selection --}}
            <div class="mb-4">
                <label for="selectedUser" class="block text-sm font-medium mb-1">Select User</label>
                <select wire:model.live="selectedUserId" id="selectedUser" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800">
                    <option value="">-- Select a user --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Messages --}}
            @if($selectedUserId)
                <div class="border dark:border-gray-700 rounded-lg p-4 max-h-96 overflow-y-auto mb-4 space-y-3">
                    @forelse($messages as $msg)
                        <div class="{{ $msg->user_id === auth()->id() ? 'text-right' : 'text-left' }}">
                            <div class="inline-block px-3 py-2 rounded-lg {{ $msg->user_id === auth()->id() ? 'bg-primary-100 dark:bg-primary-900' : 'bg-gray-100 dark:bg-gray-700' }}">
                                <p class="text-sm">{{ $msg->message }}</p>
                                <span class="text-xs text-gray-500">{{ $msg->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 text-center">No messages yet. Start a conversation!</p>
                    @endforelse
                </div>

                {{-- Send Message --}}
                <form wire:submit.prevent="sendMessage" class="flex gap-2">
                    <input type="text" wire:model="messageText" placeholder="Type a message..." class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800">
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">Send</button>
                </form>
            @else
                <p class="text-sm text-gray-500">Select a user to start messaging.</p>
            @endif
        </x-filament::section>
    </div>
</x-filament-panels::page>
