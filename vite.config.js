import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite'; // 1. Import plugin tailwind

export default defineConfig({
    plugins: [
        tailwindcss(), // 2. Panggil plugin tailwind di sini
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});