<template>
    <div
        class="table-responsive"
    >
        <div
            class="table-loader"
            v-if="displayLoading"
        >
            <loader></loader>
        </div>

        <div class="mb-6 flex items-center">
            <div
                class="pr-4 flex justify-start"
                :class="{
                    'w-3/4': searchable
                }"
            >
                <slot name="toolbar"></slot>
            </div>
            <div class="w-1/4" v-if="searchable">
                <input-text
                    class="mb-0"
                    data-type="search"
                    data-name="data_table_search"
                    data-placeholder="Search..."
                    :data-value="keyword"
                    v-model="keyword"
                    @keyup="$emit('search')"
                ></input-text>
            </div>
        </div>

        <table class="table bg-white">
            <thead>
                <tr>
                    <th
                        v-for="(header,index) in headers"
                        :key="header.label"
                        :class="{
                            'cursor-pointer': header.sortable && !loading,
                        }"
                        @click="sortByIndex(index)"
                    >
                        {{ header.label }}

                        <div
                            class="inline"
                            v-if="header.sortable"
                        >
                            <icon
                                v-show="header.ascending"
                                data-name="cheveron-up"
                                data-icon-class="h-3 w-3 fill-current"
                            ></icon>
                            <icon
                                v-show="!header.ascending"
                                data-name="cheveron-down"
                                data-icon-class="h-3 w-3 fill-current"
                            ></icon>
                        </div>
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr

                    v-for="(item,index) in items"
                    :key="index"
                >
                    <slot name="row" :item="item" :index="index"></slot>
                </tr>

                <tr v-if="!items || items.length === 0 && !displayLoading">
                    <td
                        class="text-center font-bold"
                        :colspan="headers.length"
                        v-text="emptyMessage"
                    ></td>
                </tr>
            </tbody>
        </table>

        <pagination
            :limit="2"
            v-if="paginationData"
            :class="{
                'pointer-events-none': loading,
                'opacity-75': displayLoading,
            }"
            :data="paginationData"
            @pagination-change-page="handlePaginationChangePage"
        ></pagination>
    </div>
</template>

<script>
import _map from "lodash.map";
import _snakeCase from "lodash.snakecase";
import _filter from "lodash.filter";
import queryParameters from "utilities/query-parameters";
import collect from "collect.js";
import { debounce } from "debounce";
import ModelEvents from "utilities/model-events";
import models from "utilities/models";

