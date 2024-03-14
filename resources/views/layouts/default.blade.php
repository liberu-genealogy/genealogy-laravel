<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liberu Genealogy</title>
    <link href="{{ asset('build/assets/css/tailwind.css') }}" rel="stylesheet">
    @livewireStyles
</head>
<body>
    @include('components.header')

    <main>
        @yield('content')
    </main>

    @include('components.footer')

    <script src="{{ asset('js/app.js') }}"></script>
    @livewireScripts
</body>
</html>
