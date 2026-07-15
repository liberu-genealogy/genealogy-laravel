{{-- bare: this layout supplies its own landmarks, so the shell must not wrap
     them in a second <main>. See App\View\Components\AppLayout. --}}
<x-app-layout bare>
    @include('components.home-navbar')

    <main id="main" class="flex-1">
        @yield('content')
    </main>

    @include('components.footer')
</x-app-layout>
