<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liberu Genealogy</title>
    <link href="{{ mix('css/tailwind.css') }}" rel="stylesheet">
    @livewireStyles
</head>
<body>
    @include('components.home_header')
    @include('components.home_navbar')

    <main>
        @include('components.manage_section')
       
     
    </main>

    <script src="{{ asset('js/app.js') }}"></script>
    @livewireScripts
</body>
</html>
    @include('components.footer')
