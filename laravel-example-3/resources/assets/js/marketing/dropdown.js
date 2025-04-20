let $ = require('jquery');
let dropdown = {};

dropdown.isMobile = function() {
    let $toggle = $('.js-dropdown-item-toggle');

    // On mobile the button would be visible.
    if ($toggle.length !== 0 && $toggle.css('display') === 'none') {
        return false;
    }

    return true;
};

dropdown.reset = function() {
    if (!dropdown.isMobile()) {
        $('.dropdown.open').removeClass('open');
    }
};

/**
 * Initializes mobile navigation.
 */
module.exports = function() {
    $(window).resize(dropdown.reset);

    $('.js-dropdown-item-toggle').on('click', function() {
        $(this)
            .parent('li.dropdown')
            .toggleClass('open');
    });
};
