import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import { viteStaticCopy } from 'vite-plugin-static-copy'

export default defineConfig({
    plugins: [
        laravel(['resources/css/app.css', 'resources/js/app.js']),
        viteStaticCopy({
                           targets: [
                               {
                                   src: 'resources/images/*',
                                   dest: 'images'
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
//    build: {
//        outDir: '../public',
//    },
})
/*
File: vite.config.js
Description: This configuration file is used to set up Vite for a Laravel application. It includes configurations for the Laravel Vite plugin, path resolution aliases, and copying static assets to the public directory. This setup enhances the development experience by streamlining asset management and build processes.
*/
    }),
    ],
})
