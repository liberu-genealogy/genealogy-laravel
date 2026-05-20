<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <x-slot name="heading">Profile Information</x-slot>
            <x-slot name="description">Update your account's profile information and email address.</x-slot>

            <form wire:submit.prevent="submit">
                {{ $this->form }}

                <div class="mt-4">
                    <x-filament::button type="submit">
                        Save
                    </x-filament::button>
                </div>
            </form>

            @if (session('status'))
                <div class="mt-3 text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif
        </x-filament::section>
    </div>
</x-filament-panels::page>
