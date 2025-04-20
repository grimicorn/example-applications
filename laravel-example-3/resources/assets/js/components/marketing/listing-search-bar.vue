<template>
    <div class="listing-search mb3">
        <fe-form
        :form-id="formId"
        action="/businesses/search/results"
        method="POST"
        class="listing-search-form flex-auto mr0-m mb0 mb2-m"
        :remove-submit="true"
        :disabled-unload="true">
            <div
            v-if="enableRefineSearch"
            class="flex items-center">
                <strong class="text-uppercase fc-color4 mr2">Refine Your Search:&nbsp;</strong>
                <marketing-advanced-search-modal
                data-button-class="btn-color5 btn-horizontal-input btn-link mr2"></marketing-advanced-search-modal>
            </div>

            <div class="listing-search-form-inputs bg-color5">
                <div class="clearfix mb2 mx-width-100p">
                    <div class="col-sm-5">
                        <!-- Business Category -->
                        <input-business-category-select
                        data-count-text-color-class="fc-color2"
                        label="Business Category"
                        name="business_categories[]"
                        :value="search.business_categories"
                        wrap-class="mb0 color1"
                        @select-change="businessCategoryChange"></input-business-category-select>
                    </div>
                    <div
                    v-if="!loggedIn"
                        class="col-sm-7">
                        <!-- Keyword -->
                        <input-textual
                        label="Keyword"
                        name="keyword"
                        :value="search.keyword"
                        placeholder="e.g. city, business characteristic, etc."
                        wrap-class="mb0"
                        @change="valueChange"></input-textual>
                    </div>
                    <div
                    v-else-if="!displaySavedSearches"
                        class="col-sm-7">
                        <!-- Keyword -->
                        <input-textual
                        label="Keyword"
                        name="keyword"
                        :value="search.keyword"
                        placeholder="e.g. city, business characteristic, etc."
                        wrap-class="mb0"
                        @change="valueChange"></input-textual>
                    </div>
                    <div
                    v-else
                        class="col-sm-4">
                        <!-- Keyword -->
                        <input-textual
                        label="Keyword"
                        name="keyword"
                        :value="search.keyword"
                        placeholder="e.g. city, business characteristic, etc."
                        wrap-class="mb0"
                        @change="valueChange"></input-textual>
                    </div>
                    <div
                    v-if="displaySavedSearches"
                    class="col-sm-3">
                        <input-select
                        name="saved_search_id"
                        label="Saved Searches"
                        :value="selectedSearchId"
                        :options="savedSearchOptions"
                        placeholder="Saved Search"
                        wrap-class="mr2 mb0 nowrap wrap-m listing-saved-search-select"
                        @change="savedSearchIdChange"></input-select>
                    </div>
                </div>
                <div class="clearfix mx-width-100p">
                    <div class="col-sm-2">
                        <!-- Zipcode -->
                        <input-textual
                        name="zip_code"
                        label="Zip Code"
                        v-model="search.zip_code"
                        placeholder="Zip Code"
                        wrap-class="mr2 mr0-m mb0 mb2-m"
                        validation="numeric"
                        @change="valueChange"></input-textual>
                    </div>
                    <div class="col-sm-3">
                        <!-- Miles from Zip Code -->
                        <input-range
                        label="Miles from Zip Code"
                        name="zip_code_radius"
                        :min-range="0"
                        :max-range="500"
                        v-model="search.zip_code_radius"
                        wrap-class="mr2 mr0-m mb0 mb2-m"
                        @change="valueChange">
                            <template
                            slot="status-message"
                            scope="props">
                                Distance: {{props.value}} Miles
                            </template>
                        </input-range>

                    </div>

                    <div class="col-sm-5">
                        <!-- Asking Price (Number {min} to {max}) -->
                        <input-min-max-price
                        label="Asking Price"
                        name="asking_price"
                        class="mr2 mr0-m mb0"
                        :min-value="search.asking_price_min"
                        :max-value="search.asking_price_max"
                        @min-change="valueChange"
                        @max-change="valueChange"
                        formId="listing-search-form"></input-min-max-price>
                    </div>

                    <div class="col-sm-2">
                        <div class="filter-buttons">
                            <button
                            type="submit"
                            class="btn btn-color4 btn-horizontal-input mx-width-100p pl1 pr1">submit</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Advanced Inputs -->
            <input name="state" :value="search.state" type="hidden">
            <input name="listing_updated" :value="search.listing_updated" type="hidden">
            <input name="revenue_min" :value="search.revenue_min" type="hidden">
            <input name="revenue_max" :value="search.revenue_max" type="hidden">
            <input name="ebitda_min" :value="search.ebitda_min" type="hidden">
            <input name="ebitda_max" :value="search.ebitda_max" type="hidden">
            <input name="pre_tax_income_min" :value="search.pre_tax_income_min" type="hidden">
            <input name="pre_tax_income_max" :value="search.pre_tax_income_max" type="hidden">
            <input name="cash_flow_min" :value="search.cash_flow_min" type="hidden">
            <input name="cash_flow_max" :value="search.cash_flow_max" type="hidden">

            <marketing-search-sort-inputs
            :per-page="perPage"
            :sort="sort"></marketing-search-sort-inputs>

            <marketing-auto-submit-search></marketing-auto-submit-search>
        </fe-form>

    </div>
</template>

<script>
let _merge = require("lodash.merge");
let _find = require("lodash.find");
let _map = require("lodash.map");
let _foreach = require("lodash.foreach");
let _sortedIndexBy = require("lodash.sortedindexby");
let _reverse = require("lodash.reverse");
let moment = require("moment");

