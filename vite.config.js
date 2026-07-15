import { defineConfig } from 'vite'
import laravel, { refreshPaths } from 'laravel-vite-plugin'
import { viteStaticCopy } from "vite-plugin-static-copy";
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
		        'resources/css/filament/app/theme.css',
                'resources/css/filament/admin/theme.css',
            ],
            refresh: [
                ...refreshPaths,
                'app/Filament/**',
                'app/Forms/Components/**',
                'app/Livewire/**',
                'app/Infolists/Components/**',
                'app/Providers/Filament/**',
                'app/Tables/Columns/**',
            ],
        }),
        viteStaticCopy({
            targets: [
                {
                    src: "resources/images/*",
                    dest: "images",
                    // Without stripBase the matched path is preserved in full, so
                    // files landed at build/images/resources/images/* while blade
                    // asked for build/images/* — every image 404'd. Strip the two
                    // leading segments only, so corners/ and emails/ keep theirs.
                    rename: { stripBase: 2 },
                },
            ],
        }),
    ],
})
