<template>
<div
class="avatar-wrap"
:class="{
    'has-2fa-icon': usesTwoFactor,
    'no-photo': !imageSrc
}">
    <div
    :style="fontStyle"
    class="relative block clearfix">
        <i
        class="fa fa-lock icon-2fa"
        aria-hidden="true"
        v-if="usesTwoFactor"></i>

        <img
        v-if="imageSrc"
        :src="imageSrc"
        :width="width"
        :height="height"
        class="avatar-photo"
        :class="imageClass">

        <span
        v-if="!imageSrc"
        v-text="userInitials"
        class="avatar-initials fc-color2"></span>

        <img
        v-if="!imageSrc"
        :src="defaultImageSrc"
        :width="width"
        :height="height"
        class="avatar-photo"
        :class="imageClass">
    </div>
</div>

</template>

<script>
module.exports = {
    name: 'avatar',

    props: {
        src: {
            type: String,
            default: '',
        },

        width: {
            type: String,
            default: '',
        },

        height: {
            type: String,
            default: '',
        },

        imageClass: {
            type: String,
            default: '',
        },

        initials: {
            type: String,
            default: '',
        },

        displayInitials: {
            type: Boolean,
            default: false,
        },

        usesTwoFactor: {
            type: Boolean,
            default: false,
        },
    },

    data() {
        return {
            defaultImageSrc: '/img/defaults/user/avatar.jpg',
            currentHeight: this.height,
            currentWidth: this.width,
        };
    },

    computed: {
        fontSize() {
            return this.currentHeight;
        },

        fontStyle() {
            return {
                'font-size': `${this.fontSize}px`,
            };
        },

        userInitials() {
            return this.initials ? this.initials : this.currentUserInitials;
        },

        imageSrc() {
            if (!this.src) {
                return null;
            }

            let isDefault = this.src.indexOf('img/defaults') !== -1;

            return isDefault ? null : this.src;
        },

        currentUserInitials() {
            if (this.displayInitials) {
                return '';
            }

            if (typeof window.Spark.userInitials === 'undefined') {
                return '';
            }

            return window.Spark.userInitials;
        },
    },

    methods: {
        setDimensions() {
            // this.currentHeight = this.$el.offsetHeight;
            // this.currentWidth = this.$el.offsetWidth;
        },
    },

    mounted() {
        // setTimeout(this.setDimensions, 100);
        // setTimeout(this.setDimensions, 500);
        // setTimeout(this.setDimensions, 1000);
        // window.addEventListener('resize', this.setDimensions, true);
    },
};
</script>
