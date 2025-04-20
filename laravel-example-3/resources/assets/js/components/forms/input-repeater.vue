<template>
    <div class="input-repeater clearfix">
        <input-label
            :label="label"
            :label-class="labelClass"
            :tooltip="dataTooltip"
        ></input-label>

        <ul class="list-unstyled input-repeater-list form-list">
            <li
            v-for="(input,index) in inputItems"
            :key="input.index">
                <div class="input-repeater-inputs">
                    <slot
                    :input="input.value"
                    :index="input.index"
                    :repeat-index="index">
                    </slot>
                </div>

                <div
                class="input-repeater-remove"
                @click="removeRow(input.index)">
                    <i class="fa fa-minus-circle input-repeater-icon" aria-hidden="true"></i>
                </div>
            </li>
        </ul>

        <div
        class="input-repeater-add-new inline"
        :class="{
            'pull-right': rightAlignAddNewLabel,
        }"
        @click="addRow">
            <span
            class="input-repeater-add-new-label"
            v-text="addNewLabel"></span>
            <i class="fa fa-plus-circle input-repeater-icon" aria-hidden="true"></i>
        </div>
    </div>
</template>
<script>
let _map = require('lodash.map');
let _filter = require('lodash.filter');

module.exports = {
    mixins: [require('./../../mixins/confirm.js')],

    data() {
        return {
            inputItems: this.defaultInputItems(),
            labelClass: ['input-repeater-label fe-input-label'],
        };
    },

    methods: {
        addRow() {
            this.inputItems.push(this.generateNewValue());
            this.$emit('added');
        },

        remove(index) {
            this.inputItems = _filter(this.inputItems, function(item) {
                return item.index !== index;
            });

            // Make sure there is at least one row at all times.
            if (this.inputItems.length === 0) {
                this.inputItems.push(this.generateNewValue());
            }

            this.$emit('removed', this.inputItems);
        },

        removeRow(index) {
            let id = `input-repeater-${Math.floor(Date.now())}`;
            let content =
                'Are you sure you want to delete this item? This can not be undone once saved.';

            this.confirm(id, content, () => {
                this.remove(index);
            });
        },

        generateIndex() {
            let n = Math.floor(Math.random() * 11);
            let k = Math.floor(Math.random() * 1000000);

            return new Date().getTime() + n + k;
        },

        generateNewValue() {
            return {
                index: this.generateIndex(),
                value: this.defaultValue,
            };
        },

        defaultInputItems() {
            let defaultValue = this.generateNewValue();

            if (this.values === null || this.values.length === 0) {
                return [defaultValue];
            }

            return _map(this.values, function(value, key) {
                let item = {};
                item.index = key;
                item.value = value;

                return item;
            });
        },
    },

    props: {
        label: {
            type: String,
            default: '',
        },

        values: {
            default() {
                return [];
            },
        },

        defaultValue: {
            required: true,
        },

        addNewLabel: {
            type: String,
            default: 'Add new',
        },

        rightAlignAddNewLabel: {
            type: Boolean,
            default: false,
        },

        dataTooltip: {
            type: String,
            default: '',
        },
    },
};
</script>
