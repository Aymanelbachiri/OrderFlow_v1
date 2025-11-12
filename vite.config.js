import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: 'localhost',
    },
    // server: {
    //     host: '0.0.0.0',   // 👈 allow external devices
    //     port: 5173,        // default port
    //     hmr: {
    //       host: '192.168.0.110', // 👈 replace with your computer’s IP address
    //     },
    // },
});
