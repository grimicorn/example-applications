<template>
<div class="flex items-center">
    <input-search
    @submit="submit"
    class="mb0"></input-search>
</div>
</template>

<script>
    module.exports = {
        props: {
            value: {
                type: String,
                default: '',
            },
        },

        data() {
            return {
                inputValue: this.value,
            };
        },

        watch: {
            inputValue(newValue) {
                newValue = (typeof newValue === 'string') ? newValue.trim() : '';

                this.$emit('change', newValue.trim());
            },
        },

        methods: {
            submit(value) {
                this.inputValue = value;
                this.$emit('submit', value);
                window.Bus.$emit('sort-table-search-submit', value);
            },
        },
    };
</script>
