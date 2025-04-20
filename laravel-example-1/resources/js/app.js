import axios from "axios";
import { createApp } from "vue";
import store from "./store";

/**
 * Axios Setup
 */
window.axios = axios;
window.axios.defaults.headers.common = {
    "X-Requested-With": "XMLHttpRequest",
    "X-CSRF-TOKEN": document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),
};

window.axios.interceptors.response.use(
    (response) => {
        if (response.data && response.data.success_message) {
            store.commit("addGlobalAlert", {
                message: response.data.success_message,
                timeout: 3000,
                type: "success",
            });
        }

        return response;
    },
    (error) => {
        if (process.env.NODE_ENV === "development") {
            console.error(error);
        }

        if (error.response && error.response.status === 422) {
            store.commit("addGlobalAlert", {
                message: error.response.data.message,
                timeout: 5000,
                type: "danger",
            });
            store.commit("setErrors", error.response.data.errors);
        } else {
            store.commit("addGlobalAlert", {
                message: "Something went wrong please try again.",
                timeout: 5000,
                type: "danger",
            });
        }

        throw error;
    }
);

/**
 * Vue setup
 */
const app = createApp({}).use(store);
app.config.globalProperties.$http = axios;
app.config.globalProperties.$route = window.route;

app.config.globalProperties.devtools = true;

/**
 * Vue Components
 */
const files = require.context("./", true, /\.vue$/i);
files.keys().map((key) => {
    const componentName = key.split("/").pop().split(".")[0];

    // Pre-pending the file name with an underscore (_) will allow sub components to exist and not be auto loaded globally.
    if (componentName.indexOf("_") === 0) {
        return;
    }
    return app.component(componentName, files(key).default);
});

app.mount("#app");
