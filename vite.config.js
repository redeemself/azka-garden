import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

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
      input: [
        'resources/css/app.css',
        'resources/css/user/user.css',
        'resources/css/admin/admin.css',
        'resources/css/dev/dev.css',
        'resources/js/app.js',
      ],
      refresh: true,
    }),
  ],
});
