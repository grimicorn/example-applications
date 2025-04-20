<template>
    <form
        :id="id"
        :method="method"
        :action="action"
        @submit.prevent="submit"
    >
        <input
            type="hidden"
            name="_token"
            :value="csrfToken"
        >

        <input
            v-if="requiresMethodField"
            type="hidden"
            name="_method"
            :value="method"
        >

        <slot
            name="inputs"
            :errors="formErrors"
            :submiting="submiting"
        ></slot>

        <slot
            v-if="!autoSubmit"
            name="submit"
            :submiting="submiting"
            :submit-dusk="submitDusk"
            :disabled="submiting"
        >
            <div
                class="flex clear"
                :class="{
                    'justify-end': submitAlignment === 'right',
                    'justify-center': submitAlignment === 'center',
                    'justify-start': submitAlignment === 'left',
                }"
            >
                <button
                    :dusk="submitDusk"
                    type="submit"
                    v-text="submitLabel"
                    :disabled="submiting"
                    :class="{
                        'w-full': submitAlignment === 'full',
                    }"
                ></button>
            </div>
        </slot>

        <slot name="after-submit"></slot>
    </form>
</template>

<script>
import collect from "collect.js";
import { debounce } from "debounce";

export default {
    store: ["formErrors", "addAlert", "messages"],

    props: {
        dataId: {
            type: String,
            required: true
        },

        dataMethod: {
            type: String,
            default: "POST",
            validation(value) {
                value = value.toUpperCase();
                return (
                    ["GET", "POST", "PATCH", "PUT", "DELETE"].indexOf(value) !==
                    -1
                );
            }
        },

        dataAction: {
            type: String
        },

        dataSubmitLabel: {
            type: String,
            default: "Submit"
        },

        dataSubmitAlignment: {
            type: String,
            default: "right",
            validator(value) {
                return (
                    ["right", "center", "left", "full"].indexOf(value) !== -1
                );
            }
        },

        dataConfirmation: {
            type: String,
            default: ""
        },

        dataAutoSubmit: {
            type: Boolean,
            default: false
        }
    },

    data() {
        return {
            submiting: false,
            id: this.dataId,
            confirmation: this.dataConfirmation,
            submitAlignment: this.dataSubmitAlignment,
            autoSubmit: this.dataAutoSubmit
        };
    },

    computed: {
        submitLabel() {
            return this.dataSubmitLabel;
        },

        submitDusk() {
            return `${this.id}_submit`;
        },

        csrfToken() {
            return this.$http.defaults.headers.common["X-CSRF-TOKEN"];
        },

        method() {
            return this.dataMethod.toUpperCase();
        },

        action() {
            if (!this.dataAction) {
                return window.location.href;
            }

            return this.dataAction;
        },

        requiresMethodField() {
            return this.method !== "POST" && this.method !== "GET";
        }
    },

    methods: {
        hasFileInputs() {
            let $inputs = this.$el.querySelectorAll('input[type="file"]');

            return $inputs.length > 0;
        },

        getParams() {
            return new FormData(this.$el);
        },

        getOptions() {
            let options = {};

            if (this.hasFileInputs()) {
                options.headers = {
                    "Content-Type": "multipart/form-data"
                };
            }

            return options;
        },

        confirm() {
            if (!this.confirmation) {
                return "left";
            }

            return confirm(this.confirmation);
        },

        submit() {
            let confirmed = this.confirm();
            if (!confirmed) {
                return;
            }

            let method = this.method.toLowerCase();
            if (method !== "get") {
                method = "post";
            }
            this.submiting = true;

            this.$http[method](this.action, this.getParams(), this.getOptions())
                .then(response => {
                    this.submiting = false;

                    if (response.data.redirect) {
                        window.location = response.data.redirect;
                    }

                    if (response.data.status) {
                        this.addAlert(response.data.status, "success", 5000);
                    }

                    this.$emit("submitted");
                    window.Bus.$emit(`${this.id}.submitted`);
                })
                .catch(error => {
                    this.submiting = false;

                    if (error.response.status === 422) {
                        this.handleValidationError(error);
                        return;
                    }

                    this.addAlert(this.messages.genericError, "danger", 5000);
                });
        },

        handleValidationError(error) {
            this.setFormErrors(error.response.data.errors);

            this.addAlert(error.response.data.message, "danger", 5000);
        },

        setFormErrors(errors) {
            this.formErrors = collect(errors).map(error => collect(error));
        },

        executeAutoSubmit() {
            if (!this.autoSubmit) {
                return;
            }

            this.submit();
        }
    },

    mounted() {
        this.$el.addEventListener(
            "change",
            debounce(this.executeAutoSubmit, 1000)
        );
    },

    created() {
        this.formErrors = collect([]);
    }
};
</script>
