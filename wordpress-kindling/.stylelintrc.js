module.exports = {
    plugins: ["stylelint-order", "stylelint-scss"],
    rules: {
        "at-rule-blacklist": ["debug"],
        "at-rule-no-vendor-prefix": true,
        "block-no-empty": true,
        "block-opening-brace-space-before": "always",
        "color-hex-case": "lower",
        "color-hex-length": "short",
        "color-named": "never",
        "color-no-invalid-hex": true,
        "declaration-bang-space-after": "never",
        "declaration-bang-space-before": "always",
        "declaration-block-semicolon-newline-after": "always",
        "declaration-block-semicolon-space-before": "never",
        "declaration-block-single-line-max-declarations": 1,
        "declaration-block-trailing-semicolon": "always",
        "declaration-colon-space-after": "always-single-line",
        "declaration-colon-space-before": "never",
        "declaration-property-value-blacklist": {
            "/^border/": ["none"]
        },
        "function-comma-space-after": "always-single-line",
        "function-parentheses-space-inside": "never",
        "function-url-quotes": "always",
        indentation: 2,
        "length-zero-no-unit": true,
        "max-nesting-depth": 1,
        "media-feature-name-no-vendor-prefix": true,
        "media-feature-parentheses-space-inside": "never",
        "no-missing-end-of-source-newline": true,
        "number-leading-zero": "always",
        "number-no-trailing-zeros": true,
        "order/properties-alphabetical-order": true,
        "property-no-unknown": true,
        "property-no-vendor-prefix": true,
        "rule-empty-line-before": [
            "always-multi-line",
            {
                except: ["first-nested"],
                ignore: ["after-comment"]
            }
        ],
        "scss/at-extend-no-missing-placeholder": true,
        "scss/at-function-pattern": "^[a-z]+([a-z0-9-]+[a-z0-9]+)?$",
        "scss/at-import-no-partial-leading-underscore": true,
        "scss/at-import-partial-extension-blacklist": ["scss"],
        "scss/at-mixin-pattern": "^[a-z]+([a-z0-9-]+[a-z0-9]+)?$",
        "scss/dollar-variable-colon-space-after": "always",
        "scss/dollar-variable-colon-space-before": "never",
        "scss/dollar-variable-pattern": "^[_]?[a-z]+([a-z0-9-]+[a-z0-9]+)?$",
        "scss/percent-placeholder-pattern": "^[a-z]+([a-z0-9-]+[a-z0-9]+)?$",
        "scss/selector-no-redundant-nesting-selector": true,
        "selector-class-pattern": [
            "^[a-z0-9\\-]+$",
            {
                message:
                    "Selector should be written in lowercase with hyphens (selector-class-pattern)"
            }
        ],
        "selector-list-comma-newline-after": "always",
        "selector-max-compound-selectors": 3,
        "selector-max-id": 0,
        "selector-no-qualifying-type": true,
        "selector-no-vendor-prefix": true,
        "selector-pseudo-element-colon-notation": "double",
        "selector-pseudo-element-no-unknown": true,
        "shorthand-property-no-redundant-values": true,
        "string-quotes": "single",
        "value-no-vendor-prefix": true,
        "at-rule-no-unknown": [
            true,
            {
                ignoreAtRules: [
                    "extend",
                    "at-root",
                    "debug",
                    "warn",
                    "error",
                    "if",
                    "else",
                    "for",
                    "each",
                    "while",
                    "mixin",
                    "include",
                    "content",
                    "return",
                    "function",
                    "tailwind",
                    "screen",
                    "config",
                    "apply"
                ]
            }
        ]
    }
};
