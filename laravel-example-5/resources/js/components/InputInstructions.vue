<template>
    <div
        v-if="instructions"
        class="input-instructions"
    >
        <icon
            data-name="information-outline"
            data-icon-class="h-3 w-3 fill-current"
        ></icon>
        <span v-text="instructions"></span>
    </div>
</template>

<script>
export default {
    props: {
        dataInstructions: {
            default: "",
            type: String
        }
    },

    data() {
        return {};
    },

    computed: {
        instructions() {
            return this.dataInstructions;
        }
    },

    methods: {}
};
</script>
