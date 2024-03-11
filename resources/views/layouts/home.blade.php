<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liberu Genealogy</title>
    <link href="{{ asset('css/tailwind.css') }}" rel="stylesheet">
    @livewireStyles
</head>
<body>
    @include('components.header')

    <main>
        @include('components.home.manage')
        @include('components.home.products')
        @include('components.home.whyUs')
    </main>

    <script src="{{ asset('js/app.js') }}"></script>
    @livewireScripts
</body>
</html>
    @include('components.footer')
