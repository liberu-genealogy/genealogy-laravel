const mix = require('laravel-mix');

mix.postCss('resources/css/tailwind.css', 'public/build/assets', [
    require('tailwindcss'),
])
.version();
