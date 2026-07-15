@extends('layouts.home', ['fieldHero' => true])

@php
    // Real values, not hardcoded. The old page claimed "£4.99" and a "7-day"
    // trial while config said $2.99 / 14 days.
    $settings = app(\App\Settings\GeneralSettings::class);
    $price = config('subscription.premium.price', '$2.99');
    $interval = config('subscription.premium.interval', 'month');
    $trialDays = (int) config('subscription.premium.trial_days', 14);
@endphp

@section('content')

{{-- Hero. The office is green, the paper is white: the drenched field with the
     record lying on it as a document on a registrar's desk. DESIGN.md §2,
     The Committed Field Rule. --}}
<section class="field-ruled bg-registry-field">
    <div class="mx-auto grid max-w-6xl gap-14 px-6 py-20 lg:grid-cols-12 lg:items-center lg:gap-16 lg:py-28">
        <div class="lg:col-span-7">
            <h1 class="text-display text-balance text-paper">
                Every name, with the record that proves it.
            </h1>

            <p class="mt-6 max-w-[58ch] text-pretty text-body text-emerald-100">
                {{ $settings->site_name }} is a free, open-source platform for
                building a family tree out of evidence. GEDCOM in, GEDCOM out. DNA matches with their
                confidence stated in words, not colours. A citation under every claim.
            </p>

            <div class="mt-9 flex flex-wrap items-center gap-3">
                {{-- On the field the primary action inverts: white paper is the
                     strongest thing you can put on green. --}}
                @auth
                    <a href="{{ route('filament.app.tenant') }}"
                       class="inline-flex min-h-11 items-center rounded-md bg-paper px-5 py-3 text-label text-registry-green-deep transition-colors duration-150 ease-out-quart hover:bg-registry-tint focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-tint">
                        Open your tree
                    </a>
                @else
                    <a href="{{ route('register') }}"
                       class="inline-flex min-h-11 items-center rounded-md bg-paper px-5 py-3 text-label text-registry-green-deep transition-colors duration-150 ease-out-quart hover:bg-registry-tint focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-tint">
                        Start free
                    </a>
                @endauth

                <a href="{{ route('subscription') }}"
                   class="inline-flex min-h-11 items-center rounded-md border border-emerald-400 px-5 py-3 text-label text-paper transition-colors duration-150 ease-out-quart hover:bg-white/10 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-tint">
                    See pricing
                </a>
            </div>

            <p class="mt-5 text-label text-emerald-200">
                Free forever · No credit card required · MIT licensed
            </p>
        </div>

        {{-- The record IS the hero image: real markup, no stock photography.
             No border, no shadow — white against the field is its own edge. --}}
        <div class="lg:col-span-5">
            <figure class="rounded-lg bg-paper">
                <div class="flex items-baseline justify-between gap-4 border-b border-rule px-5 py-4">
                    <h2 class="text-title text-ink">Eleanor Whitfield</h2>
                    <span class="whitespace-nowrap text-label tabular-nums text-ink-muted">1834&ndash;1901</span>
                </div>

                <dl class="divide-y divide-rule text-label">
                    <div class="grid grid-cols-3 gap-4 px-5 py-3">
                        <dt class="text-ink-faint">Baptism</dt>
                        <dd class="col-span-2 tabular-nums text-ink">12 March 1834</dd>
                    </div>
                    <div class="grid grid-cols-3 gap-4 px-5 py-3">
                        <dt class="text-ink-faint">Parish</dt>
                        <dd class="col-span-2 text-ink">St Peter Mancroft, Norwich</dd>
                    </div>
                    <div class="grid grid-cols-3 gap-4 px-5 py-3">
                        <dt class="text-ink-faint">Source</dt>
                        <dd class="col-span-2 text-ink">Norfolk Parish Registers, <span class="tabular-nums">PD&nbsp;26/12</span></dd>
                    </div>
                </dl>

                <div class="flex flex-wrap items-center gap-3 border-t border-rule bg-surface px-5 py-4">
                    <span class="text-label text-ink-faint">DNA match</span>
                    <span class="text-body font-semibold tabular-nums text-ink">87.3 cM</span>
                    {{-- Confidence never rides on hue alone: value + word + shape. --}}
                    <span class="rounded-sm bg-registry-tint px-2 py-0.5 text-label font-semibold text-registry-green-deep">High</span>
                    <span class="h-1.5 w-16 overflow-hidden rounded-full bg-rule" aria-hidden="true">
                        <span class="block h-full w-[82%] rounded-full bg-registry-green"></span>
                    </span>
                </div>

                <figcaption class="border-t border-rule px-5 py-3 text-label text-ink-faint">
                    An example record. Every fact carries its source.
                </figcaption>
            </figure>
        </div>
    </div>
