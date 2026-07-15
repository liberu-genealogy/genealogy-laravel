{{-- bare: this layout supplies its own landmarks, so the shell must not wrap
     them in a second <main>. See App\View\Components\AppLayout. --}}
<x-app-layout bare>
    {{-- Pages with a drenched hero pass fieldHero => true via @extends, so the
         bar joins their field. Everything else gets paper chrome. --}}
    @include('components.home-navbar', ['onField' => $fieldHero ?? false])

    <main id="main" class="flex-1">
        @yield('content')
    </main>

    @include('components.footer')
</x-app-layout>
