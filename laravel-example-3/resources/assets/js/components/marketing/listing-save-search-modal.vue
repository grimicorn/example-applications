<template>

    <button
    v-if="!loggedIn"
    @click="openRequiresAuthModal"
    v-text="buttonLabel"
    class="btn btn-color4 btn-horizontal-input btn-ghost"></button>

    <modal
    v-else-if="loggedIn"
    :button-label="buttonLabel"
    title="Save Search"
    button-class="btn btn-color4 btn-horizontal-input btn-ghost"
    @opened="opened">
        <fe-form
        :form-id="formId"
        :action="action"
        method="POST"
        :disabled-unload="true"
        class="listing-save-search-form"
        :submit-centered="true"
        :should-ajax="true">
            <!-- Name of Search -->
            <input-textual
            name="name"
            :value="search.name"
            placeholder="Name of Search"
            label="Name of Search"
            @change="(input) => name = input.value"></input-textual>

            <div class="fz-14 mb2 lh-copy">This saved search will include your values for Business Category(ies), Asking Price, Zip Code and Miles from Zip Code.</div>

            <!-- Allows for reopening of the "Saved Search" modal -->
            <input type="hidden" name="_save_search" value="1">

            <!-- Flushes the search inputs -->
            <input type="hidden" name="flush" id="save_search_flush" value="1">

            <!-- Saved Search Inputs -->
            <input
            type="hidden"
            :name="input.name"
            :value="input.value"
            :key="input.name"
            v-for="input in inputs">

            <!-- Saved Search Business Categories -->
            <input
            type="hidden"
            name="business_categories[]"
            :value="category"
            :key="category"
            v-for="category in search.business_categories">
        </fe-form>
    </modal>

</template>
<script>
let _map = require("lodash.map");
let _filter = require("lodash.filter");

module.exports = {
    data() {
        return {
            selectedSearchId: 0,
            name: "",
            inputs: this.getInputs(),
            formId: "listing_save_search_form",
            buttonLabel: "Save Search",
            search: {}
        };
    },

    computed: {
        action() {
            let selectedId = parseInt(this.selectedSearchId, 10);

            // If we do not have a selected id then lets create
            if (isNaN(selectedId) || selectedId <= 0) {
                return "/dashboard/saved-searches/store";
            }

            // Since we had an id we can just update
            return `/dashboard/saved-searches/${this.selectedSearchId}/update`;
        },

        loggedIn() {
            return window.Spark.userId !== null;
        },

        autoOpen() {
            let old = window.Spark.old;

            return typeof window.Spark.old["_save_search"] !== "undefined";
        }
    },

    methods: {
        opened() {
            this.inputs = this.getInputs();
        },

        getInputs() {
            let inputs = _map(this.search, (value, name) => {
                if (typeof value === "boolean") {
                    value = value ? "on" : "off";
                }

                return { value, name };
            });

            return _filter(inputs, input => {
                return (
                    [
                        "name",
                        "created_at",
                        "updated_at",
                        "id",
                        "user_id",
                        "business_categories"
                    ].indexOf(input.name) === -1
                );
            });
        },

        openRequiresAuthModal() {
            window.Bus.$emit("requires-auth-modal:open");
        }
    },

    mounted() {
        window.Bus.$on("listing-search-bar.search.updated", search => {
            let savedSearch =
                search.saved_search === undefined ? {} : search.saved_search;

            this.search = {
                business_categories: search.business_categories,
                keyword: search.keyword,
                zip_code: search.zip_code,
                zip_code_radius: search.zip_code_radius,
                asking_price_min: search.asking_price_min,
                asking_price_max: search.asking_price_max,
                name: savedSearch.name
            };

            this.selectedSearchId = savedSearch.id;
        });

        window.Bus.$on(`${this.formId}.successfully-submitted`, () => {
            window.Bus.$emit("modal-should-close");
            window.Bus.$emit("listing-saved-search:saved");
        });
    }
};
</script>
