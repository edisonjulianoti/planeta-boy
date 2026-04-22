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
        origin: 'http://187.77.36.12:5173',
        cors: true,
        hmr: {
            host: '187.77.36.12',
            port: 5173
        },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
