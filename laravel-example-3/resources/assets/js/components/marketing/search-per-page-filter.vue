<template>
    <div class="marketing-search-per-page-results">

        <div class="marketing-search-per-page">
            Show

            <select
            v-model="perPageCount"
            :value="perPageCount"
            @change="perPageChange">
                <option value="25" selected="selected">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="200">200</option>
            </select>

            Results Per Page
        </div>
    </div>
</template>

<script>
    module.exports = {
        props: {
            totalResults: {
                type: Number,
                default: 0,
            },

            perPage: {
                type: Number,
                default: 25,
            },

            sortOptions: {
                type: Array,
                default: function() {
                    return [];
                },
            },

            sortOrder: {
                type: String,
                default: '',
            },

            defaultSort: {
                type: String,
                default: '',
            },
        },

        data() {
            return {
                perPageCount: this.perPage,
            };
        },

        computed: {},

        methods: {
            sortChange(input) {
                this.$emit('sort-change', input.value);
                window.Bus.$emit('search-sort-change', input.value);
            },

            perPageChange() {
                this.$emit('per-page-change', this.perPageCount);
                window.Bus.$emit('search-per-page-change', this.perPageCount);
            }
        },
    };
</script>
