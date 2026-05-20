<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <x-slot name="heading">People Dashboard</x-slot>
            <x-slot name="description">Overview of people in your family tree.</x-slot>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-filament::section>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-primary-600">{{ \App\Models\Person::count() }}</p>
                        <p class="text-sm text-gray-500">Total People</p>
                    </div>
                </x-filament::section>

                <x-filament::section>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-primary-600">{{ \App\Models\Family::count() }}</p>
                        <p class="text-sm text-gray-500">Families</p>
                    </div>
                </x-filament::section>

                <x-filament::section>
                    <div class="text-center">
                        <p class="text-3xl font-bold text-primary-600">{{ \App\Models\PersonEvent::count() }}</p>
                        <p class="text-sm text-gray-500">Events</p>
                    </div>
                </x-filament::section>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
