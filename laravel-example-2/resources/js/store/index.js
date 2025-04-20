import Vue from "vue";
import Vuex from "vuex";
import collect from 'collect.js';
import md5 from 'md5';

Vue.use(Vuex);

/**
 * Vuex State
 *
 * @see https://vuex.vuejs.org/guide/state.html
 */
const state = {
    machines: window.vms.machines ?JSON.parse(window.vms.machines) : [],
    jobFlags: window.vms.jobFlags ?JSON.parse(window.vms.jobFlags) : [],
    jobStatuses: window.vms.jobStatuses ?JSON.parse(window.vms.jobStatuses) : [],
    pickStatuses: window.vms.pickStatuses ?JSON.parse(window.vms.pickStatuses) : [],
    artStatuses: window.vms.artStatuses ?JSON.parse(window.vms.artStatuses) : [],
    wipStatuses: window.vms.wipStatuses ?JSON.parse(window.vms.wipStatuses) : {},
    alerts: collect()
};

/**
 * Vuex Getters
 *
 * @see https://vuex.vuejs.org/guide/getters.html
 */
const getters = {
    machinesForSelect(state) {
        return collect(state.machines).mapWithKeys((machine) => [machine.id, machine.name]).all();
    },

    jobFlagsForSelect(state) {
        return collect(state.jobFlags).mapWithKeys((status) => [status.key, status.name]).all();
    },

    jobStatusesForSelect(state) {
        return collect(state.jobStatuses).mapWithKeys((status) => [status.key, `${status.name} - Status`]).all();
    },

    pickStatusesForSelect(state) {
        return collect(state.pickStatuses).mapWithKeys((status) => [status.key, status.name]).all();
    },

    artStatusesForSelect(state) {
        return collect(state.artStatuses).mapWithKeys((status) => [status.key, status.name]).all();
    }
};

/**
 * Vuex Mutations
 *
 * @see https://vuex.vuejs.org/guide/mutations.html
 */
const mutations = {
    addAlert(state, payload) {
        if (!payload.message) {
            console.error('Alert message is required to add an alert');
            return;
        }

        let uuid = md5(payload.message);

        if (!state.alerts.where('uuid', uuid).isEmpty()) {
            state.alerts = state.alerts.whereNotIn('uuid', [uuid]);
        }

        state.alerts.prepend({
            dismissible: typeof payload.dismissible === 'undefined' ? true : !!payload.dismissible,
            type: payload.type ? payload.type : "info",
            timeout: typeof payload.timeout === 'undefined' ? 0 : payload.timeout,
            uuid: uuid,
            message: payload.message
        });
    },

    removeAlert(state, payload) {
        if (!payload.message) {
            console.error('Alert message is required to remove an alert');
            return;
        }

        payload.uuid = payload.uuid ? payload.uuid : md5(payload.message);
        state.alerts = state.alerts.whereNotIn('uuid', [payload.uuid]);
    }
};

/**
 * Vuex Actions
 *
 * @see https://vuex.vuejs.org/guide/actions.html
 */
const actions = {};

/**
 * Vuex Modules
 *
 * @see https://vuex.vuejs.org/guide/modules.html
 */
const modules = {};

/**
 * Vuex Plugins
 *
 * @see https://vuex.vuejs.org/guide/plugins.html
 */
const plugins = [];

const debug = process.env.NODE_ENV !== "production";

export default new Vuex.Store({
    state,
    getters,
    actions,
    mutations,
    modules,
    strict: debug,
    plugins
});
