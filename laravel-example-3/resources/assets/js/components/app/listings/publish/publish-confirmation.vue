<template>
    <div

    class="text-center">
        <div class="text-bold mb3">
            <slot></slot>

             <p>
                Clicking <strong>OK</strong> will instantly post your business, allowing you to share it with potential buyers.
            </p>
        </div>

        <div class="inline-block">
            <button
            type="button"
            @click="cancel"
            class="btn-color7">Cancel</button>

            <button
            type="button"
            @click="confirm">Ok</button>
        </div>
    </div>
</template>

<script>
export default {
    props: {},

    data() {
        return {};
    },

    computed: {},

    methods: {
        cancel() {
            this.$emit("canceled");
        },

        confirm() {
            this.$emit("confirmed");
        }
    }
};
</script>
