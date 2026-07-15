@extends('layouts.home', ['fieldHero' => true])

@php
    $settings = app(\App\Settings\GeneralSettings::class);
    $price = config('subscription.premium.price', '$2.99');
    $interval = config('subscription.premium.interval', 'month');
    $trialDays = (int) config('subscription.premium.trial_days', 14);
    $repo = 'https://github.com/liberu-genealogy/genealogy-laravel';

    // Every line below was checked against the code that enforces it.
    //
    // Free is everything that is NOT behind isPremium(): PersonResource,
    // FamilyResource, GedcomResource, MediaObjectResource and the chart pages
    // carry no premium check at all.
    //
    // Premium is exactly the set that does: DnaResource, DnaMatchingResource,
    // SmartMatchResource, DuplicateCheckResource, DnaTriangulationPage.
    //
    // Deliberately NOT listed, because nothing implements them: "priority
    // support" (a boolean in SubscriptionService's feature map, no support
    // system behind it), "advanced charts" (chart pages are free for everyone),
    // and media storage tiers (no limit exists in the codebase). The old page
    // sold all three, and also promised free users "1 DNA kit upload" when
    // DnaResource is gated outright — free gets none.
    $free = [
        'Build the tree — people, families, events, places',
        'Sources and citations under every fact',
        'Pedigree, fan and descendant charts',
        'Photos, documents and media',
        'GEDCOM import and export, always',
    ];

    $premium = [
        'Upload DNA from any testing company',
        'DNA matching against other kits',
        'Segment triangulation',
        'Smart matching across public trees',
        'Duplicate detection and assisted merging',
    ];

    $faqs = [
        [
            'q' => 'What happens when the trial ends?',
            'a' => "Nothing is taken from you. The DNA tools stop, and everything else — your tree, your sources, your media, your export — carries on working exactly as before. No card is required to start the trial, so nothing can be charged at the end of it.",
        ],
        [
            'q' => 'Can I cancel?',
            'a' => "Yes, at any time, and premium runs to the end of the period you already paid for. There is no cancellation fee and no retention flow designed to wear you down.",
        ],
        [
            'q' => 'What happens to my data if I stop paying?',
            'a' => 'It stays yours and it stays readable. Downgrading locks the DNA tools, not your records — and GEDCOM export is free forever, so you can take the whole tree elsewhere on the way out if you want to.',
        ],
        [
            // The one answer that carries its own artifact — the evidence sits
            // where the question is actually asked, not bolted to the CTA.
            'q' => 'How do payments work?',
            'a' => 'Cards are handled by Stripe. Card details never touch our servers, and you can ',
            'link' => ['read the subscription code', $repo.'/blob/main/app/Services/SubscriptionService.php'],
            'tail' => ' yourself.',
        ],
        [
            'q' => 'Why is any of it paid?',
            'a' => 'DNA matching and triangulation are the parts that burn real CPU on our side, so they are the parts that cost money. Building, citing and exporting your tree does not, so it does not.',
        ],
    ];
@endphp

@section('content')

{{-- The field. The pricing argument in one line. --}}
<section class="bg-registry-field">
    <div class="mx-auto max-w-6xl px-6 py-20 lg:py-24">
        <h1 class="max-w-[22ch] text-display text-balance text-paper">
            The tree is free. The DNA work isn't.
        </h1>
        <p class="mt-6 max-w-[58ch] text-pretty text-body text-emerald-100">
            Building, citing and exporting your family tree costs nothing, forever, with no row limit
            and no export paywall. Premium pays for the work that runs on our processors instead of
            your patience.
        </p>
    </div>
</section>

{{-- The two plans. Not a card grid: one ledger, two columns, a shared rule
     between them — the same register grammar as the record. --}}
