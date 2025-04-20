let purgecssWordpress = require("purgecss-with-wordpress");
let _merge = require("lodash.merge");
let glob = require("glob-all");

/**
 * Custom PurgeCSS Extractor
 * https://github.com/FullHuman/purgecss
 * https://github.com/FullHuman/purgecss-webpack-plugin
 */
class TailwindExtractor {
    static extract(content) {
        return content.match(/[A-z0-9-:\/]+/g) || [];
    }
}

module.exports = {
    whitelist: _merge(purgecssWordpress.whitelist, [
        "alignnone",
        "aligncenter",
        "alignright",
        "alignleft",
        "wp-caption",
        "wp-caption-text",
        "screen-reader-text",
        "open",
        "active",
        "hidden-amount",
        "instruction",
        "textarea",
        "input",
        "select",
        "name_first",
        "name_last",
        "inner-container",
        "menu-item",
        "sub-menu",
        "item-toggle",
        "dropdown",
        "half",
        "third",
        "fourth",
    ]),
    whitelistPatterns: _merge(purgecssWordpress.whitelistPatterns, [
        /gform([^\s"]+)/,
        /gf([^\s"]+)/,
        /gfield([^\s"]+)/,
        /field([^\s"]+)/,
        /input([^\s"]+)/,
        /gchoice([^\s"]+)/,
        /ginput([^\s"]+)/,
        /address_([^\s"]+)/,
        /validation_([^\s"]+)/,
    ]),
    paths: glob.sync([
        path.join(__dirname, "wp-content/**/*.{js,vue,php,blade.php}"),
        path.join(__dirname, "!wp-content/uploads/**")
    ]),
    extractors: [
        {
            extractor: TailwindExtractor,
            extensions: ["html", "js", "php", "vue"]
        }
    ]
};
