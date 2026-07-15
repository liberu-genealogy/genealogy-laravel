{{-- Was <x-guest-layout>; see auth/card.blade.php for why all seven now share one shell. --}}
@extends('layouts.home')

@section('content')
    <x-auth.card
        :title="__('Verify your email')"
        :subtitle="__('We sent a link to the address you signed up with. Click it and you are in.')"
    >
        @if (session('status') == 'verification-link-sent')
            <p role="status" class="mb-6 rounded-md border border-registry-green bg-registry-tint px-4 py-3 text-body text-registry-green-deep">
                {{ __('A new verification link has been sent to your email address.') }}
            </p>
        @endif

        <p class="text-pretty text-body text-ink-muted">
            {{ __("If it hasn't arrived, check your spam folder — or send another.") }}
        </p>

        <form method="POST" action="{{ route('verification.send') }}" class="mt-6">
            @csrf
            <button type="submit"
                    class="inline-flex min-h-11 w-full items-center justify-center rounded-md bg-registry-green px-5 py-3 text-label text-paper transition-colors duration-150 ease-out-quart hover:bg-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green">
                {{ __('Resend verification email') }}
            </button>
        </form>

        <div class="mt-6 flex items-center justify-between gap-4 border-t border-rule pt-6">
            <a href="{{ route('profile.show') }}"
               class="inline-flex min-h-11 items-center rounded-sm text-label text-registry-green transition-colors duration-150 ease-out-quart hover:text-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green">
                {{ __('Edit profile') }}
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="inline-flex min-h-11 items-center rounded-sm text-label text-ink-muted transition-colors duration-150 ease-out-quart hover:text-ink focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green">
                    {{ __('Sign out') }}
                </button>
            </form>
        </div>
    </x-auth.card>
@endsection
