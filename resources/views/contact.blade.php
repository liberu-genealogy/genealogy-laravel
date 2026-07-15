@extends('layouts.home', ['fieldHero' => true])

@php
    $repo = 'https://github.com/liberu-genealogy/genealogy-laravel';
@endphp

@section('content')

<section class="field-ruled bg-registry-field">
    <div class="mx-auto max-w-6xl px-6 py-20 lg:py-24">
        <h1 class="max-w-[16ch] text-display text-balance text-paper">
            Ask us something.
        </h1>
        <p class="mt-6 max-w-[58ch] text-pretty text-body text-emerald-100">
            A real person reads these. If it's a bug or a feature request, the issue tracker is
            faster and public — everyone else gets to see the answer too.
        </p>
    </div>
</section>

<section class="border-b border-rule bg-paper" aria-labelledby="contact-heading">
    <div class="mx-auto grid max-w-6xl gap-12 px-6 py-20 lg:grid-cols-12 lg:gap-16 lg:py-24">
        <div class="lg:col-span-5">
            <h2 id="contact-heading" class="text-headline text-balance text-ink">
                Send a message
            </h2>
            <p class="mt-4 max-w-[42ch] text-pretty text-body text-ink-muted">
                We'll reply to the address you give us and we won't use it for anything else. No
                list, no newsletter, no follow-up sequence.
            </p>

            <div class="mt-8 border-t border-rule pt-8">
                <h3 class="text-title text-ink">Faster elsewhere</h3>
                <p class="mt-2 max-w-[42ch] text-pretty text-body text-ink-muted">
                    Bugs and feature requests belong in the issue tracker, where they get a number,
                    a history, and an answer anyone can read.
                </p>
                <a href="{{ $repo }}/issues"
                   class="mt-3 inline-flex min-h-11 items-center gap-1.5 rounded-sm text-label text-registry-green transition-colors duration-150 ease-out-quart hover:text-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green">
                    Open an issue
                    <svg class="size-4" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M6 3l5 5-5 5"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="lg:col-span-7">
            <x-contact-form />
        </div>
    </div>
</section>

@endsection
