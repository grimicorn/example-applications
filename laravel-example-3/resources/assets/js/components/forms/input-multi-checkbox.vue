<template>
    <div
    class="fe-input-type-multi-checkbox-wrap"
    :class="checkboxWrapClass">
        <label
        v-if="inputLabel"
        v-text="inputLabel"
        :class="labelClass"></label>

        <ul class="fe-input-type-multi-checkboxes list-unstyled form-list">
            <li
            v-for="input in checkboxInputs"
            :key="input.value">
                <input-checkbox
                :name="`${name}[${input.label}]`"
                :label="input.label"
                :value="input.value"
                @change="inputChanged"></input-checkbox>
            </li>

            <li v-if="isRequired || errorMessage">
                <input-hidden
                :data-ignore-dirty-check="true"
                :value="selectedCheckboxes.length === 0 ? '' : 'selected'"
                :name="`${name}_is_selected`"
                :validation="isRequired ? 'required' : ''"
                :label="label"
                :validation-message="errorMessage"></input-hidden>
            </li>
        </ul>
    </div>
</template>

<script>
let inputMixins = require("./../../mixins/input-mixin.js");
let _map = require("lodash.map");
let _foreach = require("lodash.foreach");
let _filter = require("lodash.filter");
let _merge = require("lodash.merge");

module.exports = {
    mixins: [inputMixins],

    data() {
        return {
            selectedCheckboxes: [],
            checkboxHasErrors: this.hasErrors
        };
    },

    computed: {
        checkboxInputs() {
            let values = this.values === null ? [] : this.values;
            let inputs = this.convertInputsProp(this.inputs);
            inputs = this.setCheckedInputs(inputs, values);

            return inputs;
        },

        checkboxWrapClass() {
            return _merge(this.inputWrapClass, [
                this.checkboxHasErrors ? "has-error" : ""
            ]);
        }
    },

    methods: {
        setCheckedInputs(inputs, values) {
            return _map(inputs, input => {
                let checked = -1 !== values.indexOf(input.value);
                input.value = checked ? input.value : "";

                return input;
            });
        },

        inputChanged(changedInput) {
            this.serverValidationMessage = "";

            let name = changedInput.name
                .replace(`${this.name}[`, "")
                .replace("]", "");

            _foreach(this.convertInputsProp(this.inputs), input => {
                if (input.label !== name) {
                    return;
                }

                if (changedInput.value) {
                    this.selectedCheckboxes.push(input.label);
                } else {
                    this.selectedCheckboxes = _filter(
                        this.selectedCheckboxes,
                        label => {
                            return input.label !== label;
                        }
                    );
                }
            });
        }
    },

    props: {
        inputs: {
            required: true
        },

        values: {
            default() {
                return [];
            }
        }
    },

    mounted() {
        window.Bus.$on(`${this.name}_is_selected:invalid-input`, () => {
            this.checkboxHasErrors = true;
        });
        window.Bus.$on(`${this.name}_is_selected:valid-input`, () => {
            this.checkboxHasErrors = false;
        });
    }
};
</script>
