@php
    $settings = app(\App\Settings\GeneralSettings::class);
    $currentPath = '/' . ltrim(request()->path(), '/');

    // The bar joins the field only where there IS a field, so the hero reads as
    // one continuous surface instead of a green band pinned under a white rule.
    // Elsewhere the bar is paper chrome — a lone green bar over a light page is
    // not commitment, just a stripe. Only the homepage has a field today.
    $onField = $currentPath === '/';

    $navLinkClass = fn (string $path) => 'rounded-sm px-1 py-2 text-label transition-colors duration-150 ease-out-quart focus-visible:outline-2 focus-visible:outline-offset-2 ' .
        ($onField ? 'focus-visible:outline-registry-tint ' : 'focus-visible:outline-registry-green ') .
        ($currentPath === $path
            ? ($onField ? 'font-semibold text-paper' : 'font-semibold text-registry-green-deep')
            : ($onField ? 'text-emerald-100 hover:text-paper' : 'text-ink-muted hover:text-ink'));
@endphp
{{-- DESIGN.md §2, The Committed Field Rule. --}}
<header @class([
    'sticky top-0 z-[var(--z-sticky)]',
    'field-ruled bg-registry-field' => $onField,
    'border-b border-rule bg-paper' => ! $onField,
])>
    <nav aria-label="Primary" class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-x-6 px-6 py-3">
        {{-- A text wordmark, not logo1.svg. That file is a white "Family Tree 365"
             lockup naming a different brand than $settings->site_name, which
             self-hosters configure. Set a real mark here once one exists. --}}
        <a href="/"
           @class([
               'order-1 flex-none rounded-sm text-title transition-colors duration-150 ease-out-quart focus-visible:outline-2 focus-visible:outline-offset-2',
               'text-paper hover:text-registry-tint focus-visible:outline-registry-tint' => $onField,
               'text-ink hover:text-registry-green-deep focus-visible:outline-registry-green' => ! $onField,
           ])>
            {{ $settings->site_name }}
        </a>

        <div class="order-3 flex items-center gap-x-2">
            <button type="button"
                    @class([
                        'flex size-11 items-center justify-center rounded-md transition-colors duration-150 ease-out-quart focus-visible:outline-2 focus-visible:outline-offset-2 sm:hidden',
                        'text-paper hover:bg-white/10 focus-visible:outline-registry-tint' => $onField,
                        'text-ink-muted hover:bg-surface-sunk hover:text-ink focus-visible:outline-registry-green' => ! $onField,
                    ])
                    id="navbar-mobile-toggle"
                    aria-expanded="false"
                    aria-controls="navbar-mobile-menu">
                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <line x1="3" x2="21" y1="6" y2="6"/><line x1="3" x2="21" y1="12" y2="12"/><line x1="3" x2="21" y1="18" y2="18"/>
                </svg>
                <span class="sr-only">Toggle navigation</span>
            </button>

            @php
                // On the field the primary action inverts to white paper: a
                // registry-green button on the field would be 1.77:1.
                $cta = $onField
                    ? 'inline-flex min-h-11 items-center rounded-md bg-paper px-4 py-2 text-label text-registry-green-deep transition-colors duration-150 ease-out-quart hover:bg-registry-tint focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-tint'
                    : 'inline-flex min-h-11 items-center rounded-md bg-registry-green px-4 py-2 text-label text-paper transition-colors duration-150 ease-out-quart hover:bg-registry-green-deep focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-registry-green';
            @endphp
            @auth
                <a href="{{ route('filament.app.tenant') }}" class="{{ $cta }}">Open your tree</a>
            @else
                <a href="/login"
                   @class([
                       'hidden min-h-11 items-center rounded-sm px-2 text-label transition-colors duration-150 ease-out-quart focus-visible:outline-2 focus-visible:outline-offset-2 sm:inline-flex',
                       'text-emerald-100 hover:text-paper focus-visible:outline-registry-tint' => $onField,
                       'text-ink-muted hover:text-ink focus-visible:outline-registry-green' => ! $onField,
                   ])>
                    Sign in
                </a>
                <a href="/register" class="{{ $cta }}">Start free</a>
            @endauth
        </div>

        <div id="navbar-mobile-menu" class="hidden basis-full sm:order-2 sm:block sm:basis-auto">
            <ul class="flex flex-col gap-1 pt-2 pb-1 sm:flex-row sm:items-center sm:gap-6 sm:py-0">
                <li><a class="{{ $navLinkClass('/') }}" @if($currentPath === '/') aria-current="page" @endif href="/">Home</a></li>
                <li><a class="{{ $navLinkClass('/about') }}" @if($currentPath === '/about') aria-current="page" @endif href="/about">About</a></li>
                <li><a class="{{ $navLinkClass('/subscription') }}" @if($currentPath === '/subscription') aria-current="page" @endif href="/subscription">Pricing</a></li>
                <li><a class="{{ $navLinkClass('/contact') }}" @if($currentPath === '/contact') aria-current="page" @endif href="/contact">Contact</a></li>
                @guest
                    <li class="sm:hidden"><a class="{{ $navLinkClass('/login') }}" href="/login">Sign in</a></li>
                @endguest
            </ul>
        </div>
    </nav>
</header>

@push('scripts')
<script>
    document.getElementById('navbar-mobile-toggle')?.addEventListener('click', function () {
        var menu = document.getElementById('navbar-mobile-menu');
        var expanded = this.getAttribute('aria-expanded') === 'true';
        menu.classList.toggle('hidden');
        this.setAttribute('aria-expanded', String(!expanded));
    });
</script>
@endpush
