let mix = require("laravel-mix");

require("laravel-mix-tailwind");
require("laravel-mix-purgecss");
let purgeCssConfig = require("./purgecss");

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
    .sass("resources/scss/app.scss", "public/css")
    .tailwind()
    .purgeCss(purgeCssConfig)
    .copyDirectory("resources/svg", "public/svg")
    .browserSync({
        proxy: process.env.MIX_APP_URL,
        files: ["./resources/views/**/*.blade.php", "./public/**/*"],
        notify: {
            styles: {
                top: "auto",
                bottom: "0"
            }
        },
        injectChanges: true
    })
    .webpackConfig({
        resolve: {
            modules: [path.resolve(__dirname, "./resources/js"), "node_modules"]
        }
    });

if (mix.inProduction()) {
    mix.version();
}
