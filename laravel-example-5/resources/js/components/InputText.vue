<template>
    <div
        class="input-text-wrap input-wrap"
        :class="[
            this.error ? 'has-error' : '',
            `is-${type}`
        ]"
    >
        <slot name="before"></slot>

        <slot name="label">
            <label
                v-if="label"
                :for="id"
                class="input-label"
                v-text="label"
            ></label>
        </slot>

        <slot name="input">
            <input
                :type="type"
                :name="name"
                :id="id"
                class="input-text"
                :class="inputClass"
                v-model="value"
                :placeholder="placeholder"
                :min="min"
                :max="max"
                :step="step"
                :disabled="disabled"
                :readonly="readonly"
                :dusk="dusk"
                :autofocus="autofocus"
                @input="emitInput"
                @keyup="handleKeyup($event)"
                @blur="clearErrors"
            >
        </slot>


        <input-error
            :data-error="error"
        ></input-error>

        <input-instructions
            :data-instructions="instructions"
        ></input-instructions>

        <slot name="after"></slot>
    </div>
</template>

<script>
import inputMixin from "mixins/input";

export default {
    mixins: [inputMixin],

    props: {
        dataValue: {
            type: [String, Number],
            default: ""
        },

        dataType: {
            type: String,
            default: "text"
        },

        dataMin: {
            type: [Number, String]
        },

        dataMax: {
            type: [Number, String]
        },

        dataStep: {
            type: [Number, String]
        }
    },

    data() {
        return {
            value: this.dataValue,
            type: this.dataType,
            min: this.dataMin,
            max: this.dataMax,
            step: this.dataStep
        };
    },

    computed: {},

    methods: {},

    watch: {
        formErrors(errors) {
            this.setErrors(errors);
        },

        dataValue() {
            this.value = this.dataValue;
        }
    },

    mounted() {
        this.setFormId().then(formId => {
            window.Bus.$on(`${formId}.submitted`, () => {
                if (this.type === "password") {
                    this.value = "";
                }
            });
        });
    }
};
</script>
