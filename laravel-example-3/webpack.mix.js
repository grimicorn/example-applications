let mix = require('laravel-mix');
let path = require('path');

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

// Marketing
mix
    .less(
        'resources/assets/less/marketing.less',
        'public/css/marketing.less.css'
    )
    .standaloneSass(
        'resources/assets/scss/marketing.scss',
        'public/css/marketing.scss.css'
    )
    .styles(
        ['public/css/marketing.less.*css', 'public/css/marketing.scss.*css'],
        'public/css/marketing.css'
    )
    .js('resources/assets/js/marketing.js', 'public/js');

// Blog Wrap
mix.standaloneSass('resources/assets/scss/blog-wrap.scss', 'public/css');

// Application
mix
    .less('resources/assets/less/app.less', 'public/css/app.less.css')
    .standaloneSass('resources/assets/scss/app.scss', 'public/css/app.scss.css')
    .styles(
        [
            'node_modules/sweetalert/dist/sweetalert.css',
            'public/css/app.less.*css',
            'public/css/app.scss.*css',
        ],
        'public/css/app.css'
    )
    .js('resources/assets/js/app.js', 'public/js');

// Overlay
mix
    .copy('node_modules/intro.js/intro.js', 'public/js/intro.js')
    .copy('node_modules/intro.js/introjs.css', 'public/css/introjs.css');

// Shared
mix
    .sourceMaps()
    .options({
        uglify: {
            uglifyOptions: {
                mangle: {
                    safari10: true,
                },
            },
        },
    })
    .webpackConfig({
        resolve: {
            modules: [
                path.resolve(
                    __dirname,
                    'vendor/laravel/spark/resources/assets/js'
                ),
                'node_modules',
            ],
            alias: {
                vue$: 'vue/dist/vue.js',
            },
        },
    })
    .copyDirectory(
        './node_modules/font-awesome/fonts',
        './public/fonts/vendor/font-awesome'
    )
    .browserSync('firmexchange.test')
    .version();