module.exports = {
    mixins: [require("./../../mixins/search-bar.js")],

    props: {
        perPage: {
            type: Number,
            default: 25
        },

        sort: {
            type: String,
            default: ""
        },

        searchParameters: {
            default() {
                return typeof window.Spark.old === "undefined"
                    ? []
                    : window.Spark.old;
            }
        },

        savedSearchId: {
            type: [Number, String],
            default: 0
        },

        dataEnableRefineSearch: {
            type: Boolean,
            default: false
        }
    },

    data() {
        return {
            selectedSearchId: this.savedSearchId,
            formId: "listing-search-form",
            savedSearches: {},
            updatingSavedSearches: false,
            enableRefineSearch: this.dataEnableRefineSearch
        };
    },

    computed: {
        hasSavedSearches() {
            return Object.keys(this.savedSearches).length > 0;
        },

        displaySavedSearches() {
            if (this.updatingSavedSearches) {
                return true;
            }

            if (!this.loggedIn || !this.hasSavedSearches) {
                return false;
            }

            return true;
        },

        selectedSearch: {
            get(value) {
                let searchId = parseInt(this.selectedSearchId, 10);
                // Re-add inital search for select
                if (isNaN(searchId) || !this.loggedIn) {
                    return this.getDefaultSearch(true);
                }

                // Empty the search for none
                if (0 === searchId) {
                    return this.defaultSearchValues();
                }

                if (!this.selectedSearchId) {
                    return this.search;
                }

                let search = _find(this.savedSearches, search => {
                    return (
                        parseInt(search.id, 10) ===
                        parseInt(this.selectedSearchId, 10)
                    );
                });

                if (search === undefined) {
                    return search;
                }

                return search;
            },

            set() {}
        },

        loggedIn() {
            return window.Spark.userId !== null;
        },

        searchKeys() {
            let keys = [];

            _foreach(this.defaultSearchValues(), (value, name) => {
                keys.push(name);
            });

            return keys;
        },

        savedSearchOptions() {
            let options = [
                {
                    label: "None",
                    value: 0
                }
            ];

            _foreach(this.savedSearches, search => {
                options.push({
                    label: search.name,
                    value: search.id
                });
            });

            return options;
        }
    },

    methods: {
        updateSavedSearches(callback) {
            if (!this.loggedIn) {
                this.savedSearches = {};
                return;
            }

            this.savedSearches = {};

            window.axios
                .get("/dashboard/saved-search/list")
                .then(response => {
                    this.updatingSavedSearches = false;
                    if (response.data.searches === undefined) {
                        return;
                    }

                    this.savedSearches = response.data.searches;
                    if (typeof callback === "function") {
                        callback(response.data.searches);
                    }
                })
                .catch(() => {
                    this.updatingSavedSearches = false;
                });
        },

        valueChange(input) {
            this.search[input.name] = input.value;

            this.emitSearchUpdated();
        },

        handleReset() {
            this.search = this.getDefaultSearch();
        },

        advancedSearchUpdated(search) {
            this.search = search;
        },

        getDefaultSearch(skipSelected = false) {
            let search = this.mergeOldInputs(this.searchParameters);
            search = _merge(this.defaultSearchValues(), search);

            // Overwrite the selected search if selected by default.
            if (!skipSelected && typeof this.selectedSearch !== "undefined") {
                search = this.selectedSearch;
            }

            search["business_categories"] = _map(
                search["business_categories"],
                cat => parseInt(cat, 10)
            );

            return search;
        },

        savedSearchIdChange(input) {
            this.selectedSearchId = input.value;
            if (this.selectedSearch === undefined) {
                this.search = this.defaultSearchValues();
            } else {
                this.search = this.selectedSearch;
            }
        },

        mergeOldInputs(search) {
            let old = [];

            _foreach(window.Spark.old, (value, name) => {
                if (this.searchKeys.indexOf(name) !== -1) {
                    old[name] = value;
                }
            });

            return _merge(search, old);
        },

        businessCategoryChange(options) {
            this.search.business_categories = _map(
                options,
                option => option.value
            );

            this.emitSearchUpdated();
        },

        emitSearchUpdated() {
            window.Bus.$emit("listing-search-bar.search.updated", this.search);
        },

        mostRecentSavedSearchId() {
            if (!this.hasSavedSearches) {
                return;
            }

            let searches = this.savedSearches;
            _sortedIndexBy(searches, search => {
                return moment(search.updated_at).unix();
            });
            _reverse(searches);

            if (searches[0] === undefined) {
                return;
            }

            return searches[0].id;
        }
    },

    created() {
        this.search = this.getDefaultSearch();
        this.updateSavedSearches(() => {
            this.search = this.getDefaultSearch();
        });

        window.Bus.$on("submit-search", () => {
            let $form = this.$el.querySelector("form");
            if (typeof $form.submit === "function") {
                $form.submit();
            }
        });
    },

    mounted() {
        window.Bus.$on("listing-search.reset", () => {
            this.selectedSearchId = "";
        });

        window.Bus.$on("advanced-search-updated", this.advancedSearchUpdated);

        window.Bus.$on("listing-saved-search:saved", () => {
            this.updatingSavedSearches = true;
            this.updateSavedSearches(() => {
                this.selectedSearchId = this.mostRecentSavedSearchId();
            });
        });
    },

    watch: {
        selectedSearchId() {
            this.emitSearchUpdated();
        }
    }
};
</script>
