<x-filament::page>
    <x-filament::form wire:submit.prevent="submit">
        {{ $this->form }}

        <x-filament::form.actions>
            <x-filament::button type="submit">
                Save Changes
            </x-filament::button>
        </x-filament::form.actions>
    </x-filament::form>
</x-filament::page>
