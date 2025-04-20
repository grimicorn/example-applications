<template>
    <modal
    v-if="confirming"
    :display-button="false"
    :title="title"
    :auto-open="true"
    @closed="handleClose">
        <template scope="props">
            <div
            class="mb3 text-center"
            v-html="content"></div>

            <div class="flex justify-center">
                <button
                @click="cancel(props)"
                class="btn-color7 mr3">Cancel</button>

                <button
                @click="confirm(props)"
                v-text="submitLabel"></button>
            </div>
        </template>
    </modal>
</template>

<script>
module.exports = {
    props: {},

    data() {
        return {
            confirming: false,
            content: "",
            id: "",
            confirmed: false,
            title: "Confirm",
            submitLabel: "Confirm"
        };
    },

    computed: {},

    methods: {
        cancel(props) {
            this.confirmed = false;
            this.emitConfirmation();
            props.close();
        },

        confirm(props) {
            this.confirmed = true;
            this.emitConfirmation();
            props.close();
        },

        handleClose() {
            this.confirming = false;
            this.emitConfirmation();
        },

        emitConfirmation() {
            window.Bus.$emit(
                `${this.id}:confirmation:receieved`,
                this.confirmed
            );
        }
    },

    mounted() {
        window.Bus.$on("confirmation:requested", data => {
            this.confirming = true;
            this.confirmed = false;
            this.content = data.content;
            this.title = data.title ? data.title : "Confirm";
            this.submitLabel = data.submitLabel ? data.submitLabel : "Confirm";
            this.id = data.id;
        });
    }
};
</script>
