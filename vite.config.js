import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        origin: 'https://planetaboy.com.br',
        cors: true,
        hmr: {
            host: 'planetaboy.com.br',
            port: 5173
        },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
