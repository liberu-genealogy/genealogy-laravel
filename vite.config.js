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
//    build: {
//        outDir: '../public',
//    },
})