</section>

{{-- The research loop. Three unequal blocks separated by hairlines — not a card grid. --}}
<section class="border-b border-rule bg-surface" aria-labelledby="loop-heading">
    <div class="mx-auto max-w-6xl px-6 py-20 lg:py-24">
        <div class="max-w-[54ch]">
            <h2 id="loop-heading" class="text-headline text-balance text-ink">
                How a name earns its place in your tree
            </h2>
            <p class="mt-4 text-pretty text-body text-ink-muted">
                Three steps, in this order. Nothing is asserted before it is sourced.
            </p>
        </div>

        <div class="mt-12 grid gap-px overflow-hidden rounded-lg border border-rule bg-rule lg:grid-cols-5">
            {{-- flex-col + mt-auto: the citation sits on the block's floor rather
                 than leaving dead space under it when the row stretches. --}}
            <div class="flex flex-col bg-paper p-8 lg:col-span-3">
                <h3 class="text-title text-ink">It starts with a record</h3>
                <p class="mt-3 max-w-[52ch] text-body text-ink-muted">
                    A baptism, a census line, a headstone. You cite where it came from, and the
                    citation stays attached to the fact forever — not to a hint you can't inspect.
                </p>
                <p class="mt-auto pt-6">
                    <span class="inline-block rounded-sm bg-surface-sunk p-3 text-label text-ink">
                        <span class="text-ink-faint">Source</span>
                        &nbsp;Norfolk Parish Registers, <span class="tabular-nums">PD&nbsp;26/12</span>
                    </span>
                </p>
            </div>

            <div class="bg-paper p-8 lg:col-span-2">
                <h3 class="text-title text-ink">It lands in the tree</h3>
                <p class="mt-3 text-body text-ink-muted">
                    Placed against parents, spouses and children, where a contradiction shows up
                    immediately.
                </p>
                <svg class="mt-6 w-full max-w-[16rem]" viewBox="0 0 240 96" role="img" aria-label="A pedigree fragment: Eleanor Whitfield linked to her parents, Thomas and Mary." fill="none">
                    <rect x="0.5" y="36.5" width="92" height="23" rx="3" fill="#ecfdf5" stroke="#047857"/>
                    <text x="46" y="52" text-anchor="middle" font-size="11" font-weight="600" fill="#065f46" font-family="Figtree, sans-serif">Eleanor</text>
                    <path d="M93 48h28M121 48V14h26M121 48v34h26" stroke="#64748b" stroke-width="1.25"/>
                    <rect x="147.5" y="2.5" width="92" height="23" rx="3" fill="#ffffff" stroke="#e2e8f0"/>
                    <text x="193" y="18" text-anchor="middle" font-size="11" fill="#0f172a" font-family="Figtree, sans-serif">Thomas</text>
                    <rect x="147.5" y="70.5" width="92" height="23" rx="3" fill="#ffffff" stroke="#e2e8f0"/>
                    <text x="193" y="86" text-anchor="middle" font-size="11" fill="#0f172a" font-family="Figtree, sans-serif">Mary</text>
                </svg>
            </div>

            <div class="bg-paper p-8 lg:col-span-5">
                <div class="flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
                    <div class="max-w-[52ch]">
                        <h3 class="text-title text-ink">The match argues for itself</h3>
                        <p class="mt-3 text-body text-ink-muted">
                            A DNA match arrives with the shared centimorgans, a confidence in plain
                            words, and the segments it's built from. No blurring, no counter telling
                            you there are discoveries you haven't paid for.
                        </p>
                    </div>

                    <ul class="flex shrink-0 flex-col gap-2 text-label">
                        {{-- Widths are utility classes, not inline styles: the app sends a
                             CSP, and style-src would have to allow 'unsafe-inline' for them. --}}
                        @foreach ([['87.3 cM', 'High', 'w-[82%]'], ['41.6 cM', 'Probable', 'w-[48%]'], ['12.2 cM', 'Speculative', 'w-[16%]']] as [$cm, $label, $barWidth])
                            <li class="flex items-center gap-3">
                                <span class="w-20 font-semibold tabular-nums text-ink">{{ $cm }}</span>
                                <span class="w-24 text-ink-muted">{{ $label }}</span>
                                <span class="h-1.5 w-24 overflow-hidden rounded-full bg-rule" aria-hidden="true">
                                    <span class="block h-full rounded-full bg-registry-green {{ $barWidth }}"></span>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Portability. The argument no competitor makes. --}}
