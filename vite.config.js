import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import vueJsxPlugin from '@vitejs/plugin-vue-jsx';

export default defineConfig({
    server: {
        host: '0.0.0.0',  // Разрешает доступ по локальной сети
        port: 5173,
        cors: true,  // Включает CORS
        hmr: {
            host: 'crm-service.loc'  // Указывает хост для HMR
        }
    },
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            ssr: 'resources/js/ssr.js',
            refresh: true,
        }),

        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),

        vueJsxPlugin(),
    ],
});
