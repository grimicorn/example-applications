<template>
    <div
        class="input-toggle-wrap input-wrap"
        :class="[
            this.error ? 'has-error' : '',
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
            <div :class="inputClass">
                <div
                    class="input-toggle-labels"
                    :class="{
                        'is-on': value,
                        'is-off': !value,
                        'disabled': disabled,
                    }"
                    @click="toggleValue"
                >
                    <label
                        v-if="label"
                        :for="`${id}_on`"
                        v-text="dataFalseLabel"
                        @click.prevent
                    ></label>
                    <label
                        v-if="label"
                        :for="`${id}_on`"
                        v-text="dataTrueLabel"
                        @click.prevent
                    ></label>
                </div>

                <input
                    type="radio"
                    :dusk="`${dusk}_off`"
                    :disabled="disabled"
                    :readonly="readonly"
                    :name="name"
                    :id="`${id}_on`"
                    class="input-toggle input-hidden pointer-events-none"
                    :checked="!value"
                    value="0"
                    @input="emitInput"
                    @blur="clearErrors"
                    @change="clearErrors"
                >

                <input
                    type="radio"
                    :dusk="`${dusk}_on`"
                    :disabled="disabled"
                    :readonly="readonly"
                    :name="name"
                    :id="`${id}_on`"
                    class="input-toggle input-hidden pointer-events-none"
                    :checked="value"
                    value="1"
                    @input="emitInput"
                    @blur="clearErrors"
                >
            </div>
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
            type: Boolean,
            default: false
        },

        dataTrueLabel: {
            type: String,
            default: "On"
        },

        dataFalseLabel: {
            type: String,
            default: "Off"
        }
    },

    data() {
        return {
            value: this.dataValue,
            trueLabel: this.dataTrueLabel,
            falseLabel: this.dataFalseLabel
        };
    },

    computed: {},

    methods: {
        toggleValue() {
            if (this.disabled) {
                return;
            }

            this.value = !this.value;

            this.clearErrors();

            this.$emit("input", this.value);
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
