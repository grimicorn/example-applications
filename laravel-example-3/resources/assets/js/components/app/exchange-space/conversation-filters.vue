<template>
    <div class="diligence-center-conversation-filter flex"
    v-if="!isInquiry">
        <input-select
        name="filter_by_topic"
        label="Filter by Topic"
        :options="categoryOptions"
        placeholder="Filter by Topic"
        class="nowrap flex-3 mr0 pr2 hide-labels mb0"
        input-class="fe-input-small"
        @change="categoryChange"></input-select>

        <input-select
        name="filter_by_status"
        label="Filter by Status"
        :options="statusOptions"
        placeholder="Filter by Status"
        class="nowrap flex-3 mr0 pr2 hide-labels mb0"
        input-class="fe-input-small"
        :value="status"
        @change="statusChange"></input-select>

        <input-search
        class="flex-5 mr0 fe-input-small mb0 pt0 pb0"
        @submit="searchSubmit"></input-search>
    </div>

    <div
    class="flex"
    v-else>
        <div class="flex-auto">
            <input-select
            name="sort_buyer_inquiries"
            label="Sort by"
            :options="sortOptions"
            class="nowrap flex-4 mr0 pr2 hide-labels mb0"
            input-class="fe-input-small"
            placeholder="Sort Inquiries"
            @change="sortChange"></input-select>
        </div>

        <div class="flex-5">
            <input-search
            class="mr0 fe-input-small mb0"
            @submit="searchSubmit"></input-search>
        </div>
    </div>
</template>

<script>
module.exports = {
    props: {
        categoryOptions: {
            type: Array,
            default() {
                return [];
            }
        },

        isInquiry: {
            type: Boolean,
            default: false
        },

        statusOptions: {
            type: Array,
            default() {
                return [];
            }
        },

        titleOptions: {
            type: Array,
            default() {
                return [];
            }
        },

        inquirerOptions: {
            type: Array,
            default() {
                return [];
            }
        },

        sortOptions: {
            type: Array || Object,
            default() {
                return [];
            }
        }
    },

    data() {
        return {
            status: ""
        };
    },

    computed: {},

    methods: {
        categoryChange(input) {
            this.$emit("category-change", input.value);
            window.Bus.$emit(
                "converation-filters:category-change",
                input.value
            );
        },

        statusChange(input) {
            this.status = input.value;
            this.$emit("status-change", input.value);
            window.Bus.$emit("converation-filters:status-change", input.value);
        },

        titleChange(input) {
            this.$emit("title-change", input.value);
            window.Bus.$emit("converation-filters:title-change", input.value);
        },

        inquirerChange(input) {
            this.$emit("inquirer-change", input.value);
            window.Bus.$emit(
                "converation-filters:inquirer-change",
                input.value
            );
        },

        sortChange(input) {
            this.$emit("sort-change", input.value);
            window.Bus.$emit("converation-filters:sort-change", input.value);
        },

        searchSubmit(value) {
            this.$emit("search-submit", value);
            window.Bus.$emit("converation-filters:search-submit", value);
        }
    },

    mounted() {
        window.Bus.$on("input-search:cleared", () => {
            this.searchSubmit("");
        });

        window.Bus.$on("converation-filters:update:status", status => {
            this.status = status;
        });
    }
};
</script>
