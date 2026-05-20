@php
    $currentPath = '/' . ltrim(request()->path(), '/');
    $navLinkClass = fn(string $path) => 'font-medium focus:outline-none transition-colors ' .
        ($currentPath === $path
            ? 'text-emerald-600 border-b-2 border-emerald-600 pb-0.5'
            : 'text-gray-600 hover:text-emerald-600');
@endphp
<header class="sticky top-0 z-50 flex flex-wrap sm:justify-start sm:flex-nowrap w-full bg-white/95 backdrop-blur-sm shadow-sm text-sm py-3 border-b border-gray-100">
    <nav class="max-w-[85rem] w-full mx-auto px-4 flex flex-wrap basis-full items-center justify-between">
        <a class="sm:order-1 flex-none text-xl font-semibold focus:outline-none focus:opacity-80" href="/">
            <img src="{{ asset('/build/images/logo1.svg') }}" alt="Logo" class="h-8">
        </a>

        <div class="sm:order-3 flex items-center gap-x-3">
            <button type="button"
                    class="sm:hidden relative size-8 flex justify-center items-center rounded-lg border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-colors"
                    id="navbar-mobile-toggle"
                    aria-expanded="false"
                    aria-controls="navbar-mobile-menu"
                    aria-label="Toggle navigation">
                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <line x1="3" x2="21" y1="6" y2="6"/><line x1="3" x2="21" y1="12" y2="12"/><line x1="3" x2="21" y1="18" y2="18"/>
                </svg>
                <span class="sr-only">Toggle navigation</span>
            </button>

            @if(auth()->check())
                <a href="{{ route('filament.app.tenant') }}"
                   class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition-colors duration-200 shadow-sm">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>
            @else
                <a href="/login"
                   class="font-medium text-gray-600 hover:text-emerald-600 focus:outline-none transition-colors hidden sm:inline-block">
                    Sign In
                </a>
                <a href="/register"
                   class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-lg transition-colors duration-200 shadow-sm">
                    Get Started
                </a>
            @endif
        </div>

        <div id="navbar-mobile-menu" class="hidden overflow-hidden transition-all duration-300 basis-full grow sm:grow-0 sm:basis-auto sm:block sm:order-2">
            <div class="flex flex-col gap-1 mt-4 pb-2 sm:flex-row sm:items-center sm:gap-6 sm:mt-0 sm:pb-0 sm:ps-5">
                <a class="{{ $navLinkClass('/') }}" href="/">Home</a>
                <a class="{{ $navLinkClass('/about') }}" href="/about">About</a>
                <a class="{{ $navLinkClass('/contact') }}" href="/contact">Contact</a>
                <a class="{{ $navLinkClass('/privacy') }}" href="/privacy">Privacy</a>
                <a class="{{ $navLinkClass('/terms-and-conditions') }}" href="/terms-and-conditions">Terms</a>
                @if(!auth()->check())
                    <a class="font-medium text-gray-600 hover:text-emerald-600 focus:outline-none transition-colors sm:hidden" href="/login">Sign In</a>
                @endif
            </div>
        </div>
    </nav>
</header>

<script>
    document.getElementById('navbar-mobile-toggle').addEventListener('click', function () {
        var menu = document.getElementById('navbar-mobile-menu');
        var expanded = this.getAttribute('aria-expanded') === 'true';
        menu.classList.toggle('hidden');
        this.setAttribute('aria-expanded', String(!expanded));
    });
</script>
