import { defineConfig } from "vite";
import { quasar, transformAssetUrls } from "@quasar/vite-plugin";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import ReactivityTransform from '@vue-macros/reactivity-transform/vite'

export default defineConfig({
    server: {
        host: "0.0.0.0",
        port: 5175,
        hmr: {
            host: "localhost",
        },
    },
    plugins: [
        laravel({
            input: ["resources/sass/app.scss", "resources/js/app.js"],
            refresh: true,
        }),

        vue({
            template: { transformAssetUrls },
            reactivityTransform: true,
        }),

        quasar({
            sassVariables: "./resources/sass/quasar.variables.sass",
        }),

        ReactivityTransform()
    ],

    resolve: {
        alias: {
            "@": "/resources/js",
        },
    },
});
