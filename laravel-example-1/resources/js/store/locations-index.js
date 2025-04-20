import getFromQueryString from "@/libs/get-from-query-string";
import _set from "lodash.set";
const globalMoreInfoOpenStorageKey = "locations-index.global-more-info-open";

const formStorageKey = "locations-index.form";
let storageForm = {
    filter: {},
    sort: "distance",
};
if (window.localStorage.getItem(formStorageKey)) {
    try {
        storageForm = {
            ...storageForm,
            ...JSON.parse(window.localStorage.getItem(formStorageKey)),
        };
    } catch (error) {
        console.log(error);
    }
}

export default {
    state: {
        locationsLoading: true,
        paginatedLocations: window.Domain.paginatedLocations
            ? window.Domain.paginatedLocations
            : {},
        centerCoordinates: window.Domain.centerCoordinates
            ? window.Domain.centerCoordinates
            : {},
        searchLocations: window.Domain.searchLocations
            ? window.Domain.searchLocations
            : [],
        locationsIndexForm: {
            address: getFromQueryString("address", storageForm.address),
            filter: {
                max_distance: getFromQueryString(
                    "filter[max_distance]",
                    storageForm.filter.max_distance
                ),
                min_rating: getFromQueryString(
                    "filter[min_rating]",
                    storageForm.filter.min_rating
                ),
                tags: getFromQueryString(
                    "filter[tags][]",
                    storageForm.filter.tags ?? []
                ),
                visited: getFromQueryString(
                    "filter[visited]",
                    storageForm.filter.visited
                ),
            },
            per_page: getFromQueryString(
                "per_page",
                storageForm.per_page ?? 15
            ),
            page: getFromQueryString("page", storageForm.page ?? 1),
            sort: getFromQueryString("sort", storageForm.sort),
            search_by: getFromQueryString(
                "search_by",
                storageForm.search_by ?? "address"
            ),
            search: getFromQueryString("search", storageForm.search),
            map: getFromQueryString("map", storageForm.map ?? "0"),
        },
        globalMoreInfoOpen:
            window.localStorage.getItem(globalMoreInfoOpenStorageKey) ===
            "true",
    },
    mutations: {
        updateLocationsLoading(state, loading) {
            state.locationsLoading = loading;
        },
        updateLocationsIndexFormField(state, { name, value }) {
            const path = name.replace(/\]/g, "").split("[");
            _set(state.locationsIndexForm, path, value);

            window.localStorage.setItem(
                formStorageKey,
                JSON.stringify(state.locationsIndexForm)
            );
        },
        resetLocationsIndexForm(state) {
            state.locationsIndexForm = {
                address: undefined,
                filter: {
                    max_distance: undefined,
                    min_rating: undefined,
                    tags: [],
                    visited: undefined,
                },
                per_page: 15,
                sort: "distance",
                map: "0",
                search_by: "address",
                search: "",
            };

            window.localStorage.setItem(
                formStorageKey,
                JSON.stringify(state.locationsIndexForm)
            );
        },
        updatePaginatedLocations(state, paginatedLocations) {
            state.paginatedLocations = paginatedLocations;
        },

        addSearchLocation(state, location) {
            state.searchLocations.push(location);
        },

        setglobalMoreInfoOpen(state, value) {
            value = !!value;
            window.localStorage.setItem(globalMoreInfoOpenStorageKey, value);
            state.globalMoreInfoOpen = value;
        },
    },
};
