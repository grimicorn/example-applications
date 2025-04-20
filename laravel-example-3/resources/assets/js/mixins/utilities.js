let moment = require('moment');
let _foreach = require('lodash.foreach');

module.exports = {
    methods: {
        listenClickOutside(callback, selectors = false) {
            let $elements = selectors
                ? document.querySelectorAll(selectors)
                : [document];

            if ($elements.length === 0) {
                return;
            }

            _foreach($elements, ($el) => {
                $el.addEventListener('click', (event) => {
                    if (!this.$el.contains(event.target)) {
                        callback();
                    }
                });
            });
        },

        windowIsDesktop() {
            return this.windowIsMinWidth(768);
        },

        windowIsMobile() {
            return !this.windowIsDesktop();
        },

        windowIsMinWidth(width) {
            return window.innerWidth >= width;
        },

        windowIsMaxWidth(width) {
            return window.innerWidth <= width;
        },

        slugify(text, seperator = '-') {
            return text
                .toString()
                .toLowerCase()
                .replace(/\s+/g, seperator) // Replace spaces with -
                .replace(/[^\w\-]+/g, '') // Remove all non-word chars
                .replace(/\-\-+/g, seperator) // Replace multiple - with single -
                .replace(/^-+/, '') // Trim - from start of text
                .replace(/-+$/, ''); // Trim - from end of text
        },

        formatDate(date, format = 'M/D/YYYY') {
            return moment(date).format(format);
        },

        formatDatetime(date, format = 'M/D/YYYY h:mm a') {
            return moment(date).format(format);
        },

        formatPrice(value) {
            if (value === null || typeof value === undefined) {
                return '';
            }

            return new Intl.NumberFormat(undefined, {
                style: 'currency',
                currency: 'USD',
                minimumFractionDigits: 0,
            }).format(parseFloat(value));
        },

        stringTrim(value, last_char, add_elipsis = false) {
            let new_string = value.substring(0, last_char);

            return add_elipsis ? new_string + '...' : new_string;
        },

        isEdgeBrowser() {
            return /Edge/.test(navigator.userAgent);
        },
    },
};
