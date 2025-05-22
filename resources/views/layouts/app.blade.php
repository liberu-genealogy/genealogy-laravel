<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="_token" content="{!! csrf_token() !!}" />
    <title>{{ config('app.name') }}</title>
    @vite('resources/css/app.css')
    @stack('styles')
</head>
<body class="flex min-h-full flex-col bg-gray-100">
    <main class="flex-grow flex flex-col">
        {{ $slot ?? '' }}
    </main>
    @vite('resources/js/app.js')
    @stack('scripts')
</body>
</html>