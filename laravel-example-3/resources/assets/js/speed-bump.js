// let _foreach = require('lodash.foreach');

module.exports = function() {
    document.addEventListener('click', function(e) {
        let target = event.target || event.srcElement;

        // We only want links
        if (target.tagName.toLowerCase() !== 'a') {
            return;
        }

        // We only want external links
        let href = target.href.trim();
        if (href.indexOf(window.location.host) !== -1) {
            return;
        }

        // We also dont want to include empty and pound links
        if (href === '' || href.indexOf('#') === 0) {
            return;
        }

        // Allow for skipping the check
        if (target.classList.contains('js-speed-bump-ignore')) {
            return;
        }

        window.open(
            `${window.location.origin}/external?link=${encodeURIComponent(
                href
            )}`
        );
        e.preventDefault();
    });
};
