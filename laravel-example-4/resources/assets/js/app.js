
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
var DefaultTaskDueDate = require('./components/DefaultTaskDueDate.vue');
const app = new Vue({
    el: '#app',
    components: {
        DefaultTaskDueDate,
    },
});

/**
 * Setup clip board JS.
 *
 * @see https://clipboardjs.com/
 */
require('./clipboard.js');
