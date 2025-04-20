const mix = require("laravel-mix");

require("laravel-mix-alias");
require("laravel-mix-purgecss");

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
    .postCss("resources/css/app.css", "public/css", [
        require("postcss-import"),
        require("tailwindcss"),
        require("postcss-nested")
    ])
    .purgeCss(require('./purgecss.config'))
    .browserSync(process.env.MIX_APP_URL)
    .alias({
        "@": "/resources/js",
        "~": "/resources/sass",
        "@mixins": "/resources/js/mixins",
        "@utilities": "/resources/js/utilities"
    })
    .options({
        extractVueStyles: true
    });

if (mix.inProduction()) {
    mix.version();
}
