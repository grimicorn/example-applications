const colors = require("tailwindcss/colors");

module.exports = {
    theme: {
        colors: {
            danger: colors.red[500],
            success: colors.green[500],
            info: colors.blue[500],
            warning: colors.yellow[500],
            transparent: "transparent",
            current: "currentColor",
            black: colors.black,
            white: colors.white,
            primary: colors.lightBlue,
            gray: colors.blueGray
        }
    },
    purge: {
        content: [
            "./resources/**/*.blade.php",
            "./resources/**/*.js",
            "./resources/**/*.vue"
        ]

        // These options are passed through directly to PurgeCSS
        // Tailwind JIT instead uses a safelist.txt
        // Though it is preferable to refactor towards having the full class name in a file instead of concatenation.
        // options: {
        //     safelist: ["bg-success", "bg-info", "bg-danger", "bg-warning"]
        // }
    },
    variants: {
        extend: {
            borderWidth: ["last"],
            padding: ["last"],
            margin: ["last"]
        }
    },
    plugins: [require("@tailwindcss/forms"), require("@tailwindcss/typography")]
};
