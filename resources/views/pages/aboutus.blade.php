@extends('layouts.home', ['fieldHero' => true])

@php
    $settings = app(\App\Settings\GeneralSettings::class);
    $repo = 'https://github.com/liberu-genealogy/genealogy-laravel';

    // Every claim carries the artifact that proves it — the same contract the
    // product asks of a genealogist. Nothing is asserted here without a link,
    // and nothing is claimed that the repository can't back up: no founding
    // story, no team, no commit counts that would rot by next month.
    $claims = [
        [
            'claim' => 'Your data leaves whole.',
            'body' => 'Import and export GEDCOM, the format every other genealogy program reads. The file you brought in is the file you can take out, and the exporter is not a premium feature.',
            'label' => 'Read the GEDCOM library',
            'href' => 'https://github.com/liberu-genealogy/laravel-gedcom',
        ],
        [
            'claim' => 'The source is the whole spec.',
            'body' => 'MIT licensed, in full. There is no hidden edition, no source-available asterisk, and no clause that stops you reading exactly what happens to your family\'s records.',
            'label' => 'Read the licence',
            'href' => $repo.'/blob/main/LICENSE',
        ],
        [
            'claim' => 'You can run it yourself.',
            'body' => 'Clone it, build it, point it at your own database on your own server. Then the question of who holds your records has a one-word answer, and the word is you.',
            'label' => 'Read the source',
            'href' => $repo,
        ],
        [
            'claim' => 'The rules are public, and they apply to everyone.',
            'body' => 'Contributions arrive by pull request against a published standard, under the Contributor Covenant. Disagreements happen in the open, in the issue tracker, where you can read them.',
            'label' => 'Read the code of conduct',
            'href' => $repo.'/blob/main/CODE_OF_CONDUCT.md',
        ],
    ];

    // The honest answer to "who is behind this" is: a family of open-source
    // Laravel applications. Named from README.md; each is a real repository.
    $siblings = [
        ['Accounting', 'https://github.com/liberu-accounting/accounting-laravel'],
        ['Automation', 'https://github.com/liberu-automation/automation-laravel'],
        ['Billing', 'https://github.com/liberu-billing/billing-laravel'],
        ['CMS', 'https://github.com/liberu-cms/cms-laravel'],
        ['Control Panel', 'https://github.com/liberu-control-panel/control-panel-laravel'],
        ['CRM', 'https://github.com/liberu-crm/crm-laravel'],
        ['E-commerce', 'https://github.com/liberu-ecommerce/ecommerce-laravel'],
        ['Maintenance', 'https://github.com/liberu-maintenance/maintenance-laravel'],
        ['Real Estate', 'https://github.com/liberu-real-estate/real-estate-laravel'],
        ['Social Network', 'https://github.com/liberu-social-network/social-network-laravel'],
        ['Browser Game', 'https://github.com/liberu-browser-game/browser-game-laravel'],
        ['Boilerplate (core)', 'https://github.com/liberusoftware/boilerplate'],
    ];
@endphp

@section('content')

{{-- The field. Short: this page is the citations, not the claim. --}}
<section class="field-ruled bg-registry-field">
    <div class="mx-auto max-w-6xl px-6 py-20 lg:py-24">
        <h1 class="max-w-[18ch] text-display text-balance text-paper">
            Don't take our word for it.
        </h1>
        <p class="mt-6 max-w-[58ch] text-pretty text-body text-emerald-100">
            {{ $settings->site_name }} asks you to put a source under every fact in your tree. It
            would be a poor thing to ask that and then expect you to trust it on faith. So every
            claim on this page carries the record that proves it.
        </p>
    </div>
</section>

