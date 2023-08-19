import { defineConfig } from 'vite';

export default defineConfig({
    server: {
        proxy: {
            '/app': 'http://localhost:8888'
        }
    },
    build: {
        rollupOptions: {
            input: 'resources/css/styles.css',
        }
    }
});
