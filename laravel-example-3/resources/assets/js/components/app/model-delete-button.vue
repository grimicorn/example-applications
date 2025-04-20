<template>
    <fe-form
    form-id="model-delete-form"
    class="model-delete-form pt1 inline-block"
    method="DELETE"
    :action="route"
    :confirm-message="confirmMessage"
    :remove-submit="true">
        <button
        class="btn btn-model-delete"
        :class="buttonClasses"
        type="submit">
            {{ label }}
            <strong
            v-if="isLink"
            class="fa fa-times"
            aria-hidden="true"></strong>
        </button>
    </fe-form>
</template>

<script>
    module.exports = {
        props: {
            route: {
                type: String,
                required: true,
            },

            isLink: {
                type: Boolean,
                default: true,
            },

            confirmMessage: {
                type: String,
                default: 'Are your sure you want to delete this item? This can not be undone.',
            },

            label: {
                type: String,
                default: 'Delete'
            },

            buttonClass: {
                type: String,
                default: ''
            },
        },

        computed: {
            buttonClasses() {
                return [
                    this.isLink ? 'btn-link' : '',
                    this.buttonClass,
                ];
            }
        },

        methods: {
            confirmDeletion(e) {
            },
        },
    };
</script>
