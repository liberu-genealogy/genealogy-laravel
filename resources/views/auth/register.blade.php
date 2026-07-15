@extends('layouts.home')

@section('content')
    <x-auth.card
        :title="__('Start your tree')"
        :subtitle="__('Free forever. No card, and nothing to cancel.')"
    >
        <x-validation-errors class="mb-6" />

        <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-5">
            @csrf

            <x-auth.field
                name="name"
                :label="__('Your name')"
                :value="old('name')"
                autocomplete="name"
                autofocus
            />

            <x-auth.field
                name="email"
                :label="__('Email address')"
                type="email"
                :value="old('email')"
                autocomplete="username"
            />

            <x-auth.field
                name="password"
                :label="__('Password')"
                type="password"
                :hint="__('At least 8 characters.')"
                autocomplete="new-password"
            />

            <x-auth.field
                name="password_confirmation"
                :label="__('Confirm password')"
                type="password"
                autocomplete="new-password"
            />

            <button type="submit"
                    class="inline-flex min-h-11 w-full items-center justify-center rounded-md bg-registry-green px-5 py-3 text-label text-paper transition-colors duration-150 ease-out-quart hover:bg-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green">
                {{ __('Create account') }}
            </button>

            {{-- Informational, not a checkbox: Features::termsAndPrivacyPolicy() is
                 disabled in config/jetstream.php and CreateNewUser does not
                 validate acceptance, so a required-looking control would be a lie. --}}
            <p class="text-label text-ink-muted">
                {{ __('By creating an account you agree to our') }}
                <a href="{{ route('terms.and.conditions') }}"
                   class="rounded-sm text-registry-green underline underline-offset-2 transition-colors duration-150 ease-out-quart hover:text-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green">{{ __('Terms of Service') }}</a>
                {{ __('and') }}
                <a href="{{ route('privacy') }}"
                   class="rounded-sm text-registry-green underline underline-offset-2 transition-colors duration-150 ease-out-quart hover:text-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green">{{ __('Privacy Policy') }}</a>.
            </p>
        </form>

        @if (\JoelButcher\Socialstream\Socialstream::show() && ! empty(\JoelButcher\Socialstream\Socialstream::providers()))
            <x-socialstream />
        @endif

        <x-slot name="footer">
            {{ __('Already have an account?') }}
            <a href="{{ route('login') }}"
               class="rounded-sm font-semibold text-registry-green transition-colors duration-150 ease-out-quart hover:text-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green">
                {{ __('Sign in') }}
            </a>
        </x-slot>
    </x-auth.card>
@endsection
