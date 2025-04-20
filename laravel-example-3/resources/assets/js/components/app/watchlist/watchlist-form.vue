<template>
    <fe-form
    :form-id="formId"
    :action="url"
    method="POST"
    class="relative clearfix"
    :disabled-unload="true"
    :remove-submit="true">
        <!-- Business Category -->
        <input-business-category-select
        label="Business Category"
        name="business_categories[]"
        :value="watchlist.businessCategories"
        class="input-half is-left"></input-business-category-select>
        <!-- Zipcode -->
        <input-textual
        name="zip_code"
        label="Zip Code"
        :value="watchlist.zipcode"
        placeholder="Zip Code"
        validation="numeric"
        class="input-half is-right"></input-textual>

        <!-- Asking Price (Min/Max) -->
        <input-min-max-price
        label="Asking Price"
        name="asking_price"
        :min-value="watchlist.minValue"
        :max-value="watchlist.maxValue"
        @min-change="({value}) => watchlist.minValue = value"
        @max-change="({value}) => watchlist.maxValue = value"
        class="input-half is-left"></input-min-max-price>

        <!-- Miles from Zip Code -->
        <input-range
        label="Miles from Zip Code"
        name="zip_code_radius"
        :min-range="0"
        :max-range="500"
        :value="watchlist.zipcodeRadius"
        class="input-half is-right">
            <template slot="status-message" scope="props">
                Distance: {{props.value}} Miles
            </template>
        </input-range>

        <!-- Name of Search -->
        <input-textual
        name="name"
        :value="watchlist.name"
        placeholder="Search Name"
        label="Search Name"
        validation="required"
        class="input-half is-left"></input-textual>

        <!-- Keyword -->
        <input-textual
        name="keyword"
        :value="watchlist.keyword"
        placeholder="Keyword"
        label="Keyword"
        class="input-half is-right"></input-textual>

        <!-- Watchlist Id
        (Allows the edit modals to open up on submit error) -->
        <input type="hidden" name="watchlist_id" :value="watchlist.id">

        <div
        :class="{
            'text-right clear': !isEdit,
            'text-center clear': isEdit,
        }">
            <app-model-delete-button
            :route="destroyUrl"
            label="Delete"
            v-if="isEdit"
            class="inline mr2"
            button-class="btn-color7"
            :is-link="false"></app-model-delete-button>

            <button
            type="submit"
            class="inline-block"
            :class="{
                'fe-input-height': !isEdit,
            }"
            v-text="submitLabel"></button>
        </div>
    </fe-form>
</template>

<script>
module.exports = {
    props: {
        isEdit: {
            type: Boolean,
            default: false,
        },

        businessCategories: {
            type: Array,
            default() {
                return [];
            },
        },

        zipcode: {
            type: String,
            default: '',
        },

        zipcodeRadius: {
            type: Number,
            default: 100,
        },

        keyword: {
            type: String,
            default: '',
        },

        minValue: {
            type: Number,
        },

        maxValue: {
            type: Number,
        },

        name: {
            type: String,
            default: '',
        },

        watchlistId: {
            type: Number,
            default: 0,
        },

        dataFormId: {
            default: 'watchlist_form',
            type: String,
        },
    },

    data() {
        return {
            formId: this.dataFormId,
            watchlist: {
                businessCategories: this.businessCategories,
                zipcode: this.zipcode,
                zipcodeRadius: this.zipcodeRadius,
                minValue: this.minValue,
                maxValue: this.maxValue,
                name: this.name,
                keyword: this.keyword,
                id: parseInt(this.watchlistId, 10),
            },
        };
    },

    computed: {
        url() {
            if (this.watchlist.id > 0) {
                return `/dashboard/saved-searches/${this.watchlist.id}/update`;
            }

            return '/dashboard/saved-searches/store';
        },

        destroyUrl() {
            return `/dashboard/saved-searches/${this.watchlist.id}/destroy`;
        },

        submitLabel() {
            return this.isEdit ? 'Update' : 'Create Search';
        },
    },

    methods: {
        getPostData() {
            let watchlist = this.watchlist;
            delete watchlist.id;

            return watchlist;
        },
    },

    watch: {
        businessCategories(value) {
            this.watchlist.businessCategories = value;
        },

        zipcode(value) {
            this.watchlist.zipcode = value;
        },

        zipcodeRadius(value) {
            this.watchlist.zipcodeRadius = value;
        },

        minValue(value) {
            this.watchlist.minValue = value;
        },

        maxValue(value) {
            this.watchlist.maxValue = value;
        },

        name(value) {
            this.watchlist.name = value;
        },

        watchlistId(value) {
            this.watchlist.id = parseInt(value, 10);
        },
    },
};
</script>
