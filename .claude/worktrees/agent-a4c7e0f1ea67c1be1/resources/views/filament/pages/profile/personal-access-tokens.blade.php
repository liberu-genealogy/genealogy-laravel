<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <x-slot name="heading">Personal Access Tokens</x-slot>
            <x-slot name="description">Manage your API tokens for third-party access.</x-slot>

            @if($user->tokens->count())
                <div class="space-y-3">
                    @foreach($user->tokens as $token)
                        <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg border">
                            <div>
                                <p class="font-medium">{{ $token->name }}</p>
                                <p class="text-sm text-gray-500">Created {{ $token->created_at->diffForHumans() }}</p>
                            </div>
                            <x-filament::button color="danger" size="sm" wire:click="deleteApiToken('{{ $token->name }}')">
                                Delete
                            </x-filament::button>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">No tokens created yet.</p>
            @endif

            <div class="mt-4">
                <form wire:submit.prevent="createApiToken($refs.tokenName.value)">
                    <div class="flex gap-3">
                        <x-filament::input.wrapper class="flex-1">
                            <input x-ref="tokenName" type="text" placeholder="Token name" class="fi-input block w-full border-none bg-transparent px-3 py-1.5 text-base text-gray-950 outline-none transition duration-75 placeholder:text-gray-400 focus:ring-0 dark:text-white dark:placeholder:text-gray-500 sm:text-sm sm:leading-6" required />
                        </x-filament::input.wrapper>
                        <x-filament::button type="submit">Create Token</x-filament::button>
                    </div>
                </form>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
