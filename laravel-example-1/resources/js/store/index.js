import { createStore } from "vuex";

import all from "./all";
import locationsIndex from "./locations-index";

export default createStore({
    state: {
        ...all.state,
        ...locationsIndex.state
    },

    mutations: {
        ...all.mutations,
        ...locationsIndex.mutations
    }
});
