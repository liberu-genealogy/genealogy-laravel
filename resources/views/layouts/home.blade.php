<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Home Page')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/filament/styles.css') }}">
</head>
<body>
    <header>
        @include('layouts.partials.header')
    </header>
    <main>
        @yield('content')
    </main>
    <footer>
        @yield('footer', view('layouts.partials.footer'))
    </footer>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('vendor/filament/scripts.js') }}"></script>
</body>
</html>
