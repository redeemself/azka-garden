import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import externals from './vite.externals.js';

export default defineConfig({
    server: {
        // Serve on custom domain
        host: 'azka-garden.test',
        port: 5173,
        strictPort: true,
        cors: true,
        // Disable HMR to prevent "Upgrade Required" messages
        hmr: false,
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            lodash: '/js/vendor/lodash.min.js',
            axios: '/js/vendor/axios.min.js',
            alpinejs: '/js/vendor/alpine.min.js',
            'chart.js/auto': '/js/vendor/chart.min.js',
            flatpickr: '/js/vendor/flatpickr.min.js',
        },
    },
    build: {
        rollupOptions: {
            external: Object.keys(externals),
            output: {
                globals: externals,
            },
        },
    },
    // Updated: 2025-07-30 04:12:47 by mulyadafa
});
