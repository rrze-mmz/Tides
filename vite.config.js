import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  server: {
    watch: {
      ignored: ['**/storage/app/videos/**'],
    },
  },

  plugins: [laravel(['resources/css/app.css', 'resources/js/app.js'])],
  resolve: {
    alias: {
      '@': '/resources/js',
      boot: './bootstrap',
    },
  },
});
