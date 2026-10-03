import tailwindcss from '@tailwindcss/vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import { defineConfig, lazyPlugins } from 'vite-plus';

export default defineConfig({
    plugins: lazyPlugins(() => [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/passkeys.js',
            ],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ]),
    server: {
        // Pinned so this dev server never collides with the Vite servers of the
        // sibling projects on 5173 / 5174. strictPort makes a taken port fail
        // loudly instead of silently drifting to 5176.
        host: '0.0.0.0',
        port: 5175,
        strictPort: true,
        cors: true,
        // The browser runs on the host, not inside the container, so HMR and the
        // generated public/hot file must advertise localhost.
        hmr: {
            host: 'localhost',
        },
        watch: {
            ignored: [
                '**/.agents/**',
                '**/.claude/**',
                '**/.cursor/**',
                '**/.junie/**',
                '**/storage/framework/views/**',
                '**/vendor/**',
            ],
        },
    },
});
