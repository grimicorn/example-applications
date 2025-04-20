<template>
    <input-images
        :data-error="error"
        :data-label="label"
        :data-id="id"
        :data-name="name"
        :data-instructions="instructions"
        :data-input-class="inputClass"
        :data-disabled="disabled"
        :data-readonly="readonly"
        :data-dusk="dusk"
        :data-autofocus="autofocus"
        :data-required="required"
        :data-images="images"
        :data-multiple="false"
    ></input-images>
</template>

<script>
import inputMixin from "mixins/input";

export default {
    mixins: [inputMixin],

    props: {
        dataValue: {
            type: Object,
            default() {
                return {};
            }
        }
    },

    data() {
        return {
            value: this.dataValue
        };
    },

    computed: {
        accept() {
            return [".jpg", ".png", ".gif"];
        },

        images() {
            if (!this.value) {
                return [];
            }

            return [this.value];
        }
    },

    methods: {
        handleInput($event) {
            window.Vue.nextTick(() => {
                this.emitInput($event, this.value);
            });
        }
    },

    watch: {
        formErrors(errors) {
            this.setErrors(errors);
        },

        dataValue() {
            this.value = this.dataValue;
        }
    },

    mounted() {
        this.setFormId();
    }
};
</script>
