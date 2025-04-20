<template>
    <button
    class="btn btn-color5 cursor model-preview-button"
    @click="buttonSubmit"
    :disabled="saving">
        <i
        class="fa fa-circle-o-notch fa-spin"
        aria-hidden="true"
        v-if="saving"></i>

        {{ this.label }}
    </button>
</template>

<script>
let _map = require('lodash.map');
let _foreach = require('lodash.foreach');
let debounce = require('./../../debounce');

module.exports = {
    name: 'app-model-preview-button',

    props: {
        routes: {
            type: Array,
            required: true,
        },

        storeRoute: {
            type: String,
            required: true,
        },

        formId: {
            type: String,
            default: '',
        },

        dataLabel: {
            type: String,
            default: 'Preview',
        },

        dataSyncOnUpdate: {
            type: Boolean,
            default: true,
        },
    },

    data() {
        return {
            formData: {},
            previewWindows: [],
            saving: false,
            debounce: debounce.new(),
            sendAgain: false,
        };
    },

    computed: {
        label() {
            return this.dataLabel;
        },
    },

    methods: {
        isCurrentlyOpened(route) {
            return (
                typeof this.previewWindows[route] !== 'undefined' &&
                !this.previewWindows[route].closed
            );
        },

        openPreviewWindows(onlyReload = false) {
            _foreach(this.routes, route => {
                this.openPreviewWindow(route, onlyReload);
            });
        },

        openPreviewWindow(route, onlyReload = false) {
            let isOpen = this.isCurrentlyOpened(route);

            // Return in the case when we only want to reload and the window is not open.
            if (!isOpen && onlyReload) {
                return;
            }

            // Handle reloading open windows
            if (isOpen) {
                this.previewWindows[route].location.reload();
                return;
            }

            // Finally open the window
            this.previewWindows[route] = window.open(route);
        },

        buttonSubmit() {
            this.submit();
        },

        submit(onlyReload = false) {
            if (this.saving) {
                this.sendAgain = true;
                return;
            }

            this.saving = true;

            window.axios
                .post(this.storeRoute, this.formData)
                .then(response => {
                    this.openPreviewWindows(onlyReload);

                    this.saving = false;

                    if (this.sendAgain) {
                        this.sendAgain = false;
                        this.submit(true);
                    }
                })
                .catch(errors => {
                    window.Bus.$emit(
                        `${this.formId}-preview-submission-error`,
                        errors
                    );

                    this.saving = false;
                });
        },
    },

    mounted() {
        window.Bus.$on(`${this.formId}.created`, formData => {
            this.formData = formData;
        });

        window.Bus.$on(`${this.formId}.updated`, formData => {
            this.debounce.set(() => {
                window.Vue.nextTick(() => {
                    this.formData = formData;

                    if (this.dataSyncOnUpdate) {
                        this.submit(true);
                    }
                });
            }, 500);
        });
    },
};
</script>
