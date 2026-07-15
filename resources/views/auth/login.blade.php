@extends('layouts.home')

@section('content')
    <x-auth.card
        :title="__('Welcome back')"
        :subtitle="__('Sign in to pick up where your research left off.')"
    >
        @session('status')
            <p role="status" class="mb-6 rounded-md border border-registry-green bg-registry-tint px-4 py-3 text-body text-registry-green-deep">
                {{ $value }}
            </p>
        @endsession

        <x-validation-errors class="mb-6" />

        <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-5">
            @csrf

            <x-auth.field
                name="email"
                :label="__('Email address')"
                type="email"
                :value="old('email')"
                autocomplete="username"
                autofocus
            />

            <x-auth.field
                name="password"
                :label="__('Password')"
                type="password"
                autocomplete="current-password"
            >
                <x-slot name="action">
                    <a href="{{ route('password.request') }}"
                       class="rounded-sm text-registry-green transition-colors duration-150 ease-out-quart hover:text-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green">
                        {{ __('Forgot password?') }}
                    </a>
                </x-slot>
            </x-auth.field>

            <label for="remember_me" class="flex w-fit items-center gap-2.5">
                <input type="checkbox" id="remember_me" name="remember"
                       class="size-4 rounded-sm border border-ink-faint text-registry-green focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green">
                <span class="text-body text-ink-muted">{{ __('Remember me') }}</span>
            </label>

            <button type="submit"
                    class="inline-flex min-h-11 w-full items-center justify-center rounded-md bg-registry-green px-5 py-3 text-label text-paper transition-colors duration-150 ease-out-quart hover:bg-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green">
                {{ __('Sign in') }}
            </button>
        </form>

        @if (\JoelButcher\Socialstream\Socialstream::show() && ! empty(\JoelButcher\Socialstream\Socialstream::providers()))
            <x-socialstream />
        @endif

        <x-slot name="footer">
            {{ __("Don't have an account?") }}
            <a href="{{ route('register') }}"
               class="rounded-sm font-semibold text-registry-green transition-colors duration-150 ease-out-quart hover:text-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green">
                {{ __('Start free') }}
            </a>
        </x-slot>
    </x-auth.card>
@endsection
