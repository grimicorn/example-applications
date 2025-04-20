import { collect } from "collect.js";

const collectErrors = (errors) => {
    return collect(errors).map((error) => {
        error = collect(error);
        return error;
    });
};

export default {
    state: {
        user: window.Domain.user,
        locationTags: window.Domain.locationTags,
        locationIcons: window.Domain.locationIcons,
        timesOfYearToVisit: window.Domain.timesOfYearToVisit,
        timesOfDayToVisit: window.Domain.timesOfDayToVisit,
        locationTrafficLevels: collect(window.Domain.locationTrafficLevels),
        locationAccessDifficulties: collect(
            window.Domain.locationAccessDifficulties
        ),
        old: collect(window.Domain.forms.old),
        errors: collectErrors(window.Domain.forms.errors),
        globalAlerts: window.Domain.globalAlerts ?? [],
    },
    mutations: {
        setUserLocation(state, location) {
            state.user.currentLocation = location;
        },

        setUserSearchAddressDistance(state, distance) {
            distance = distance === null ? undefined : distance;
            state.user.searchAddressDistance = distance;
        },

        addGlobalAlert(state, alert) {
            state.globalAlerts.push(alert);
        },

        setErrors(state, errors) {
            state.errors = errors ? collectErrors(errors) : collect({});
        },
    },
};
