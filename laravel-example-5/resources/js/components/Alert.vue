<style lang="scss" scoped>
    .fade-enter-active,
    .fade-leave-active {
        transition: opacity 0.5s;
    }

    .fade-enter,
    .fade-leave-to {
        opacity: 0;
    }
</style>

<template>
    <transition name="fade">
        <div
            v-if="visible"
            class="border-l-4 p-4 mb-2 flex"
            :class="wrapClass"
            role="alert"
            :dusk="`${type}_alert`"
        >
            <div
                :class="{'mr-2': dismissible}"
                class="flex-1"
            >
                <span class="font-bold block">
                    <icon
                        data-icon-class="cursor-pointer h-3 w-3 fill-current"
                        :data-name="iconName"
                    ></icon>

                    {{ label }}
                </span>
                <slot></slot>
            </div>


            <div v-if="dismissible" @click="close">
                <icon
                    data-icon-class="cursor-pointer h-3 w-3 fill-current"
                    data-name="close"
                    alt="Close"
                ></icon>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    store: ['removeAlert'],

    props: {
        dataDismissible: {
            type: Boolean,
            default: true
        },

        dataType: {
            type: String,
            required: true,
            validator(value) {
                return (
                    ["success", "info", "warning", "danger"].indexOf(value) !==
                    -1
                );
            }
        },

        dataTimeout: {
            type: Number,
            default: 0,
            validator(value) {
                return parseInt(value, 10) >= 0;
            }
        }
    },

    data() {
        return {
            labels: {
                info: "Info",
                success: "Success",
                warning: "Warning",
                danger: "Danger"
            },

            iconNames: {
                info: "information-outline",
                success: "checkmark-outline",
                warning: "minus-outline",
                danger: "exclamation-outline"
            },

            visible: true
        };
    },

    computed: {
        label() {
            if (typeof this.labels[this.type] !== undefined) {
                return this.labels[this.type];
            }

            return "";
        },

        type() {
            return this.dataType;
        },

        dismissible() {
            return this.dataDismissible;
        },

        timeout() {
            return this.dataTimeout;
        },

        wrapClass() {
            return `alert-${this.type}`;
        },

        iconName() {
            if (typeof this.iconNames[this.type] !== undefined) {
                return this.iconNames[this.type];
            }

            return "notifications-outline";
        }
    },

    methods: {
        close() {
            this.visible = false;
            this.removeAlert(this.$slots.default[0].text);
        },

        startTimeout() {
            if (this.timeout > 0) {
                setTimeout(this.close, this.timeout);
            }
        }
    },

    mounted() {
        this.startTimeout();
    }
};
</script>
