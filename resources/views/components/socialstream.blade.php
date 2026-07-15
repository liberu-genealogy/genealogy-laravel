{{--
    Published from bursteri/socialstream's stubs and restyled onto the Registry
    palette. This file's absence was the whole auth outage: auth/login.blade.php
    calls a bare <x-socialstream />, which Laravel resolves against
    resources/views/components/socialstream.blade.php. The package ships the view
    under its own `socialstream::` namespace, so the bare call found nothing and
    /app/login returned 500 on every request.

    Note: the providers listed here come from config/socialstream.php, and none
    of them currently has a client_id in config/services.php — every button will
    error on click until OAuth credentials are configured.
--}}
@php
    $providers = \JoelButcher\Socialstream\Socialstream::providers();
@endphp

@if (! empty($providers))
    <div class="mt-8">
        <div class="flex items-center gap-4">
            <span class="h-px flex-1 bg-rule"></span>
            <span class="text-label text-ink-muted">{{ config('socialstream.prompt', 'Or continue with') }}</span>
            <span class="h-px flex-1 bg-rule"></span>
        </div>

        <x-input-error :for="'socialstream'" class="mt-4 text-label text-flag-error" />

        <div class="mt-6 grid gap-3 sm:grid-cols-2">
            @foreach ($providers as $provider)
                {{-- ink-faint border: 4.76:1, clearing the 3:1 a control boundary
                     needs. The stub used gray-400 at 2.56:1. --}}
                <a href="{{ route('oauth.redirect', $provider['id']) }}"
                   class="inline-flex min-h-11 items-center justify-center gap-2.5 rounded-md border border-ink-faint px-4 py-2.5 text-label text-ink transition-colors duration-150 ease-out-quart hover:bg-surface focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green">
                    <x-socialstream-icons.provider-icon :provider="$provider['id']" class="size-5 shrink-0" />
                    <span>{{ $provider['buttonLabel'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
@endif
