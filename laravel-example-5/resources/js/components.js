import Pagination from "laravel-vue-pagination";

export default function(Vue) {
    /**
     * Site components
     */
    const req = require.context("./components/", true, /\.(js|vue)$/i);
    req.keys().map(key => {
        const name = key.match(/\w+/)[0];
        return Vue.component(name, req(key));
    });

    /**
     * Package components
     */
    Vue.component("pagination", Pagination);
}
