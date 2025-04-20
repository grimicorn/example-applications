<template>
    <div class="marketing-search-sort-filter items-center">
        <input-select
        :readonly="!changeEnabled"
        name="sort_order"
        :value="defaultSort"
        :options="sortOptions"
        placeholder="Sort"
        wrap-class="hide-labels"
        @change="sortChange"></input-select>
    </div>
</template>

<script>
let utilities = require('./../../mixins/utilities.js');
module.exports = {
    mixins: [utilities],

    props: {
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
            changeEnabled: !this.isEdgeBrowser(),
            perPageCount: this.perPage,
        };
    },

    computed: {},

    methods: {
        sortChange(input) {
            if (!this.changeEnabled) {
                return;
            }
            this.$emit('sort-change', input.value);
            window.Bus.$emit('search-sort-change', input.value);
        },
    },

    mounted() {
        setTimeout(() => {
            this.changeEnabled = true;
        }, 1000);
    },
};
</script>
