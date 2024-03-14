<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liberu Genealogy</title>
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body>
    @include('components.home_header')
    @include('components.home_navbar')

    <main>
        @include('components.manage_section')
       
     
    </main>

    @vite('resources/js/app.js')
    @livewireScripts
</body>
</html>
    @include('components.footer')
    @include('components.footer')
