<div>
    {{-- RSVP Status --}}
    <div class="mb-4">
        @if($attendee)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $this->rsvpStatusColor }}-100 text-{{ $this->rsvpStatusColor }}-800">
                {{ $this->rsvpStatusText }}
            </span>
        @endif
    </div>

    {{-- Event Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-3 text-center shadow">
            <div class="text-2xl font-bold">{{ $eventStats['total_attendees'] ?? 0 }}</div>
            <div class="text-sm text-gray-500">Total</div>
        </div>
        <div class="bg-green-50 dark:bg-green-900 rounded-lg p-3 text-center shadow">
            <div class="text-2xl font-bold text-green-600">{{ $eventStats['accepted'] ?? 0 }}</div>
            <div class="text-sm text-gray-500">Accepted</div>
        </div>
        <div class="bg-yellow-50 dark:bg-yellow-900 rounded-lg p-3 text-center shadow">
            <div class="text-2xl font-bold text-yellow-600">{{ $eventStats['pending'] ?? 0 }}</div>
            <div class="text-sm text-gray-500">Pending</div>
        </div>
        <div class="bg-red-50 dark:bg-red-900 rounded-lg p-3 text-center shadow">
            <div class="text-2xl font-bold text-red-600">{{ $eventStats['declined'] ?? 0 }}</div>
            <div class="text-sm text-gray-500">Declined</div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex gap-3 mb-6">
        @if($userCanRsvp)
            <button wire:click="openRsvpModal" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                {{ $attendee ? 'Update RSVP' : 'RSVP Now' }}
            </button>
        @endif

        @if($canJoin)
            <button wire:click="joinEvent" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                Join Event
            </button>
        @endif

        @if($this->canInvite)
            <button wire:click="openInviteModal" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Invite Others
            </button>
        @endif

        <button wire:click="toggleAttendeeList" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300">
            {{ $showAttendeeList ? 'Hide Attendees' : 'Show Attendees' }}
        </button>
    </div>

    {{-- Attendee List --}}
    @if($showAttendeeList)
        <div class="mb-4 flex gap-3">
            <input type="text" wire:model.live="search" placeholder="Search attendees..." class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800">
            <select wire:model.live="statusFilter" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800">
                <option value="all">All Statuses</option>
                <option value="accepted">Accepted</option>
                <option value="pending">Pending</option>
                <option value="declined">Declined</option>
                <option value="maybe">Maybe</option>
            </select>
        </div>

        @if($attendees->count() > 0)
            <div class="space-y-2">
                @foreach($attendees as $att)
                    <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg shadow">
                        <div>
                            <span class="font-medium">{{ $att->user?->name ?? $att->person?->fullname() ?? $att->guest_name ?? 'Unknown' }}</span>
                            <span class="text-sm text-gray-500 ml-2">{{ $att->rsvp_status }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">{{ $attendees->links() }}</div>
        @else
            <p class="text-gray-500">No attendees found.</p>
        @endif
    @endif

    {{-- RSVP Modal --}}
    @if($showRsvpModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md">
                <h3 class="text-lg font-semibold mb-4">RSVP</h3>
                <form wire:submit.prevent="submitRsvp">
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Status</label>
                        <select wire:model="rsvp_status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                            <option value="accepted">Accept</option>
                            <option value="declined">Decline</option>
                            <option value="maybe">Maybe</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Notes</label>
                        <textarea wire:model="rsvp_notes" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700" rows="3"></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" wire:click="$set('showRsvpModal', false)" class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Invite Modal --}}
    @if($showInviteModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md">
                <h3 class="text-lg font-semibold mb-4">Invite Others</h3>

                {{-- Invite by Person --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Invite a Person</label>
                    <select wire:model="invite_person_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                        <option value="">Select person...</option>
                        @foreach($persons as $person)
                            <option value="{{ $person->id }}">{{ $person->fullname() }}</option>
                        @endforeach
                    </select>
                    <button type="button" wire:click="invitePerson" class="mt-2 px-3 py-1 bg-blue-600 text-white rounded text-sm">Invite Person</button>
                </div>

                <hr class="my-4">

                {{-- Invite by Email --}}
                <form wire:submit.prevent="sendInvitations">
                    @foreach($invite_emails as $index => $email)
                        <div class="flex gap-2 mb-2">
                            <input type="email" wire:model="invite_emails.{{ $index }}" placeholder="Email address" class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                            <button type="button" wire:click="removeInviteEmail({{ $index }})" class="text-red-500">&times;</button>
                        </div>
                    @endforeach
                    <button type="button" wire:click="addInviteEmail" class="text-sm text-blue-600 mb-4">+ Add another email</button>

                    <div class="flex justify-end gap-3">
                        <button type="button" wire:click="$set('showInviteModal', false)" class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Send Invitations</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Flash Messages --}}
    @if(session()->has('success'))
        <div class="mt-4 p-3 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
    @endif
    @if(session()->has('error'))
        <div class="mt-4 p-3 bg-red-100 text-red-700 rounded-lg">{{ session('error') }}</div>
    @endif
    @if(session()->has('warning'))
        <div class="mt-4 p-3 bg-yellow-100 text-yellow-700 rounded-lg">{{ session('warning') }}</div>
    @endif
</div>
