{{--
    The one shell every auth screen uses. Before this, login/register/forgot
    extended layouts.home while reset-password, confirm-password,
    two-factor-challenge and verify-email used <x-guest-layout> — a different
    document with its own font link (missing the 800 weight) and its own body
    styles. A single password-reset journey crossed both.

    Paper, not the field: auth is a task, not a pitch. The form is the only
    thing on screen that matters.
--}}
@props(['title', 'subtitle' => null])

<section class="bg-surface">
    <div class="mx-auto flex max-w-md flex-col justify-center px-6 py-16 lg:py-24">
        <div>
            <h1 class="text-headline text-balance text-ink">{{ $title }}</h1>
            @if ($subtitle)
                <p class="mt-2 text-pretty text-body text-ink-muted">{{ $subtitle }}</p>
            @endif
        </div>

        <div class="mt-8 rounded-lg border border-rule bg-paper p-6 sm:p-8">
            {{ $slot }}
        </div>

        @if (isset($footer))
            <p class="mt-6 text-center text-body text-ink-muted">
                {{ $footer }}
            </p>
        @endif
    </div>
</section>
