<template>
    <div
    class="fe-input-type-business-category-select-wrap"
    :class="inputWrapClass">
        <input-label
        :input-id="inputId"
        :label="inputLabel"
        :label-class="labelClass"
        class="width-auto pull-left"></input-label>


        <div class="fe-input-type-business-category-select">
            <div
            class="fe-input-type-business-category-select-overlay"
            @click.stop="toggle">
                <select
                class="fe-input-type-select"
                readonly="readonly"
                @focus.prevent="open"
                @blur="close">
                    <option  selected="selected" v-text="selectPlaceholder"></option>
                </select>
            </div>

            <input-business-category-select-dropdown
            :name="name"
            :values="inputValue"
            :is-open="isOpen"
            :data-options="selectOptions"
            @select-change="handleSelectChange"
            @select-display-change="handleDisplaySelectChange"></input-business-category-select-dropdown>
        </div>

        <input-error-message
        :message="errorMessage"></input-error-message>
    </div>
</template>

<script>
let _map = require('lodash.map');
let _foreach = require('lodash.foreach');
let _indexof = require('lodash.indexof');
let _filter = require('lodash.filter');

module.exports = {
    mixins: [
        require('./../../mixins/input-mixin.js'),
        require('./../../mixins/utilities.js'),
    ],

    components: {
        'input-business-category-select-dropdown': require('./input-business-category-select-dropdown.vue'),
    },

    props: {
        dataCountTextColorClass: {
            type: String,
            default: 'fc-color4',
        },

        value: {
            type: Array,
            default() {
                return [];
            },
        },

        placeholder: {
            type: String,
            default: 'Any Category',
        },
    },

    data() {
        return {
            isOpen: false,
            inputValue: [],
            selectOptions: this.getOptions(),
        };
    },

    computed: {
        oldCategories() {
            if (typeof window.Spark.old === 'undefined') {
                return;
            }

            return window.Spark.old.business_categories;
        },

        categories() {
            ids = [];

            _foreach(this.selectOptions, category => {
                ids.push({
                    label: category.parent.label,
                    id: category.parent.value,
                    parentId: 0,
                });

                _foreach(category.children, child => {
                    ids.push({
                        label: child.label,
                        id: child.value,
                        parentId: category.parent.value,
                    });
                });
            });

            return ids;
        },

        allSelectedChildrenCategories() {
            return _filter(this.selectedCategories, category => {
                return category.parentId !== 0;
            });
        },

        allSelectedParentCategories() {
            return _filter(this.selectedCategories, category => {
                return category.parentId === 0;
            });
        },

        selectedCategories() {
            return Object.values(
                _filter(this.categories, category => {
                    return this.categorySelected(category.id);
                })
            );
        },

        displaySelectedParentCategories() {
            return _filter(this.allSelectedParentCategories, category => {
                return this.allChildrenSelected(category.id);
            });
        },

        selectedParentCount() {
            return this.displaySelectedParentCategories.length;
        },

        displaySelectedChildrenCategories() {
            return _filter(this.allSelectedChildrenCategories, category => {
                return !this.allChildrenSelected(category.parentId);
            });
        },

        selectedChildrenCount() {
            return this.displaySelectedChildrenCategories.length;
        },

        selectedCount() {
            return this.selectedChildrenCount + this.selectedParentCount;
        },

        selectPlaceholder() {
            // It is kind of counterintuitive but here is the logic behind it...
            // If you have a parent selected it selects all the children so instead of
            // counting all of them it counts 1 parent and no children.
            // If you have only 1 child of 1 parent selected then you have 1 which makes sense.
            if (this.selectedCount > 1) {
                return this.countPlaceholder;
            }

            // Fall back to the default
            return this.categoryPlaceholder;
        },

        countPlaceholder() {
            return `Categories Selected (${this.selectedCount})`;
        },

        categoryPlaceholder() {
            // Nothing is selcted thats easy we can just use the default.
            if (this.selectedCount === 0) {
                return this.placeholder;
            }

            // If the selected parent count is 0
            if (this.selectedParentCount === 0) {
                return this.childCategoryPlaceholder;
            }

            return this.parentCategoryPlaceholder;
        },

        childCategoryPlaceholder() {
            let categories = Object.values(this.allSelectedChildrenCategories);

            return categories[0] ? categories[0].label : this.countPlaceholder;
        },

        parentCategoryPlaceholder() {
            let categories = Object.values(this.allSelectedParentCategories);

            return categories[0] ? categories[0].label : this.countPlaceholder;
        },
    },

    methods: {
        getCategoryChildren(parentId) {
            return _filter(this.categories, category => {
                return category.parentId === parentId;
            });
        },

        getCategorySelectedChildren(parentId) {
            return _filter(this.allSelectedChildrenCategories, category => {
                return category.parentId === parentId;
            });
        },

        categorySelected(id) {
            return _indexof(this.inputValue, id) !== -1;
        },

        allChildrenSelected(parentId) {
            return (
                this.getCategorySelectedChildren(parentId).length ===
                this.getCategoryChildren(parentId).length
            );
        },

        getOptions() {
            if (typeof window.Spark.businessCategories === 'undefined') {
                return [];
            }

            return _map(window.Spark.businessCategories, (value, key) => {
                value.childrenOpen = false;

                return value;
            });
        },

        getInputValue() {
            if (typeof this.oldCategories === 'undefined') {
                return this.value;
            }

            return _map(this.oldCategories, category => {
                return parseInt(category, 10);
            });
        },

        toggle() {
            this.isOpen = !this.isOpen;
        },

        open() {
            this.isOpen = true;
        },

        close() {
            this.isOpen = false;
        },

        handleDisplaySelectChange(selectedValues) {
            let displaySelectedValues = this.displaySelectedParentCategories.concat(
                this.displaySelectedChildrenCategories
            );

            // This sends the "selected" values based on the criteria for checkboxes
            this.$emit('select-display-change', displaySelectedValues);
        },

        handleSelectChange(selectedValues) {
            this.inputValue = _map(selectedValues, category => {
                return category.value;
            });

            // This sends the actual selected values.
            this.$emit('select-change', selectedValues);
        },
    },

    mounted() {
        this.inputValue = this.getInputValue();
        this.listenClickOutside(this.close);
        this.listenClickOutside(this.close, '.modal-content');
    },
};
</script>
