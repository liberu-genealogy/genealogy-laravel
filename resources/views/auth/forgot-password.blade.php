@extends('layouts.home')

@section('content')
    <x-auth.card
        :title="__('Reset your password')"
        :subtitle="__('Give us the email address on your account and we will send a link to choose a new password.')"
    >
        @session('status')
            <p role="status" class="mb-6 rounded-md border border-registry-green bg-registry-tint px-4 py-3 text-body text-registry-green-deep">
                {{ $value }}
            </p>
        @endsession

        <x-validation-errors class="mb-6" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-5">
            @csrf

            <x-auth.field
                name="email"
                :label="__('Email address')"
                type="email"
                :value="old('email')"
                autocomplete="username"
                autofocus
            />

            <button type="submit"
                    class="inline-flex min-h-11 w-full items-center justify-center rounded-md bg-registry-green px-5 py-3 text-label text-paper transition-colors duration-150 ease-out-quart hover:bg-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green">
                {{ __('Email password reset link') }}
            </button>
        </form>

        <x-slot name="footer">
            <a href="{{ route('login') }}"
               class="rounded-sm font-semibold text-registry-green transition-colors duration-150 ease-out-quart hover:text-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green">
                {{ __('Back to sign in') }}
            </a>
        </x-slot>
    </x-auth.card>
@endsection