export default {
    store: ["user"],

    props: {
        dataHeaders: {
            type: Array,
            required: true
        },

        dataUrl: {
            type: String,
            required: true
        },

        dataEmptyMessage: {
            type: String,
            default: "No matching items found."
        },

        dataId: {
            type: String,
            required: true
        },

        dataSearchable: {
            type: Boolean,
            default: true
        },

        dataItemModel: {
            type: String
        }
    },

    data() {
        return {
            initialized: false,
            id: this.dataId,
            headers: this.dataHeaders,
            items: [],
            loading: true,
            sortColumn: null,
            sortAscending: true,
            paginationData: {},
            currentPage: 1,
            keyword: "",
            emptyMessage: this.dataEmptyMessage,
            searchable: this.dataSearchable,
            searchNeeded: false,
            itemModel: this.dataItemModel,
            modelEvents: null,
            getItemsSilent: false
        };
    },

    computed: {
        url() {
            let url = this.dataUrl;
            let query = [];

            if (this.sortColumn) {
                let order = this.sortAscending ? "" : "-";
                query.push(`sort=${order}${this.sortColumn}`);
            }

            if (this.currentPage) {
                query.push(`page=${this.currentPage}`);
            }

            if (this.keyword) {
                query.push(`keyword=${this.keyword}`);
            }

            return this.appendQueryString(url, query.join("&"));
        },

        displayLoading() {
            return this.loading && !this.getItemsSilent;
        }
    },

    methods: {
        handlePaginationChangePage(page) {
            this.currentPage = page;
            this.setPushState();
            this.getItems();
        },

        appendQueryString(url, query) {
            if (url.indexOf("?") === -1) {
                return `${url}?${query}`;
            }

            return `${url}&${query}`;
        },

        search() {
            if (!this.searchNeeded) {
                return;
            }

            this.setPushState();
            this.getItems();
            this.searchNeeded = false;
        },

        sortByIndex(index) {
            return this.sortByHeader(this.headers[index]);
        },

        sortByHeader(header) {
            if (this.loading || !header || !header.sortable) {
                return;
            }

            this.toggleAscending(header);
            this.sortColumn = header.column;
            this.sortAscending = header.ascending;
            this.setPushState();
            this.getItems();
        },

        setPushState() {
            history.pushState({}, document.title, this.url);
        },

        toggleAscending(header) {
            if (!header || !header.sortable) {
                return header;
            }

            this.headers = _map(this.headers, item => {
                if (header.label !== item.label) {
                    item.ascending = true;
                }

                return item;
            });

            header.ascending = !header.ascending;

            return header;
        },

        setHeaderDefaults() {
            this.headers = _map(this.headers, header => {
                if (typeof header.ascending === "undefined") {
                    header.ascending = true;
                }

                if (typeof header.sortable === "undefined") {
                    header.sortable = true;
                }

                if (typeof header.column === "undefined") {
                    header.column = _snakeCase(header.label);
                }

                return header;
            });
        },

        getHeaderByColumn(column) {
            return collect(this.headers)
                .where("column", column)
                .first();
        },

        getItems(page, silent = false) {
            this.getItemsSilent = silent;

            if (this.loading && this.initialized) {
                this.getItemsSilent = false;

                return;
            }

            this.loading = true;

            if (page) {
                this.currentPage = page;
            }

            this.$http
                .get(this.url)
                .then(response => {
                    this.loading = false;
                    this.initialized = true;
                    this.getItemsSilent = false;

                    if (
                        !response.data ||
                        !response.data.items ||
                        !response.data.items.data
                    ) {
                        this.items = [];
                        this.paginationData = {};

                        return;
                    }

                    this.items = Object.values(response.data.items.data);
                    this.paginationData = response.data.items;
                })
                .catch(error => {
                    this.items = [];
                    this.loading = false;
                    this.getItemsSilent = false;
                });
        },

        setInitialSort() {
            let parameters = queryParameters.get();

            let sort = parameters.get("sort");
            if (sort) {
                this.sortColumn = sort.replace(/^\-+/g, "");
                this.sortAscending = sort.indexOf("-") !== 0;
            }

            this.keyword = parameters.get("keyword", "");
            this.currentPage = parameters.get("page", 1);
        },

        handleRefreshEvent() {
            window.Bus.$on(`data-table.${this.id}.refresh`, () => {
                this.getItems();
            });
        },

        handleRemoveEvent() {
            window.Bus.$on(`data-table.${this.id}.item.remove`, item => {
                this.items = _filter(this.items, value => {
                    return value.id !== item.id;
                });
            });
        },

        initialize() {
            this.setHeaderDefaults();
            this.setInitialSort();

            if (this.sortColumn) {
                this.loading = false;
                this.sortByHeader(this.getHeaderByColumn(this.sortColumn));
            } else {
                this.getItems();
            }
        },

        handleSearchEvent() {
            this.$on("search", debounce(this.search, 1000));
        },

        handleItemModelDeleted() {
            if (!this.itemModel) {
                return;
            }

            this.modelEvents.deleted(this.itemModel, e => {
                this.items = models.removeByKey(this.items, e.modelKey);
            });
        },

        handleItemModelSaved() {
            if (!this.itemModel) {
                return;
            }

            this.modelEvents.saved(this.itemModel, e => {
                this.getItems(this.currentPage, true);
            });
        }
    },

    watch: {
        keyword() {
            this.searchNeeded = true;
        }
    },

    mounted() {
        this.modelEvents = new ModelEvents(this.user);

        this.initialize();
        this.handleRefreshEvent();
        this.handleSearchEvent();
        this.handleRemoveEvent();
        this.handleItemModelDeleted();
        this.handleItemModelSaved();
    }
};
</script>
