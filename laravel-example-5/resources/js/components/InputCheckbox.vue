<template>
    <div
        class="input-checkbox-wrap input-wrap"
        :class="[
            this.error ? 'has-error' : '',
        ]"
    >
        <slot name="before"></slot>

        <div class="flex items-center">
            <slot name="input">
                <div
                    class="input-faux-checkbox"
                    :class="{
                        'disabled': disabled,
                        'readonly': readonly,
                    }"
                >
                    <icon
                        data-name="checkmark"
                        data-icon-class="h-3 w-3 fill-current"
                        v-show="value"
                    ></icon>

                    <input
                        type="checkbox"
                        :name="name"
                        :id="id"
                        class="input-checkbox"
                        :class="inputClass"
                        v-model="value"
                        :disabled="disabled"
                        :readonly="readonly"
                        :dusk="dusk"
                        :autofocus="autofocus"
                        @input="emitInput"
                        @keyup="handleKeyup($event)"
                        @blur="clearErrors"
                    >
                </div>
            </slot>

            <slot name="label">
                <label
                    v-if="label"
                    :for="id"
                    class="input-label leading-none text-sm mb-0"
                    v-text="label"
                    :class="{
                        'pointer-events-none': disabled || readonly
                    }"
                ></label>
            </slot>
        </div>

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
        }
    },

    data() {
        return {
            value: this.dataValue
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
        this.setFormId();
    }
};
</script>
