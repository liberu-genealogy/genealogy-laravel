<x-app-layout>
@include('components.home-navbar')

    <main class="flex-1">
        @yield('content')
    </main>

    @include('components.footer')
</x-app-layout>