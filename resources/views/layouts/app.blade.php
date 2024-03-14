/**
 * File: app.blade.php
 * Description: This file is the main layout file for the application.
 * It includes the header, main content, and footer components.
 */
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liberu Genealogy</title>
    @vite('resources/css/app.css')
</head>
<body>

    <main>
        @yield('content')
    </main>

    @vite('resources/js/app.js')
</body>
</html>
