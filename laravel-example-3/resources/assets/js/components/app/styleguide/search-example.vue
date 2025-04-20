<template>
    <div>
        <h2 class="mt3">Search</h2>

        <input-search
        class="flex-5 mr0 fe-input-small mb0 pt0 pb0"
        @submit="searchSubmit"></input-search>

        <div v-text="`Search value: ${searchValue}`"></div>
    </div>
</template>

<script>
export default {
    props: {},

    data() {
        return {
            searchValue: '',
        };
    },

    computed: {},

    methods: {
        searchSubmit(value) {
            this.searchValue = value;
        },
    },
};
</script>
