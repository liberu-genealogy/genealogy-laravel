<x-filament-panels::page>
    <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <table class="w-full text-sm">
            <thead class="border-b border-gray-200 dark:border-white/10">
                <tr class="text-left text-gray-500 dark:text-gray-400">
                    <th class="px-4 py-3 font-medium">{{ __('Name') }}</th>
                    <th class="px-4 py-3 font-medium">{{ __('Email') }}</th>
                    <th class="px-4 py-3 font-medium">{{ __('Tier') }}</th>
                    <th class="px-4 py-3"><span class="sr-only">{{ __('Actions') }}</span></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                @foreach ($members as $member)
                    <tr>
                        <td class="px-4 py-3 text-gray-950 dark:text-white">{{ $member['name'] }}</td>
                        <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $member['email'] }}</td>
                        <td class="px-4 py-3 text-gray-950 dark:text-white">{{ \Illuminate\Support\Str::headline($member['tier']) }}</td>
                        <td class="px-4 py-3 text-right">
                            @unless ($member['is_owner'])
                                {{ ($this->setTierAction)(['user' => $member['id']]) }}
                            @endunless
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-filament-panels::page>