<section class="border-b border-rule bg-paper" aria-labelledby="portability-heading">
    <div class="mx-auto max-w-6xl px-6 py-20 lg:py-24">
        <div class="max-w-[46ch]">
            <h2 id="portability-heading" class="text-headline text-balance text-ink">
                Leaving is a feature
            </h2>
            <p class="mt-4 text-pretty text-body text-ink-muted">
                A decade of research should never be hostage to a subscription. Everything you put in
                comes back out in a format other software can read.
            </p>
        </div>

        {{-- Three across at lg only: below that the measure collapses to ~20ch
             and the rag falls apart. --}}
        <dl class="mt-12 grid gap-x-12 gap-y-10 border-t border-rule pt-10 sm:grid-cols-2 lg:grid-cols-3">
            <div class="max-w-[38ch]">
                <dt class="text-title text-ink">GEDCOM, both ways</dt>
                <dd class="mt-2 text-pretty text-body text-ink-muted">
                    Import and export the industry standard. The same file you brought in is the file
                    you can take out.
                </dd>
            </div>
            <div class="max-w-[38ch]">
                <dt class="text-title text-ink">MIT licensed</dt>
                <dd class="mt-2 text-pretty text-body text-ink-muted">
                    The whole platform is open source. Read it, fork it, audit what it does with your
                    family's data.
                </dd>
            </div>
            <div class="max-w-[38ch]">
                <dt class="text-title text-ink">Self-host it</dt>
                <dd class="mt-2 text-pretty text-body text-ink-muted">
                    Run it on your own server and the question of who owns the records stops being a
                    question.
                </dd>
            </div>
        </dl>
    </div>
</section>

