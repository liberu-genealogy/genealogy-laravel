<x-app-layout>
@include('components.home-navbar')

    <main class="flex-1 mt-[64px] pt-6">
        @yield('content')
    </main>

    @include('components.footer')
</x-app-layout>