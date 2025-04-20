import Vue from "vue";
import store from "./store/index";
import PortalVue from 'portal-vue';
import axios from 'axios';
import laravelVuePagination from 'laravel-vue-pagination';

/**
 * Setup Axios.js for AJAX requests
 * @see https://github.com/axios/axios
 */

axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
} else {
    console.error(
        "CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token"
    );
}

Vue.prototype.$http = axios;

/**
 * Setup Portal Vue
 * @see https://linusborg.github.io/portal-vue/#/
 */
Vue.use(PortalVue);

/**
 * Add Laravel Vue Pagination component
 * @see https://github.com/gilbitron/laravel-vue-pagination
 */
Vue.component('laravel-vue-pagination', laravelVuePagination);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context("./", true, /\.vue$/i);
files.keys().map(key =>
    Vue.component(
        key
            .split("/")
            .pop()
            .split(".")[0],
        files(key).default
    )
);


/**
 * Setup Vue.js
 */

window.Vue = Vue;

new window.Vue({
    el: "#app",
    store
});
