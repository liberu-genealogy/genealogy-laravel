<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold">{{ $space->name }}</h2>
        <div class="text-sm text-gray-600">Collaborators: {{ $space->collaborators()->count() }}</div>
    </div>

    <div>
        <textarea wire:model.defer="content" rows="10" class="w-full rounded border-gray-300 p-2"></textarea>
    </div>

    <div class="flex gap-2">
        <button wire:click="saveContent(content)" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
        <button wire:click="$refresh" class="bg-gray-200 px-4 py-2 rounded">Refresh</button>
    </div>

    <script>
        // Example of initiating Echo presence/listening from the front-end if Echo is set up.
        // window.Echo.private(`research-space.{{ $space->id }}`)
        //     .listen('ResearchSpaceUpdated', (e) => {
        //         Livewire.emit(`echo:research-space.{{ $space->id }},ResearchSpaceUpdated`, e);
        //     });
    </script>
</div>
