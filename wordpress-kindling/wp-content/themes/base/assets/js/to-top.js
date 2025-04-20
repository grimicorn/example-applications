import _merge from "lodash.merge";
import debounce from "./debounce";

let toTop = {
    $to: undefined,
    $from: undefined,
    debounce: debounce.new(),
    config: {},

    mergeConfig(config) {
        return _merge(
            {
                from: ".js-to-top",
                to: "body",
                duration: 1000,
                topOffset: $(window).outerHeight() * 0.5,
                easing: "swing"
            },
            config
        );
    },

    toggleShow() {
        if ($(document).scrollTop() > toTop.config.topOffset) {
            toTop.$from.addClass("scrolled");
        } else {
            toTop.$from.removeClass("scrolled");
        }
    },

    scroll() {
        if (toTop.$to.length === 0) {
            return;
        }

        $("html, body").animate(
            {
                scrollTop: toTop.$to.offset().top
            },
            {
                duration: toTop.config.duration,
                easing: toTop.config.easing
            }
        );
    },

    init(config) {
        toTop.config = toTop.mergeConfig(config);
        toTop.$from = $(toTop.config.from);
        toTop.$to = $(toTop.config.to);

        if (toTop.$from.length === 0 || toTop.$to.length === 0) {
            return;
        }

        toTop.$from.on("click", e => {
            e.preventDefault();
            toTop.scroll();
            return false;
        });

        // Handle showing/hidding of the $from element
        toTop.toggleShow();
        $(document).scroll(function() {
            toTop.debounce.set(() => {
                toTop.toggleShow();
            }, 500);
        });
    }
};

export default toTop.init;
