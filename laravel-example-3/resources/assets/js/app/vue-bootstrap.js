/*
 * Load Vue.
 *
 * Vue is the JavaScript framework used by Spark.
 */
if (window.Vue === undefined) {
    window.Vue = require('vue');
    window.Bus = new Vue();
}

/**
 * Add alert flash message helper
 */
window.flashAlert = require('./../flash-alert.js');

/**
 * Add alert clear message helper
 */
let clearAlert = require('./../clear-alert.js');
window.clearAlert = clearAlert.individual;
window.clearAlertAll = clearAlert.all;

/**
 * Load Vue Dragula (Drag/Drop reorder)
 *
 * @see https://github.com/kristianmandrup/vue2-dragula
 */
import {Vue2Dragula} from 'vue2-dragula';
window.Vue.use(Vue2Dragula, {});

/**
 * Load Vee Validate (Form validation)
 *
 * @see http://vee-validate.logaretm.com/
 */
require('./../vee-validate.js')();

/**
 * Load Vue Agile (Slider)
 *
 * @see https://github.com/lukaszflorczak/vue-agile
 */
import VueAgile from 'vue-agile';
window.Vue.use(VueAgile);

let sparkBase = require('auth/register-stripe');

// Register shared components.
let sharedComponents = require('./../components/shared-components.js');
sharedComponents();

// General components.
window.Vue.component(
    'app-ways-to-get-started',
    require('./../components/app/ways-to-get-started.vue')
);
window.Vue.component(
    'app-sidebar-navigation',
    require('./../components/app/sidebar-navigation.vue')
);
window.Vue.component(
    'app-form-accordion',
    require('./../components/app/form-accordion.vue')
);
window.Vue.component(
    'app-navbar-dropdown',
    require('./../components/app/navbar-dropdown.vue')
);
window.Vue.component(
    'app-edit-subtitle',
    require('./../components/app/edit-subtitle.vue')
);

window.Vue.component(
    'app-sticky-form-buttons',
    require('./../components/app/sticky-form-buttons.vue')
);
window.Vue.component(
    'app-teleport-click-button',
    require('./../components/app/teleport-click-button.vue')
);

// File upload
window.Vue.component(
    'app-document-file-upload',
    require('./../components/app/file-upload/document-file-upload.vue')
);
window.Vue.component(
    'app-file-upload-submission-inputs',
    require('./../components/app/file-upload/submission-inputs.vue')
);
window.Vue.component(
    'app-document-list',
    require('./../components/app/file-upload/document-file-list.vue')
);
window.Vue.component(
    'app-notification-accordion',
    require('./../components/app/notifications/notification-accordion.vue')
);
window.Vue.component(
    'app-notification-messages',
    require('./../components/app/notifications/messages.vue')
);
window.Vue.component(
    'app-notification-message',
    require('./../components/app/notifications/message.vue')
);
window.Vue.component(
    'app-notification-view-all',
    require('./../components/app/notifications/view-all.vue')
);

// User table components.
window.Vue.component(
    'app-sort-table',
    require('./../components/app/sort-table/table.vue')
);
window.Vue.component(
    'app-sort-table-search',
    require('./../components/app/sort-table/search.vue')
);
window.Vue.component(
    'app-sort-table-export-button',
    require('./../components/app/sort-table/export-button.vue')
);
window.Vue.component(
    'app-sort-table-order-icons',
    require('./../components/app/sort-table/order-icons.vue')
);

// Profile components
window.Vue.component(
    'app-profile-edit-business-types',
    require('./../components/app/profile/edit-business-types.vue')
);
window.Vue.component(
    'app-profile-settings-update-password',
    require('./../components/app/profile/settings-update-password.vue')
);
window.Vue.component(
    'app-profile-account-balance',
    require('./../components/app/profile/account-balance.vue')
);

