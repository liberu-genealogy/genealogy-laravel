<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />

        <meta name="application-name" content="{{ config('app.name') }}" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>{{ config('app.name') }}</title>

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        @filamentStyles
        @vite('resources/css/app.css')
    </head>

    <body class="antialiased">
        {{ $slot }}
        
        @filamentScripts
        @vite('resources/js/app.js')

        @filamentScripts
        @vite('resources/js/app.js')
    </body>
</html>
<style>
    .card {
        @apply bg-green-500 bg-opacity-75 text-white rounded-lg shadow-md overflow-hidden;
    }
    .card-header {
        @apply text-gray-700 font-bold py-2 px-4;
    }
    .card-body {
        @apply p-4 bg-green-500 bg-opacity-75;
    }
</style>
<style>
    .card {
        @apply bg-green-500 bg-opacity-75 text-white rounded-lg shadow-md overflow-hidden;
    }
    .card-header {
        @apply text-gray-700 font-bold py-2 px-4;
    }
    .card-body {
        @apply p-4 bg-green-500 bg-opacity-75;
    }
</style>
