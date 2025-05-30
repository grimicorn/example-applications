const mix = require("laravel-mix");
const path = require("path");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js("resources/js/app.js", "public/js")
    .vue({ version: 3, extractStyles: true, globalStyles: false })
    .postCss("resources/css/app.css", "public/css", [
        require("postcss-import"),
        require("@tailwindcss/jit"),
        require("postcss-nested"),
        require("autoprefixer"),
    ])
    .webpackConfig((webpack) => {
        return {
            plugins: [
                new webpack.DefinePlugin({
                    __VUE_OPTIONS_API__: true,
                    __VUE_PROD_DEVTOOLS__: false,
                }),
            ],
            resolve: {
                alias: {
                    "@": path.resolve("./resources/js"),
                    "@libs": path.resolve("./resources/js/libs"),
                    "~": path.resolve("./node_modules"),
                },
                fallback: {
                    crypto: require.resolve("crypto-browserify"),
                    stream: require.resolve("stream-browserify"),
                },
            },
        };
    })
    .sourceMaps()
    .browserSync(process.env.MIX_APP_URL);

if (mix.inProduction()) {
    mix.version();
}
