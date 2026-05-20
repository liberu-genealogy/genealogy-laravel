<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <x-slot name="heading">Update Password</x-slot>
            <x-slot name="description">Ensure your account is using a strong password.</x-slot>

            <form wire:submit.prevent="submit">
                {{ $this->form }}

                <div class="mt-4">
                    <x-filament::button type="submit">
                        Update Password
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
