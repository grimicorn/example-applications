/*
 * Load Vue & Vue-Resource.
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

/**
 * Add marketing speed bump
 */
require('./../speed-bump')();

// Register shared components.
require('./../components/shared-components.js')();

window.Vue.component(
    'marketing-feature-switcher',
    require('./../components/marketing/feature-switcher.vue')
);
window.Vue.component(
    'marketing-search-sort-filter',
    require('./../components/marketing/search-sort-filter.vue')
);
window.Vue.component(
    'marketing-search-per-page-filter',
    require('./../components/marketing/search-per-page-filter.vue')
);
window.Vue.component(
    'marketing-search-sort-inputs',
    require('./../components/marketing/search-sort-inputs.vue')
);
window.Vue.component(
    'marketing-button-toggle-show-hide',
    require('./../components/marketing/button-toggle-show-hide.vue')
);
window.Vue.component(
    'marketing-listing-search-bar',
    require('./../components/marketing/listing-search-bar.vue')
);
window.Vue.component(
    'marketing-saved-search-modal',
    require('./../components/marketing/listing-save-search-modal.vue')
);
window.Vue.component(
    'marketing-advanced-search-modal',
    require('./../components/marketing/listing-advanced-search-modal.vue')
);
window.Vue.component(
    'marketing-auto-submit-search',
    require('./../components/marketing/auto-submit-search.vue')
);

window.Vue.component(
    'marketing-register-submit-tracking-button',
    require('./../components/marketing/register-submit-tracking-button.vue')
);

window.Vue.component(
    'marketing-facebook-tracking-submit',
    require('./../components/marketing/facebook-tracking-submit.vue')
);

// Setup the application
let app = new Vue({
    el: '#app',

    components: {},
});
