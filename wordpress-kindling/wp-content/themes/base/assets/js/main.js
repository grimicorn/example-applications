import fitvids from "fitvids";
import toTop from "./to-top";
import fontawesome from "./vendor/fontawesome";
import modernizr from "./vendor/modernizr-custom";
import touch from "./touch";
import navigation from "./navigation";
import accordion from "./accordion";

window.jQuery = window.$ = jQuery;

jQuery(
    (function($) {
        $(document).ready(() => {
            /**
             * Setup Font Awesome icons
             * @see https://fontawesome.com/icons
             */
            fontawesome();

            /**
             * Setup Fivids
             * @see https://www.npmjs.com/package/fitvids
             */
            fitvids(".js-fitvid");

            /**
             * Setup back to top
             */
            toTop();

            /**
             * Setup navigation
             */
            navigation();

            /**
             * Setup accordions
             */
            accordion();

             /**
             * Setup modernizr for touch events
             */
            modernizr();
            /**
             * Setup modernizr for touch events
             */
            touch();
        });
    })(jQuery)
);
