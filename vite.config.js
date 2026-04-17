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
    resolve: {
        alias: {
            '@': '/resources'
        },
    },
    server: {
        host: 'xmlsystem.test',
        port: 5174,
        hmr: {
            host: 'xmlsystem.test',
        },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
