/**
 * Configures Vite for use with a Laravel application. This configuration includes setting up
 * necessary plugins for integrating with Laravel, specifying path aliases for easier imports,
 * and defining global CSS files to be included in the build.
 * 
 * The configuration object passed to defineConfig includes:
 * - plugins: An array of plugins used by Vite, including Laravel integration and static file copying.
 * - resolve: An object specifying path aliases.
 * - css: An array of global CSS files to be included.
 */
import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';
import path from 'path';
import { viteStaticCopy } from 'vite-plugin-static-copy'

export default defineConfig({
    plugins: [
        laravel(['resources/css/app.css', 'resources/js/app.js']),
        viteStaticCopy({
                           targets: [
                               {
                                   src: 'resources/images/*',
                                   dest: 'public/assets/images'
                               }
                           ]
                       }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources')
        }
    },
    css: [
        'resources/css/tailwind.css',
        'resources/css/custom.css',
    ],
})
