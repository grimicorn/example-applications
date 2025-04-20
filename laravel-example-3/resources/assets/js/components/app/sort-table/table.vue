<template>
<div class="sort-table-wrap">
    <div class="flex mb3">
        <div class="flex-auto">
            <slot name="header_left" :vm="this"></slot>
        </div>

        <slot name="header_right" :vm="this"></slot>
    </div>
    <slot name="subheader" :vm="this"></slot>
    <table
    class="sort-table"
    :class="{
        'is-filtering': isFiltering
    }">
        <thead>
            <tr>
                <th
                class="nowrap"
                v-for="(header, index) in headers"
                :key="header.sortKey"
                @click="sortColumn(index)"
                :class="header.class">
                    <div
                    class="sort-table-label"
                    v-html="header.label"></div>
                    <app-sort-table-order-icons
                    :sort-disabled="header.sortDisabled"
                    :is-sorted="header.isSorted"
                    :sort-order="header.sortOrder"></app-sort-table-order-icons>
                </th>
            </tr>
        </thead>

        <tbody>
            <!-- Displays the filtering icon -->
            <tr
            class="sort-table-body-filtering-icon"
            v-if="isFiltering">
                <td
                :colspan="headers.length"
                class="text-center relative pa3">
                    <loader :loading="true"></loader>
                </td>
            </tr>

            <!-- Error message -->
            <tr
            class="sort-table-body-error-message"
            v-if="filterErrorMessage">
                <td
                :colspan="headers.length"
                class="text-center"
                v-text="filterErrorMessage"></td>
            </tr>

            <!-- Not found message-->
            <tr
            class="sort-table-body-row"
            v-else-if="items.length === 0 && filterErrorMessage === ''">
                <td
                :colspan="headers.length"
                class="text-center">No items found</td>
            </tr>

            <!-- Table row data -->
            <tr
            v-else
            class="sort-table-body-row"
            v-for="(item, index) in items"
            :key="item.id">
                <slot
                name="row"
                :formatDate="formatDate"
                :item="item"
                :index="index"></slot>
            </tr>
        </tbody>
    </table>

    <model-pagination
    :paginated="paginated"
    :is-navigating="isFiltering"
    @change="handlePageChange"></model-pagination>
</div>
</template>

<script>
let moment = require('moment');
let _map = require('lodash.map');
let _merge = require('lodash.merge');

module.exports = {
    mixins: [require('./../../../mixins/utilities.js')],

    props: {
        columns: {
            type: Array,
            default() {
                return [];
            },
        },

        paginatedItems: {
            type: Object,
            required: true,
        },

        route: {
            type: String,
            required: true,
        },

        dataFilters: {
            type: Object,
            default() {
                return {};
            },
        },
    },

    data() {
        return {
            isFiltering: false,

            filterErrorMessage: '',

            paginated: this.paginatedItems,

            // filters: this.mergeDataFilters(),
        };
    },

    computed: {
        filters() {
            return _merge(
                {
                    search: '',
                    sortKey: '',
                    sortOrder: '',
                    page: 1,
                },
                this.dataFilters
            );
        },

        items: {
            get() {
                return this.paginated.data;
            },

            set() {},
        },

        headers: {
            get() {
                let headers = _map(this.columns, header => {
                    return this.setHeaderDefaults(header);
                });

                return headers;
            },

            set() {},
        },
    },

    watch: {
        paginated(newValue) {
            this.filters.page = newValue.current_page;
        },
    },

    methods: {
        mergeDataFilters() {
            return _merge(
                {
                    search: '',
                    sortKey: '',
                    sortOrder: '',
                    page: 1,
                },
                this.dataFilters
            );
        },

        getHeaderSortyKey(header) {
            if (header.sortyKey) {
                return header.sortyKey;
            }

            return this.slugify(header.label, '_');
        },

        setHeaderDefaults(header) {
            return _merge(
                {
                    label: '',
                    isSorted: false,
                    sortOrder: '',
                    sortKey: this.getHeaderSortyKey(header),
                    sortDisabled: false,
                },
                header
            );
        },

        filter() {
            // Set filter data
            this.filterErrorMessage = '';
            this.isFiltering = true;

            axios
                .get(this.route, { params: this.filters })
                .then(response => {
                    this.filterSuccess(response.data);
                })
                .catch(error => {
                    this.filterError();
                });
        },

        filterSuccess(paginated) {
            // Reset the pagination to update the table.
            this.paginated = paginated;

            // Display the results.
            this.isFiltering = false;
        },

        filterError() {
            // Remove the items and set the error message.
            this.items = [];
            this.filterErrorMessage = 'Something went wrong please try again';
            this.isFiltering = false;
        },

        search(value) {
            this.filters.search = value;
            this.filters.page = 1; // Set the page back to 1 so we paginate correctly.

            this.filter();
        },

        handleSearchChange(value) {
            // Make it easier to clear out the search.
            if (value !== '') {
                return;
            }

            this.filters.search = value;

            this.filters.page = 1;
        },

        handlePageChange(page) {
            this.filters.page = parseInt(page, 10);

            this.filter();
        },

        toggleSortOrder(order) {
            order = order.toLowerCase().trim();

            if (!order) {
                return 'asc';
            }

            return 'desc' === order ? 'asc' : 'desc';
        },

        setColumnSortAttributes(column, sorted = false) {
            column.isSorted = sorted;
            column.sortOrder = sorted
                ? this.toggleSortOrder(column.sortOrder)
                : '';

            return column;
        },

        sortColumn(columnIndex) {
            // If the sort order is disabled then there is nothing we need to do.
            if (this.headers[columnIndex].sortDisabled) {
                return;
            }

            // First we need to set a few details for both headers and the filter.
            this.headers = _map(this.headers, (column, index) => {
                let sorted = index === columnIndex;

                // Set details for the headers
                column = this.setColumnSortAttributes(column, sorted);

                // Set details for the filter.
                if (sorted) {
                    this.filters.sortKey = column.sortKey;
                    this.filters.sortOrder = column.sortOrder;
                }

                return column;
            });

            // Set the page back to 1 so we paginate correctly.
            this.filters.page = 1;

            // Filter the table.
            this.filter();
        },
    },

    mounted() {
        window.Bus.$on('sort-table-search-submit', value => {
            this.search(value);
        });
    },
};
</script>
