import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/welcome.css', 'resources/css/profile-tilt.css',
                'resources/css/app.css', 'resources/js/app.js', 'resources/js/welcome.js', 'resources/js/guest.js'],
            refresh: true,
        }),
    ],
});
