<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liberu Genealogy</title>
    @vite('resources/css/app.css')
</head>
<body>
    @include('components.header') 

    <main>
        @yield('content')
    </main>

    @include('components.footer')

    @vite('resources/js/app.js')
</body>
</html>
