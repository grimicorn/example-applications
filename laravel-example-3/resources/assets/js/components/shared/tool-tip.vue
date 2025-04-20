<template>
    <div class="inline-block relative">
        <span @click="toggle">
            <slot name="icon">
                <i
                :class="iconClass"
                class="fa fa-info-circle relative pointer"></i>
            </slot>
        </span>

        <div
        v-show="isOpen"
        class="tooltip bt1 fz-14 bb1 bl1 br1 pa2 fc-color7 lh-copy pop"
        :class="[
            this.currentDirection,
        ]">
            <button
            @click="close"
            v-if="this.autoOpen"
            class="tooltip-close fa fa-times"
            type="button"></button>

            <div class="arrow"></div>

            <div class="tooltip-content">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script>
module.exports = {
    mixins: [require('./../../mixins/utilities.js')],

    props: {
        direction: {
            type: String,
            default: 'right',
        },

        autoOpen: {
            type: Boolean,
            default: false,
        },

        dataIconClass: {
            type: String,
            default: 'fc-color4',
        },
    },

    data() {
        return {
            iconClass: this.dataIconClass,
            currentDirection: this.direction,
            $tooltip: undefined,
            tooltipHeight: undefined,
            tooltipWidth: undefined,
            isOpen: this.autoOpen,
        };
    },

    computed: {},

    methods: {
        align() {
            // Setup the tooltip element
            this.setTooltipData();

            // Align the tooltip
            this.alignVertical();
            this.alignHorizontal();
        },

        alignVertical() {
            let direction = this.currentDirection;

            // Handle aligning right and left vertically.
            if (direction === 'right' || direction === 'left') {
                this.$tooltip.style.marginTop = `${this.tooltipHeight /
                    2 *
                    -1}px`;
            }
        },

        alignHorizontal() {
            let direction = this.currentDirection;

            // Handle top and bottom horizontally.
            if (direction === 'top' || direction === 'bottom') {
                this.$tooltip.style.marginLeft = `${this.tooltipWidth /
                    2 *
                    -1}px`;
            }

            if (direction === 'left') {
                this.$tooltip.style.marginLeft = `${(this.tooltipWidth + 15) *
                    -1}px`;
            }
        },

        setTooltipData() {
            this.$tooltip = this.$el.querySelectorAll('.tooltip')[0];
            this.tooltipHeight = this.$tooltip.offsetHeight;
            this.tooltipWidth = this.$tooltip.offsetWidth;
        },

        toggle() {
            this.isOpen = !this.isOpen;
            window.Vue.nextTick(this.align);
        },

        close() {
            this.isOpen = false;
        },
    },

    mounted() {
        this.listenClickOutside(this.close);

        // Align the tooltip when mounted.
        this.align();

        // Align the tooltip when resized.
        window.addEventListener(
            'resize',
            () => {
                this.align();
            },
            true
        );
    },
};
</script>