<section class="border-b border-rule bg-paper" aria-labelledby="plans-heading">
    <div class="mx-auto max-w-6xl px-6 py-20 lg:py-24">
        <h2 id="plans-heading" class="sr-only">Plans</h2>

        <div class="grid gap-px overflow-hidden rounded-lg border border-rule bg-rule lg:grid-cols-2">
            {{-- Free --}}
            <div class="flex flex-col bg-paper p-8 lg:p-10">
                <h3 class="text-title text-ink">Free</h3>
                <p class="mt-1 text-label text-ink-muted">Forever, and not a trial of anything.</p>

                <p class="mt-6 flex items-baseline gap-2">
                    <span class="text-headline tabular-nums text-ink">$0</span>
                </p>

                <ul class="mt-8 flex flex-col gap-3 text-body text-ink">
                    @foreach ($free as $item)
                        <li class="flex items-start gap-3">
                            <svg class="mt-1.5 size-4 shrink-0 text-registry-green" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M2.5 8.5l3.5 3.5 7.5-7.5"/>
                            </svg>
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>

                <div class="mt-auto pt-10">
                    @guest
                        <a href="{{ route('register') }}"
                           class="inline-flex min-h-11 w-full items-center justify-center rounded-md border border-ink-faint px-5 py-3 text-label text-registry-green transition-colors duration-150 ease-out-quart hover:bg-surface hover:text-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green">
                            Start a tree
                        </a>
                    @else
                        <a href="{{ route('filament.app.tenant') }}"
                           class="inline-flex min-h-11 w-full items-center justify-center rounded-md border border-ink-faint px-5 py-3 text-label text-registry-green transition-colors duration-150 ease-out-quart hover:bg-surface hover:text-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green">
                            Open your tree
                        </a>
                    @endguest
                </div>
            </div>

            {{-- Premium. No "most popular" sticker: there is no data behind one,
                 and manufactured popularity is the pattern this product rejects. --}}
            <div class="flex flex-col bg-surface p-8 lg:p-10">
                <h3 class="text-title text-ink">Premium</h3>
                <p class="mt-1 text-label text-ink-muted">
                    <span class="tabular-nums">{{ $trialDays }}</span>-day trial. No card to start.
                </p>

                <p class="mt-6 flex items-baseline gap-2">
                    <span class="text-headline tabular-nums text-ink">{{ $price }}</span>
                    <span class="text-body text-ink-muted">per {{ $interval }}</span>
                </p>

                <ul class="mt-8 flex flex-col gap-3 text-body text-ink">
                    <li class="flex items-start gap-3 text-ink-muted">
                        <svg class="mt-1.5 size-4 shrink-0 text-registry-green" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M2.5 8.5l3.5 3.5 7.5-7.5"/>
                        </svg>
                        Everything in Free, unchanged
                    </li>
                    @foreach ($premium as $item)
                        <li class="flex items-start gap-3">
                            <svg class="mt-1.5 size-4 shrink-0 text-registry-green" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M2.5 8.5l3.5 3.5 7.5-7.5"/>
                            </svg>
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>

                <div class="mt-auto pt-10">
                    @guest
                        <a href="{{ route('register') }}"
                           class="inline-flex min-h-11 w-full items-center justify-center rounded-md bg-registry-green px-5 py-3 text-label text-paper transition-colors duration-150 ease-out-quart hover:bg-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green">
                            Start the trial
                        </a>
                    @else
                        <a href="{{ url('/app/subscription') }}"
                           class="inline-flex min-h-11 w-full items-center justify-center rounded-md bg-registry-green px-5 py-3 text-label text-paper transition-colors duration-150 ease-out-quart hover:bg-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green">
                            Upgrade to premium
                        </a>
                    @endguest
                </div>
            </div>
        </div>

        <p class="mt-6 max-w-[68ch] text-pretty text-label text-ink-muted">
            That is the whole list. There is no third tier, no seat count, and nothing on this page
            that isn't enforced in code you can read.
        </p>
    </div>
</section>

{{-- FAQ. The questions a paying researcher actually asks, answered without hedging. --}}
<section class="border-b border-rule bg-surface" aria-labelledby="faq-heading">
    <div class="mx-auto grid max-w-6xl gap-12 px-6 py-20 lg:grid-cols-12 lg:gap-16 lg:py-24">
        <div class="lg:col-span-4">
            <h2 id="faq-heading" class="text-headline text-balance text-ink">
                The questions worth asking
            </h2>
            <p class="mt-4 max-w-[38ch] text-pretty text-body text-ink-muted">
                Mostly variations on one question: what happens to my research if I stop paying.
            </p>
        </div>

        <dl class="divide-y divide-rule border-y border-rule lg:col-span-8">
            @foreach ($faqs as $faq)
                <div class="py-7">
                    <dt class="text-title text-balance text-ink">{{ $faq['q'] }}</dt>
                    <dd class="mt-2 max-w-[64ch] text-pretty text-body text-ink-muted">{{ $faq['a'] }}@isset($faq['link'])<a
                            href="{{ $faq['link'][1] }}"
                            class="rounded-sm text-registry-green underline underline-offset-2 transition-colors duration-150 ease-out-quart hover:text-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green"
                        >{{ $faq['link'][0] }}</a>{{ $faq['tail'] ?? '' }}@endisset</dd>
                </div>
            @endforeach
        </dl>
    </div>
</section>

{{-- Close on the field. --}}
<section class="bg-registry-field" aria-labelledby="sub-cta-heading">
    <div class="mx-auto grid max-w-6xl gap-10 px-6 py-20 lg:grid-cols-12 lg:items-center lg:gap-16 lg:py-24">
        <div class="lg:col-span-7">
            <h2 id="sub-cta-heading" class="text-headline text-balance text-paper">
                Start free. Add DNA when you need it.
            </h2>
            <p class="mt-4 max-w-[52ch] text-pretty text-body text-emerald-100">
                The trial takes no card, and the free tier isn't a countdown. If premium turns out
                not to be worth it, the tree you built is still yours and still exports.
            </p>
        </div>

        {{-- One action. A pricing page's last block is where someone decides to
             pay; a second button sending them to GitHub only competes with it,
             and this audience is genealogists, not developers. The source link
             lives in the FAQ, where the question is actually asked. --}}
        <div class="lg:col-span-5">
            <div class="flex flex-wrap items-center gap-3 lg:justify-end">
                @guest
                    <a href="{{ route('register') }}"
                       class="inline-flex min-h-11 items-center rounded-md bg-paper px-5 py-3 text-label text-registry-green-deep transition-colors duration-150 ease-out-quart hover:bg-registry-tint focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-tint">
                        Start free
                    </a>
                @else
                    <a href="{{ url('/app/subscription') }}"
                       class="inline-flex min-h-11 items-center rounded-md bg-paper px-5 py-3 text-label text-registry-green-deep transition-colors duration-150 ease-out-quart hover:bg-registry-tint focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-tint">
                        Upgrade to premium
                    </a>
                @endguest
            </div>
        </div>
    </div>
</section>

@endsection