window.Vue.component(
    'app-profile-cancel-subscription',
    require('./../components/app/profile/cancel-subscription.vue')
);
window.Vue.component(
    'app-profile-desired-purchase-criteria',
    require('./../components/app/profile/desired-purchase-criteria.vue')
);
window.Vue.component(
    'app-profile-due-dilligence-notifications',
    require('./../components/app/profile/due-dilligence-notifications.vue')
);
window.Vue.component(
    'app-profile-blog-post-notifications',
    require('./../components/app/profile/blog-post-notifications.vue')
);
window.Vue.component(
    'app-profile-all-notifications',
    require('./../components/app/profile/all-notifications.vue')
);
window.Vue.component(
    'app-profile-close',
    require('./../components/app/profile/close-account.vue')
);
window.Vue.component(
    'app-profile-enable-two-factor-form',
    require('./../components/app/profile/enable-two-factor-form.vue')
);

// Listing components.
window.Vue.component(
    'app-listing-basics-accordion',
    require('./../components/app/listings/basics-accordion.vue')
);
window.Vue.component(
    'app-listing-more-about-the-business-accordion',
    require('./../components/app/listings/more-about-the-business-accordion.vue')
);
window.Vue.component(
    'app-listing-financial-details-accordion',
    require('./../components/app/listings/financial-details-accordion.vue')
);
window.Vue.component(
    'app-listing-details-of-the-business-accordion',
    require('./../components/app/listings/details-of-the-business-accordion.vue')
);
window.Vue.component(
    'app-listing-upload-documents-accordion',
    require('./../components/app/listings/upload-documents/accordion.vue')
);
window.Vue.component(
    'app-listing-publish-modal',
    require('./../components/app/listings/publish/publish-modal.vue')
);
window.Vue.component(
    'app-listing-publish-payment-form',
    require('./../components/app/listings/publish/payment-form.vue')
);
window.Vue.component(
    'app-listing-publish-pricing-options',
    require('./../components/app/listings/publish/pricing-options.vue')
);
window.Vue.component(
    'app-listing-publish-pricing-option',
    require('./../components/app/listings/publish/pricing-option.vue')
);
window.Vue.component(
    'app-listing-publish-confirmation',
    require('./../components/app/listings/publish/publish-confirmation.vue')
);
window.Vue.component(
    'marketing-button-toggle-show-hide',
    require('./../components/marketing/button-toggle-show-hide.vue')
);
window.Vue.component(
    'app-historical-financials-stale-data-alert',
    require('./../components/app/listings/historical-financials/stale-data-alert.vue')
);
window.Vue.component(
    'app-listing-exit-survey-modal',
    require('./../components/app/listings/exit-survey/exit-survey-modal.vue')
);
window.Vue.component(
    'app-listing-exit-survey-business-not-sold',
    require('./../components/app/listings/exit-survey/exit-survey-business-not-sold.vue')
);
window.Vue.component(
    'app-listing-exit-survey-business-sold',
    require('./../components/app/listings/exit-survey/exit-survey-business-sold.vue')
);
window.Vue.component(
    'app-listing-edit-button',
    require('./../components/app/listings/listing-edit-button.vue')
);
window.Vue.component(
    'app-listing-publish-status-label',
    require('./../components/app/listings/publish-status-label.vue')
);
window.Vue.component(
    'app-listing-publish-status-message',
    require('./../components/app/listings/publish-status-message.vue')
);

/**
 * Admin
 */
window.Vue.component(
    'app-admin-delete-user',
    require('./../components/app/admin/delete-user.vue')
);

/**
 * Listing - Historical Financials
 */
window.Vue.component(
    'app-historical-financial-input-yearly-totals',
    require('./../components/app/listings/historical-financials/input-yearly-totals.vue')
);

window.Vue.component(
    'app-historical-financial-input-total-labels',
    require('./../components/app/listings/historical-financials/input-total-labels.vue')
);

window.Vue.component(
    'app-historical-financial-input-totals',
    require('./../components/app/listings/historical-financials/input-totals.vue')
);

window.Vue.component(
    'app-historical-financial-input-totals-repeater',
    require('./../components/app/listings/historical-financials/input-totals-repeater.vue')
);

window.Vue.component(
    'app-historical-financial-period-selection',
    require('./../components/app/listings/historical-financials/period-selection.vue')
);

window.Vue.component(
    'app-historical-financial-edit',
    require('./../components/app/listings/historical-financials/historical-financials-edit.vue')
);

