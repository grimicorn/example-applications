<template>
    <div
        v-if="error"
        class="input-error"
    >
        <icon
            data-name="exclamation-outline"
            data-icon-class="h-3 w-3 fill-current"
        ></icon>
        <span v-text="error"></span>
    </div>
</template>

<script>
export default {
    props: {
        dataError: {
            default: "",
            type: String
        }
    },

    data() {
        return {};
    },

    computed: {
        error() {
            return this.dataError;
        }
    },

    methods: {}
};
</script>