{{-- The spine: claim → artifact. A register of entries, each with its source. --}}
<section class="border-b border-rule bg-paper" aria-labelledby="claims-heading">
    <div class="mx-auto max-w-6xl px-6 py-20 lg:py-24">
        <h2 id="claims-heading" class="sr-only">What we claim, and how to check it</h2>

        <dl class="divide-y divide-rule border-y border-rule">
            @foreach ($claims as $item)
                <div class="grid gap-x-12 gap-y-3 py-10 lg:grid-cols-12 lg:py-12">
                    <dt class="text-title text-balance text-ink lg:col-span-5">
                        {{ $item['claim'] }}
                    </dt>
                    <dd class="lg:col-span-7">
                        <p class="max-w-[62ch] text-pretty text-body text-ink-muted">
                            {{ $item['body'] }}
                        </p>
                        <a href="{{ $item['href'] }}"
                           class="mt-3 inline-flex min-h-11 items-center gap-1.5 rounded-sm text-label text-registry-green transition-colors duration-150 ease-out-quart hover:text-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green">
                            {{ $item['label'] }}
                            <svg class="size-4" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M6 3l5 5-5 5"/>
                            </svg>
                        </a>
                    </dd>
                </div>
            @endforeach
        </dl>
    </div>
</section>

{{-- What Liberu is: not a story, a list of repositories. --}}
<section class="border-b border-rule bg-surface" aria-labelledby="liberu-heading">
    <div class="mx-auto grid max-w-6xl gap-12 px-6 py-20 lg:grid-cols-12 lg:gap-16 lg:py-24">
        <div class="lg:col-span-5">
            <h2 id="liberu-heading" class="text-headline text-balance text-ink">
                What Liberu is
            </h2>
            <p class="mt-4 max-w-[46ch] text-pretty text-body text-ink-muted">
                A family of open-source Laravel applications, of which this is one. There is no
                story here beyond that — just other repositories, built the same way, under the
                same licence.
            </p>
            <p class="mt-4 max-w-[46ch] text-pretty text-body text-ink-muted">
                Genealogy is the one that keeps records of people. The rest keep records of
                something else.
            </p>
        </div>

        <div class="lg:col-span-7">
            <ul class="grid grid-cols-2 gap-x-8 sm:grid-cols-3">
                @foreach ($siblings as [$name, $href])
                    <li>
                        <a href="{{ $href }}"
                           class="inline-flex min-h-11 items-center rounded-sm text-label text-ink-muted transition-colors duration-150 ease-out-quart hover:text-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green">
                            {{ $name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>

{{-- Close on the field, same grammar as the homepage. --}}
<section class="field-ruled bg-registry-field" aria-labelledby="about-cta-heading">
    <div class="mx-auto grid max-w-6xl gap-10 px-6 py-20 lg:grid-cols-12 lg:items-center lg:gap-16 lg:py-24">
        <div class="lg:col-span-7">
            <h2 id="about-cta-heading" class="text-headline text-balance text-paper">
                Checked it? Then start a tree.
            </h2>
            <p class="mt-4 max-w-[52ch] text-pretty text-body text-emerald-100">
                Or don't, and run your own copy instead. Both of those are the point.
            </p>
        </div>

        <div class="lg:col-span-5">
            <div class="flex flex-wrap items-center gap-3 lg:justify-end">
                @guest
                    <a href="{{ route('register') }}"
                       class="inline-flex min-h-11 items-center rounded-md bg-paper px-5 py-3 text-label text-registry-green-deep transition-colors duration-150 ease-out-quart hover:bg-registry-tint focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-tint">
                        Start free
                    </a>
                @else
                    <a href="{{ route('filament.app.tenant') }}"
                       class="inline-flex min-h-11 items-center rounded-md bg-paper px-5 py-3 text-label text-registry-green-deep transition-colors duration-150 ease-out-quart hover:bg-registry-tint focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-tint">
                        Open your tree
                    </a>
                @endguest

                <a href="{{ $repo }}"
                   class="inline-flex min-h-11 items-center rounded-md border border-emerald-400 px-5 py-3 text-label text-paper transition-colors duration-150 ease-out-quart hover:bg-white/10 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-tint">
                    Read the source
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
