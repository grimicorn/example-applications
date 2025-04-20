<template>
    <div
    class="form-accordion clearfix"
    :class="{
        'is-open': isOpen,
        'is-closed': !isOpen,
        'is-collapsible': collapsible,
        'disable-padding': disablePadding,
    }">
        <div
        class="form-accordion-header"
        @click="toggle">
            <i
            class="form-accordion-header-icon"
            aria-hidden="true"
            v-if="collapsible"></i>

            <span
            class="form-accordion-header-title"
            v-text="headerTitle"></span>

            <slot name="header-right"></slot>
        </div>

        <div
        class="form-accordion-content"
        :class="{
            'disable-padding': disablePadding,
        }">
            <slot name="content"></slot>
        </div>
    </div>
</template>
<script>
    module.exports = {
        data() {
            return {
                isOpen: this.collapsible ? this.defaultOpen : true,
            };
        },

        props: {
            headerTitle: {
                type: String,
                required: true,
            },

            defaultOpen: {
                type: Boolean,
                default: true,
            },

            collapsible: {
                type: Boolean,
                default: true,
            },

            disablePadding: {
                type: Boolean,
                default: false,
            },
        },

        methods: {
            toggle() {
                if (this.collapsible) {
                    this.isOpen = !this.isOpen;
                }
            }
        },
    };
</script>
