<template>
    <div
    class="business-category-list-wrap"
    v-show="isOpen">
        <ul
        class="business-category-list list-unstyled business-category-parent-list">
            <li
            v-for="(option, parentIndex) in options"
            :key="option.parent.value"
            class="flex flex-wrap">
                <div class="business-category-parent flex items-start">
                    <label
                    class="mb0 mr1"
                    @click.stop>
                        <input
                        type="checkbox"
                        :name="name"
                        :value="option.parent.value"
                        :checked="option.parent.isChecked"
                        :id="name"
                        @change="handleParentChange(option, $event)">

                        {{ option.parent.label }}
                    </label>

                    <i
                    class="fa fa-caret-down business-category-toggle-icon"
                    aria-hidden="true"
                    @click="childrenToggle(parentIndex)"></i>
                </div>

                <ul class="business-category-children"
                v-show="option.childrenOpen">
                    <li
                    v-for="child in option.children"
                    :key="child.value"
                    class="flex items-start">
                        <input
                        type="checkbox"
                        :name="name"
                        :value="child.value"
                        :checked="child.isChecked"
                        :id="`business_category_${child.value}`"
                        @change="handleChildChange(child.value, $event)">

                        <label
                        :for="`business_category_${child.value}`"
                        v-text="child.label"
                        class="mb0 mr1"></label>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</template>
<script>
let _map = require('lodash.map');
let _filter = require('lodash.filter');
let _foreach = require('lodash.foreach');
let _indexof = require('lodash.indexof');

module.exports = {
    props: {
        dataOptions: {
            type: [Array, Object],
            required: true,
        },

        name: {
            type: String,
            required: true,
        },

        values: {
            required: true,
        },

        isOpen: {
            type: Boolean,
            default: false,
        },
    },

    data() {
        return {
            inputValues: null === this.values ? [] : this.values,
            options: this.dataOptions,
        };
    },

    computed: {
        categoryLabels() {
            let labels = [];

            // Go through the options.
            _foreach(this.options, function(option) {
                // Set parent labels.
                labels[option.parent.value] = option.parent.label;

                // Set children labels.
                _foreach(option.children, function(child) {
                    labels[child.value] = child.label;
                });
            });

            // Make sure that all labels are defined.
            let filteredLabels = _filter(labels, function(label) {
                return typeof label !== 'undefined';
            });

            return filteredLabels;
        },

        selectedValues() {
            let selectedValues = [];
            _foreach(this.inputValues, inputValue => {
                selectedValues.push({
                    label: this.categoryLabels[inputValue - 1],
                    value: inputValue,
                });
            });

            return selectedValues;
        },

        selectedDisplayValues() {
            return this.selectedValues;
        },
    },

    methods: {
        setIsChecked() {
            this.options = _map(this.options, option => {
                // Parent
                option.parent.isChecked = this.isChecked(
                    option.parent.value,
                    _indexof(this.inputValues, option.parent.value) !== -1
                );

                // Children
                option.children = _map(option.children, child => {
                    child.isChecked = this.isChecked(child.value);

                    return child;
                });

                return option;
            });
        },

        handleParentChange(option, event) {
            let isChecked = this.inputIsChecked(event);
            let value = option.parent.value;

            // Toggle the parent
            let inputValues = this.toggleInputValue(
                this.inputValues,
                value,
                isChecked
            );

            // Toggle the children
            inputValues = this.toggleAllChildrenCheck(
                value,
                isChecked,
                inputValues
            );

            // Update the input values
            this.inputValues = inputValues;

            this.emitChange();
        },

        toggleAllChildrenCheck(value, isChecked, inputValues) {
            // Go through each option to find the matching value...
            _foreach(this.options, option => {
                let parentValue = parseInt(option.parent.value, 10);
                if (parentValue !== parseInt(value, 10)) {
                    return;
                }

                // Go through each child and toggle them on/off.
                _foreach(option.children, child => {
                    inputValues = this.toggleInputValue(
                        inputValues,
                        child.value,
                        isChecked
                    );
                });
            });

            return inputValues;
        },

        removeParentCheck(value, isChecked, inputValues) {
            // We only want to effect it when a child is unchecked
            // if (isChecked) {
            //     return inputValues;
            // }

            _foreach(this.options, option => {
                let matchingChildren = _filter(option.children, child => {
                    return _indexof(inputValues, child.value) !== -1;
                });

                inputValues = this.toggleInputValue(
                    inputValues,
                    option.parent.value,
                    option.children.length === matchingChildren.length
                );
            });

            return inputValues;
        },

        childrenToggle(index) {
            this.options = _map(this.options, function(value, key) {
                value.childrenOpen =
                    key === index ? !value.childrenOpen : false;

                return value;
            });
        },

        isChecked(value) {
            return _indexof(this.inputValues, value) !== -1;
        },

        toggleInputValue(inputValues, value, isChecked) {
            value = parseInt(value, 10);
            let valueExists = this.isChecked(value);

            // Update the input values...
            // Remove the ones that where not checked and add the ones that are
            if (isChecked && !valueExists) {
                inputValues.push(value);
            } else if (!isChecked && valueExists) {
                inputValues = _filter(inputValues, function(inputValue) {
                    return parseInt(inputValue, 10) !== value;
                });
            }

            return inputValues;
        },

        setInputValue(value, isChecked) {
            return this.toggleInputValue(this.inputValues, value, isChecked);
        },

        setSelectedCounts(inputValues) {
            this.options = _map(this.options, option => {
                return this.setSelectedCount(option, inputValues);
            });
        },

        setSelectedCount(option, inputValues) {
            // Get all of the children
            let count = _filter(option.children, child => {
                return _indexof(inputValues, child.value) !== -1;
            }).length;

            // Add the parent if it is set
            if (_indexof(inputValues, option.parent.value) !== -1) {
                count = count + 1;
            }

            option.selectedCount = count;

            return option;
        },

        handleChildChange(value, event) {
            let isChecked = this.inputIsChecked(event);
            let inputValues = this.setInputValue(value, isChecked);
            inputValues = this.removeParentCheck(value, isChecked, inputValues);

            this.inputValues = inputValues;

            this.emitChange();
        },

        emitChange() {
            this.$emit('select-change', this.selectedValues);
            this.$emit('select-display-change', this.selectedDisplayValues);
        },

        inputIsChecked(event) {
            let target = event.target || event.srcElement;

            return target.checked;
        },
    },

    watch: {
        values() {
            this.inputValues = null === this.values ? [] : this.values;
        },

        inputValues(values) {
            this.setSelectedCounts(values);
            this.setIsChecked();
        },
    },

    mounted() {
        this.inputValues = _map(this.inputValues, value => {
            return parseInt(value, 10);
        });

        // This will allow access to the correct selected values without the
        // parent component(s) having to worry about
        // altering the parent/child options.
        window.Vue.nextTick(() => {
            this.$emit('select-change', this.selectedValues);
        });
    },
};
</script>
