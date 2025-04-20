<template>
    <div
    class="fe-input-type-tags-wrap"
    :class="inputWrapClass">
        <input-label
        :input-id="inputId"
        :label="inputLabel"
        :label-class="labelClass"
        :input-value="inputValue"
        :input-maxlength="inputMaxlength"></input-label>

        <input
        type="tags"
        :name="`${name}_value`"
        :id="inputId"
        class="fe-input-type-tags"
        :class="inputClasses"
        :placeholder="inputPlaceholder"
        :disabled="inputDisabled"
        :readonly="inputReadonly"
        v-validate="validationRules"
        v-model="inputValue">

        <input
        type="hidden"
        :name="`${name}[]`"
        :value="tag"
        v-for="tag in tags">

        <input-error-message
        :message="errorMessage"></input-error-message>
    </div>
</template>

<script>
    let inputMixins = require('./../../mixins/input-mixin.js');
    let _filter = require('lodash.filter');
    let _map = require('lodash.map');

    module.exports = {
        name: 'input-tags',

        computed: {
            inputValue() {
                if (null === this.values) {
                    return '';
                }

                return this.value.join(',');
            },
        },

        mixins: [inputMixins],

        computed: {
            tags() {
                let tags = (typeof this.inputValue === 'string') ? this.inputValue.split(',') : this.inputValue;

                // Trim the tags
                tags = _map(tags, function(tag) {
                    return tag.trim();
                });

                // Remove empty tags
                tags = _filter(tags, function(tag) {
                    return tag.trim().length !== 0;
                });

               return  tags;
            }
        },

        props: {
            value: {
                required: true,
            },

            placeholder: {
                type: String,
                default: 'Separate multiple items with commas',
            },
        },
    };
</script>
