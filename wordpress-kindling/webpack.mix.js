let mix = require("laravel-mix");
let pjson = require("./package.json");
let PurgecssPlugin = require("purgecss-webpack-plugin");
let purgecssConfig = require("./purgecss.config");
let fs = require("fs");
require("laravel-mix-tailwind");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application, as well as bundling up your JS files.
 |
 */

if (!process.env.MIX_PARENT_THEME_NAME) {
    throw "ERROR: MIX_PARENT_THEME_NAME is not set in your .env file!";
}

let parentDirName = process.env.MIX_PARENT_THEME_NAME;
let parentDirPath = `./wp-content/themes/${parentDirName}`;

// JavaScript
mix.js(`${parentDirPath}/assets/js/load.js`, "./dist/js/main.js");

// Styles
mix.sass(
    `${parentDirPath}/assets/sass/load.scss`,
    "./dist/css/main.css"
).tailwind();

// Images
mix.copy(`${parentDirPath}/assets/images/**`, "./dist/images");

// Fonts
mix.copy(`${parentDirPath}/assets/fonts/**`, "./dist/fonts");

// Child
if (process.env.MIX_CHILD_THEME_NAME) {
    process.env.MIX_CHILD_THEME_NAME.split(",").forEach(function(
        childThemeName
    ) {
        let childDirPath = `./wp-content/themes/${childThemeName.trim()}`;

        // JavaScript
        let loadJsPath = `${childDirPath}/assets/js/load.js`;
        if (fs.existsSync(loadJsPath)) {
            mix.js(loadJsPath, "./dist/js/child.main.js");
        }

        // Styles
        let loadScssPath = `${childDirPath}/assets/sass/load.scss`;
        if (fs.existsSync(loadScssPath)) {
            mix.sass(loadScssPath, "./dist/css/child.main.css", {
                functions: {
                    "config($keys)": JsToSass
                }
            }).tailwind();
        }

        // Images
        let imagePath = `${childDirPath}/assets/images/**`;
        if (fs.existsSync(imagePath)) {
            mix.copy(imagePath, "./dist/images");
        }

        // Fonts
        let fontsPath = `${childDirPath}/assets/fonts/**`;
        if (fs.existsSync(fontsPath)) {
            mix.copy(fontsPath, "./dist/fonts");
        }
    });
}

// Configuration
mix.setPublicPath("./")
    .options({
        processCssUrls: false,
        uglify: {
            uglifyOptions: {
                mangle: {
                    safari10: true
                }
            }
        }
    })
    .webpackConfig({
        resolve: {
            modules: [
                path.resolve(__dirname, `${parentDirPath}assets/js/`),
                "node_modules"
            ]
        },
        node: {
            fs: "empty"
        }
    })
    .sourceMaps()
    .version()
    .browserSync({
        proxy: process.env.MIX_SITE_URL,
        files: ["*.php", "*.js", "*.css"],
        notify: {
            styles: {
                top: "auto",
                bottom: "0"
            }
        },
        injectChanges: true
    });

// Extract dependencies into vendor.js
if (Object.keys(pjson.dependencies).length > 0) {
    mix.extract(Object.keys(pjson.dependencies));
}

// Handle a things specific for production
if (mix.inProduction()) {
    mix.webpackConfig({
        plugins: [new PurgecssPlugin(purgecssConfig)]
    });
}

// Full API
// mix.js(src, output);
// mix.react(src, output); <-- Identical to mix.js(), but registers React Babel compilation.
// mix.ts(src, output); <-- Requires tsconfig.json to exist in the same folder as webpack.mix.js
// mix.extract(vendorLibs);
// mix.sass(src, output);
// mix.standaloneSass('src', output); <-- Faster, but isolated from Webpack.
// mix.fastSass('src', output); <-- Alias for mix.standaloneSass().
// mix.less(src, output);
// mix.stylus(src, output);
// mix.postCss(src, output, [require('postcss-some-plugin')()]);
// mix.browserSync('my-site.dev');
// mix.combine(files, destination);
// mix.babel(files, destination); <-- Identical to mix.combine(), but also includes Babel compilation.
// mix.copy(from, to);
// mix.copyDirectory(fromDir, toDir);
// mix.minify(file);
// mix.sourceMaps(); // Enable sourcemaps
// mix.version(); // Enable versioning.
// mix.disableNotifications();
// mix.setPublicPath('path/to/public');
// mix.setResourceRoot('prefix/for/resource/locators');
// mix.autoload({}); <-- Will be passed to Webpack's ProvidePlugin.
// mix.webpackConfig({}); <-- Override webpack.config.js, without editing the file directly.
// mix.babelConfig({}); <-- Merge extra Babel configuration (plugins, etc.) with Mix's default.
// mix.then(function () {}) <-- Will be triggered each time Webpack finishes building.
// mix.options({
//   extractVueStyles: false, // Extract .vue component styling to file, rather than inline.
//   processCssUrls: true, // Process/optimize relative stylesheet url()'s. Set to false, if you don't want them touched.
//   purifyCss: false, // Remove unused CSS selectors.
//   uglify: {}, // Uglify-specific options. https://webpack.github.io/docs/list-of-plugins.html#uglifyjsplugin
//   postCss: [] // Post-CSS options: https://github.com/postcss/postcss/blob/master/docs/plugins.md
// });
