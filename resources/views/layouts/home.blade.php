@extends('layouts.app')

@section('content')
    <x-home-header></x-home-header>
    <x-home-navbar></x-home-navbar>
    <x-contact-form></x-contact-form>

    <main>
        @include('components.manage_section')
    </main>
@endsection

    @vite('resources/js/app.js')
    @livewireScripts
</body>
</html>
    @include('components.footer')
