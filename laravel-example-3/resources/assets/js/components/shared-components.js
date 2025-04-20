module.exports = function() {
    /**
     * Generic components
     */
    window.Vue.component('alert', require('./shared/alert.vue'));
    window.Vue.component('alerts', require('./shared/alerts.vue'));
    window.Vue.component('loader', require('./shared/loader.vue'));
    window.Vue.component(
        'model-pagination',
        require('./shared/model-pagination.vue')
    );
    window.Vue.component('avatar', require('./shared/avatar.vue'));
    window.Vue.component('user-avatar', require('./shared/user-avatar.vue'));
    window.Vue.component('modal', require('./shared/modal.vue'));
    window.Vue.component('lightbox', require('./shared/lightbox.vue'));
    window.Vue.component(
        'lightbox-slider',
        require('./shared/lightbox-slider.vue')
    );
    window.Vue.component(
        'favorite-listing-link',
        require('./shared/favorite-listing-link.vue')
    );
    window.Vue.component('tool-tip', require('./shared/tool-tip.vue'));
    window.Vue.component('print-page', require('./shared/print-page.vue'));
    window.Vue.component(
        'timezone-date',
        require('./shared/timezone-date.vue')
    );
    window.Vue.component(
        'timezone-date-range',
        require('./shared/timezone-date-range.vue')
    );
    window.Vue.component(
        'timezone-datetime',
        require('./shared/timezone-datetime.vue')
    );
    window.Vue.component(
        'share-page',
        require('./../components/shared/share-page.vue')
    );
    window.Vue.component(
        'requires-auth-modal',
        require('./../components/shared/requires-auth-modal.vue')
    );

    window.Vue.component(
        'slider',
        require('./../components/shared/slider.vue')
    );
    window.Vue.component(
        'overlay-tour-final-step',
        require('./../components/shared/overlay-tour-final-step.vue')
    );

    window.Vue.component(
        'overlay-tour-wrap',
        require('./../components/shared/overlay-tour-wrap.vue')
    );

    window.Vue.component(
        'sticky',
        require('./../components/shared/sticky.vue')
    );

    /**
     * Form structure components
     */
    window.Vue.component('fe-form', require('./forms/form.vue'));
    window.Vue.component(
        'input-maxlength',
        require('./forms/input-maxlength.vue')
    );
    window.Vue.component('input-label', require('./forms/input-label.vue'));
    window.Vue.component(
        'input-repeater',
        require('./forms/input-repeater.vue')
    );
    window.Vue.component(
        'input-error-message',
        require('./forms/input-error-message.vue')
    );
    window.Vue.component(
        'submit-confirm-challenge',
        require('./../components/forms/submit-confirm-challenge.vue')
    );

    /**
     * Textual type inputs
     */
    window.Vue.component('input-textual', require('./forms/input-textual.vue'));
    window.Vue.component('input-hidden', require('./forms/input-hidden.vue'));
    window.Vue.component('input-search', require('./forms/input-search.vue'));
    window.Vue.component(
        'input-file-single-image',
        require('./forms/input-file-single-image.vue')
    );

    /**
     * Selection type inputs (checkbox/radio/select).
     */
    window.Vue.component('input-radio', require('./forms/input-radio.vue'));
    window.Vue.component(
        'input-checkbox',
        require('./forms/input-checkbox.vue')
    );
    window.Vue.component(
        'input-multi-checkbox',
        require('./forms/input-multi-checkbox.vue')
    );
    window.Vue.component('input-select', require('./forms/input-select.vue'));
    window.Vue.component('input-toggle', require('./forms/input-toggle.vue'));
    window.Vue.component(
        'input-business-category',
        require('./forms/input-business-category.vue')
    );
    window.Vue.component('input-tags', require('./forms/input-tags.vue'));
    window.Vue.component(
        'input-business-category-select',
        require('./forms/input-business-category-select.vue')
    );

    /**
     * Custom input components.
     */
    window.Vue.component(
        'input-locations-repeater',
        require('./forms/input-locations-repeater.vue')
    );
    window.Vue.component(
        'input-datepicker',
        require('./forms/input-datepicker.vue')
    );
    window.Vue.component('input-boolean', require('./forms/input-boolean.vue'));
    window.Vue.component(
        'input-multi-file-upload',
        require('./forms/input-multi-file-upload.vue')
    );
    window.Vue.component('input-range', require('./forms/input-range.vue'));
    window.Vue.component(
        'input-min-max-price',
        require('./forms/input-min-max-price.vue')
    );
    window.Vue.component(
        'input-credit-card-expiration',
        require('./forms/credit-card-expiration.vue')
    );
    window.Vue.component('input-rating', require('./forms/input-rating.vue'));

    /**
     * Listing Components
     */
    window.Vue.component(
        'app-listing-section-completion-bar',
        require('./../components/app/listings/section-completion-bar.vue')
    );
    window.Vue.component(
        'lc-rating',
        require('./../components/shared/lc-rating.vue')
    );
    window.Vue.component(
        'lc-rating-tooltip',
        require('./../components/shared/lc-rating-tooltip.vue')
    );
    window.Vue.component(
        'app-listing-cards',
        require('./../components/app/listings/listing-cards.vue')
    );
    window.Vue.component(
        'app-listing-card',
        require('./../components/app/listings/listing-card.vue')
    );

    window.Vue.component(
        'app-file-preview-link',
        require('./../components/app/file-preview-link.vue')
    );

    /**
     * Exchange Space
     */
    window.Vue.component(
        'app-create-inquiry',
        require('./../components/app/exchange-space/create-inquiry.vue')
    );

    /**
     * Confirmation
     */
    window.Vue.component(
        'confirm',
        require('./../components/shared/confirm.vue')
    );
};