{{-- Premium. Real numbers, read from config — no invented pricing. --}}
<section class="border-b border-rule bg-surface" aria-labelledby="premium-heading">
    <div class="mx-auto grid max-w-6xl gap-12 px-6 py-20 lg:grid-cols-12 lg:gap-16 lg:py-24">
        <div class="lg:col-span-6">
            <h2 id="premium-heading" class="text-headline text-balance text-ink">
                The free tier is the product. Premium is the heavy lifting.
            </h2>
            <p class="mt-4 max-w-[54ch] text-pretty text-body text-ink-muted">
                Building, citing and exporting your tree costs nothing, forever. Premium pays for the
                work that burns CPU on our side.
            </p>

            <ul class="mt-8 flex flex-col gap-3 text-body text-ink">
                @foreach ([
                    'Unlimited DNA uploads across testing companies',
                    'Duplicate detection and assisted merging',
                    'Smart matching against public trees',
                    'Automated background record discovery',
                ] as $benefit)
                    <li class="flex items-start gap-3">
                        <svg class="mt-1.5 size-4 shrink-0 text-registry-green" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M2.5 8.5l3.5 3.5 7.5-7.5"/>
                        </svg>
                        {{ $benefit }}
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="lg:col-span-6 lg:justify-self-end">
            <div class="rounded-lg border border-rule bg-paper p-8 sm:min-w-[22rem]">
                <p class="flex items-baseline gap-2">
                    <span class="text-headline tabular-nums text-ink">{{ $price }}</span>
                    <span class="text-body text-ink-muted">per {{ $interval }}</span>
                </p>

                <p class="mt-3 text-label text-ink-muted">
                    <span class="tabular-nums">{{ $trialDays }}</span>-day free trial. No card required to start.
                </p>

                @guest
                    <a href="{{ route('register') }}"
                       class="mt-7 inline-flex min-h-11 w-full items-center justify-center rounded-md bg-registry-green px-5 py-3 text-label text-paper transition-colors duration-150 ease-out-quart hover:bg-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green">
                        Start the trial
                    </a>
                @else
                    <a href="{{ url('/app/subscription') }}"
                       class="mt-7 inline-flex min-h-11 w-full items-center justify-center rounded-md bg-registry-green px-5 py-3 text-label text-paper transition-colors duration-150 ease-out-quart hover:bg-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green">
                        Upgrade to premium
                    </a>
                @endguest

                <a href="{{ route('subscription') }}"
                   class="mt-3 inline-flex min-h-11 w-full items-center justify-center rounded-sm px-2 text-label text-registry-green transition-colors duration-150 ease-out-quart hover:text-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green">
                    What's included
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Close on the field, bookending the hero: you enter and leave through the
     office. Committed, ~a third of the page. --}}
<section class="field-ruled bg-registry-field" aria-labelledby="cta-heading">
    {{-- Two columns so the band doesn't end as a lonely left block against an
         empty right half. --}}
    <div class="mx-auto grid max-w-6xl gap-10 px-6 py-20 lg:grid-cols-12 lg:items-center lg:gap-16 lg:py-24">
        <div class="lg:col-span-7">
            <h2 id="cta-heading" class="text-headline text-balance text-paper">
                Start with one name and the record behind it.
            </h2>
            <p class="mt-4 max-w-[52ch] text-pretty text-body text-emerald-100">
                Import a GEDCOM you already have, or begin from a single person. Either way, you can
                take the whole thing with you when you go.
            </p>
        </div>

        <div class="lg:col-span-5">
            {{-- Retuned to the field: a registry-green button here would be 1.77:1
                 against it, and slate greys read as a hedge in the green. --}}
            <div class="flex flex-wrap items-center gap-3 lg:justify-end">
                @guest
                    <a href="{{ route('register') }}"
                       class="inline-flex min-h-11 items-center rounded-md bg-paper px-5 py-3 text-label text-registry-green-deep transition-colors duration-150 ease-out-quart hover:bg-registry-tint focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-tint">
                        Start free
                    </a>
                @else
                    <a href="{{ route('filament.app.tenant') }}"
                       class="inline-flex min-h-11 items-center rounded-md bg-paper px-5 py-3 text-label text-registry-green-deep transition-colors duration-150 ease-out-quart hover:bg-registry-tint focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-tint">
                        Continue your tree
                    </a>
                @endguest

                <a href="https://github.com/liberu-genealogy/genealogy-laravel"
                   class="inline-flex min-h-11 items-center rounded-md border border-emerald-400 px-5 py-3 text-label text-paper transition-colors duration-150 ease-out-quart hover:bg-white/10 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-tint">
                    Read the source
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
