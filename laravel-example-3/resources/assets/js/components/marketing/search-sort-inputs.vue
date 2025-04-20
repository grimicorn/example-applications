<template>
    <div class="search-sort-inputs">
        <input
        type="hidden"
        name="per_page"
        :value="currentPerPage">

        <input
        type="hidden"
        name="sort_order"
        :value="currentSort">
    </div>
</template>

<script>
    module.exports = {
        props: {
            perPage: {
                type: Number,
                default: 25,
            },

            sort: {
                type: String,
                default: '',
            },
        },

        data() {
            return {
                currentPerPage: this.perPage,
                currentSort: this.sort,
            };
        },

        methods: {
            emitChange() {
                let values = {
                    currentPerPage: this.currentPerPage,
                    currentSort: this.currentSort,
                };
                this.$emit('change', values);
                window.Bus.$emit('search-sort-inputs:change', values);
            },
        },

        created() {
            window.Bus.$on('search-sort-change', (value) => {
                this.currentSort = value;
                this.emitChange();
            });
            window.Bus.$on('search-per-page-change', (value) => {
                this.currentPerPage = value;
                this.emitChange();
            });
        },
    };
</script>
