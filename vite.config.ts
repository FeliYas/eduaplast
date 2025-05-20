import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import tailwindcss from "@tailwindcss/vite";
import { resolve } from 'node:path';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    base: 'eduaplast/build/',
    build: {
        outDir: resolve(__dirname, 'public/build'),
        emptyOutDir: true,
        sourcemap: true,
        rollupOptions: {
            output: {
                // Ajusta el nombre de los archivos según tus necesidades
                chunkFileNames: 'assets/[name]-[hash].js',
                entryFileNames: 'assets/[name]-[hash].js',
                assetFileNames: 'assets/[name]-[hash][extname]',
            },
        },
    },
    // resolve: {
    //     alias: {
    //         '@': path.resolve(__dirname, './resources/js'),
    //         'ziggy-js': resolve(__dirname, 'vendor/tightenco/ziggy'),
    //     },
    // },
    
});
