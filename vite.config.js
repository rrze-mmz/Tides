import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  plugins: [laravel(['resources/css/app.css', 'resources/js/app.js'])],
  build: { chunkSizeWarningLimit: 1600 },
  resolve: {
    alias: {
      '@': '/resources/js',
      boot: './bootstrap',
    },
  },
});
