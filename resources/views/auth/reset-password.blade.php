{{-- Was <x-guest-layout>: a second document shell with its own font link and
     body styles, so a password-reset journey changed layout mid-flow. --}}
@extends('layouts.home')

@section('content')
    <x-auth.card
        :title="__('Choose a new password')"
        :subtitle="__('Pick something you have not used elsewhere.')"
    >
        <x-validation-errors class="mb-6" />

        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-5">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <x-auth.field
                name="email"
                :label="__('Email address')"
                type="email"
                :value="old('email', $request->email)"
                autocomplete="username"
                autofocus
            />

            <x-auth.field
                name="password"
                :label="__('New password')"
                type="password"
                :hint="__('At least 8 characters.')"
                autocomplete="new-password"
            />

            <x-auth.field
                name="password_confirmation"
                :label="__('Confirm new password')"
                type="password"
                autocomplete="new-password"
            />

            <button type="submit"
                    class="inline-flex min-h-11 w-full items-center justify-center rounded-md bg-registry-green px-5 py-3 text-label text-paper transition-colors duration-150 ease-out-quart hover:bg-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green">
                {{ __('Reset password') }}
            </button>
        </form>
    </x-auth.card>
@endsection
