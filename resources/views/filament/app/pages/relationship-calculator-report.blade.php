<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">How are they related?</x-slot>
        <x-slot name="description">Pick two people to see how the first is related to the second.</x-slot>

        <form wire:submit="calculate">
            {{ $this->form }}

            <div class="mt-6">
                <x-filament::button type="submit">
                    Calculate
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>

    @if ($this->relationship !== null)
        <x-filament::section class="mt-6">
            <x-slot name="heading">Result</x-slot>
            <p class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                {{ ucfirst($this->relationship) }}
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                (first person's relationship to the second)
            </p>
        </x-filament::section>
    @endif
</x-filament-panels::page>
