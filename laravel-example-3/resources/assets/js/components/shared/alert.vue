<template>
    <div
    :class="alertClass"
    v-if="value && visible"
    role="alert">
        <button
        type="button"
        class="close"
        aria-label="Close"
        v-if="dismissible"
        @click="close">
            <span aria-hidden="true">&times;</span>
        </button>

        <slot></slot>
    </div>
</template>

<script>
    module.exports = {
        data() {
            return {
                value: this.$slots.default,
                visible: true,
                classMap: {
                    error: 'danger',
                },
            };
        },

        props: {
            type: {
                type: String,
                default: 'info',
            },

            dismissible: {
                type: Boolean,
                default: true,
            },

            timeout: {
                type: Number,
                default: -1,
            }
        },

        computed: {
            alertClass() {
                return [
                    'alert',
                    `alert-${this.getMappedClass()}`,
                    this.dismissible ? 'alert-dismissible' : '',
                ];
            },
        },

        methods: {
            close() {
                this.visible = false;
                this.$emit('closed');
            },

            startTimeout() {
                if (this.timeout > 0 && this.visible) {
                    setTimeout(this.close, this.timeout);
                }
            },

            getMappedClass() {
                if (typeof this.classMap[this.type] !== 'undefined') {
                    return this.classMap[this.type];
                }

                return this.type ? this.type : 'info';
            },
        },

        mounted() {
            this.visible = !!this.value;
            this.startTimeout();
        }
    };
</script>
