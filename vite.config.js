import { defineConfig } from 'vite'
import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';
import path from 'path';
import copy from 'vite-plugin-copy';

export default defineConfig({
    plugins: [
        laravel(['resources/css/app.css', 'resources/js/app.js']),
    copy({
      targets: [
        { src: 'resources/images/*', dest: 'public/assets/images' }
      ],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './resources')
    }
  },
      hook: 'writeBundle' // Use the 'writeBundle' hook to copy files after the bundle is written
    }),
    ],
})
    css: [
        'resources/css/tailwind.css',
        'resources/css/custom.css',
    ],
