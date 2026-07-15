{{-- Was <x-guest-layout>. The Alpine recovery toggle is preserved exactly;
     x-cloak now comes from app.css, which the old shell declared inline. --}}
@extends('layouts.home')

@section('content')
    <div x-data="{ recovery: false }">
        <x-auth.card :title="__('Two-factor authentication')">
            <p class="mb-6 text-pretty text-body text-ink-muted" x-show="! recovery">
                {{ __('Enter the authentication code from your authenticator app.') }}
            </p>

            <p class="mb-6 text-pretty text-body text-ink-muted" x-cloak x-show="recovery">
                {{ __('Enter one of your emergency recovery codes.') }}
            </p>

            <x-validation-errors class="mb-6" />

            <form method="POST" action="{{ route('two-factor.login') }}" class="flex flex-col gap-5">
                @csrf

                {{-- x-show sits on the wrapper, not the input, so the whole
                     label/field/error unit toggles together. --}}
                <div x-show="! recovery">
                    <x-auth.field
                        name="code"
                        :label="__('Authentication code')"
                        inputmode="numeric"
                        autocomplete="one-time-code"
                        :required="false"
                        autofocus
                        x-ref="code"
                    />
                </div>

                <div x-cloak x-show="recovery">
                    <x-auth.field
                        name="recovery_code"
                        :label="__('Recovery code')"
                        autocomplete="one-time-code"
                        :required="false"
                        x-ref="recovery_code"
                    />
                </div>

                <button type="submit"
                        class="inline-flex min-h-11 w-full items-center justify-center rounded-md bg-registry-green px-5 py-3 text-label text-paper transition-colors duration-150 ease-out-quart hover:bg-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green">
                    {{ __('Sign in') }}
                </button>

                <button type="button"
                        class="inline-flex min-h-11 w-fit items-center rounded-sm text-label text-registry-green underline underline-offset-2 transition-colors duration-150 ease-out-quart hover:text-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green"
                        x-show="! recovery"
                        x-on:click="recovery = true; $nextTick(() => { $refs.recovery_code.focus() })">
                    {{ __('Use a recovery code instead') }}
                </button>

                <button type="button"
                        class="inline-flex min-h-11 w-fit items-center rounded-sm text-label text-registry-green underline underline-offset-2 transition-colors duration-150 ease-out-quart hover:text-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green"
                        x-cloak
                        x-show="recovery"
                        x-on:click="recovery = false; $nextTick(() => { $refs.code.focus() })">
                    {{ __('Use an authentication code instead') }}
                </button>
            </form>
        </x-auth.card>
    </div>
@endsection
