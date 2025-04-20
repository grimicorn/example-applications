<template>
    <span
    class="sort-table-sort-icons"
    v-if="!sortDisabled"
    :class="{
        'is-sorted': isSorted,
        'is-sorted-asc': (isSorted && sortOrder === 'asc'),
        'is-sorted-desc': (isSorted && sortOrder === 'desc'),
    }">


        <i class="fa fa-caret-up sort-table-sort-icon is-asc" aria-hidden="true"></i>
        <i class="fa fa-caret-down sort-table-sort-icon is-desc" aria-hidden="true"></i>
    </span>
</template>

<script>
    module.exports = {
        props: {
            sortDisabled: {
                type: Boolean,
                default: false,
            },

            isSorted: {
                type: Boolean,
                default: false,
            },

            sortOrder: {
                type: String,
                default: '',
            },
        },

        data() {
            return {};
        },

        computed: {},

        methods: {},
    };
</script>
