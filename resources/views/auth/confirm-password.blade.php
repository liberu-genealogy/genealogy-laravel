{{-- Was <x-guest-layout>; see auth/card.blade.php for why all seven now share one shell. --}}
@extends('layouts.home')

@section('content')
    <x-auth.card
        :title="__('Confirm your password')"
        :subtitle="__('This is a secure area. Please confirm your password before continuing.')"
    >
        <x-validation-errors class="mb-6" />

        <form method="POST" action="{{ route('password.confirm') }}" class="flex flex-col gap-5">
            @csrf

            <x-auth.field
                name="password"
                :label="__('Password')"
                type="password"
                autocomplete="current-password"
                autofocus
            />

            <button type="submit"
                    class="inline-flex min-h-11 w-full items-center justify-center rounded-md bg-registry-green px-5 py-3 text-label text-paper transition-colors duration-150 ease-out-quart hover:bg-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green">
                {{ __('Confirm') }}
            </button>
        </form>
    </x-auth.card>
@endsection