// Favorite components
window.Vue.component(
    'app-favorite-cards',
    require('./../components/app/favorites/favorite-cards.vue')
);

// Exchange Space components.
window.Vue.component(
    'app-exchange-space-add-to-dashboard',
    require('./../components/app/exchange-space/add-to-dashboard.vue')
);
window.Vue.component(
    'app-exchange-space-deal-status',
    require('./../components/app/exchange-space/deal-status.vue')
);
window.Vue.component(
    'app-exchange-space-toggle-access',
    require('./../components/app/exchange-space/toggle-access.vue')
);
window.Vue.component(
    'app-exchange-space-accept-inquiry',
    require('./../components/app/exchange-space/accept-inquiry.vue')
);
window.Vue.component(
    'app-exchange-space-reject-inquiry',
    require('./../components/app/exchange-space/reject-inquiry.vue')
);
window.Vue.component(
    'app-exchange-space-inquiry-welcome',
    require('./../components/app/exchange-space/inquiry-welcome.vue')
);
window.Vue.component(
    'app-exchange-space-conversation-filter-card',
    require('./../components/app/exchange-space/conversation-filter-card.vue')
);
window.Vue.component(
    'app-exchange-space-conversation-filters',
    require('./../components/app/exchange-space/conversation-filters.vue')
);
window.Vue.component(
    'app-exchange-space-conversation-filter',
    require('./../components/app/exchange-space/conversation-filter.vue')
);
window.Vue.component(
    'app-exchange-space-add-to-dashboard',
    require('./../components/app/exchange-space/add-to-dashboard.vue')
);
window.Vue.component(
    'app-exchange-space-deal-status',
    require('./../components/app/exchange-space/deal-status.vue')
);
window.Vue.component(
    'app-exchange-space-unresolved-conversation-count',
    // eslint-disable-next-line
    require('./../components/app/exchange-space/unresolved-conversation-count.vue')
);
window.Vue.component(
    'app-exchange-space-new-member',
    require('./../components/app/exchange-space/new-member.vue')
);
window.Vue.component(
    'app-exchange-space-show-members',
    require('./../components/app/exchange-space/show-members.vue')
);
window.Vue.component(
    'app-exchange-space-show-member',
    require('./../components/app/exchange-space/show-member.vue')
);
window.Vue.component(
    'app-exchange-space-remove-member',
    require('./../components/app/exchange-space/remove-member.vue')
);
window.Vue.component(
    'app-exchange-space-documents-list',
    require('./../components/app/exchange-space/documents-list.vue')
);
window.Vue.component(
    'app-exchange-space-sort-table',
    require('./../components/app/exchange-space/sort-table.vue')
);
window.Vue.component(
    'app-exchange-space-leave-modal',
    require('./../components/app/exchange-space/leave-modal.vue')
);
window.Vue.component(
    'app-exchange-space-delete-modal',
    require('./../components/app/exchange-space/delete-modal.vue')
);

window.Vue.component(
    'app-exchange-space-delete-message',
    require('./../components/app/exchange-space/delete-message.vue')
);

// DB Model components
window.Vue.component(
    'app-model-delete-button',
    require('./../components/app/model-delete-button.vue')
);
window.Vue.component(
    'app-model-preview-button',
    require('./../components/app/model-preview-button.vue')
);
window.Vue.component(
    'app-model-preview-close',
    require('./../components/app/model-preview-close.vue')
);
window.Vue.component(
    'app-model-save-button',
    require('./../components/app/model-save-button.vue')
);

/**
 * Watchlist
 */
window.Vue.component(
    'app-watchlist-form',
    require('./../components/app/watchlist/watchlist-form.vue')
);

/**
 * Styleguide
 */
window.Vue.component(
    'styleguide-confirmation-example',
    require('./../components/app/styleguide/confirmation-example.vue')
);

window.Vue.component(
    'styleguide-form-example',
    require('./../components/app/styleguide/form-example.vue')
);

window.Vue.component(
    'styleguide-search-example',
    require('./../components/app/styleguide/search-example.vue')
);

let app = new Vue({
    mixins: [require('spark'), require('./../mixins/filters.js')],

    components: {
        'spark-register-stripe': {
            mixins: [sparkBase],
        },
    },
});
