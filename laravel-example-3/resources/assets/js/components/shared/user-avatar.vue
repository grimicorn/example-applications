<template>
    <div
    class="inline-block"
    :class="{
        'loading': loading
    }"
    :style="style">
        <avatar
        v-if="!loading"
        :src="src"
        :width="width"
        :height="height"
        :image-class="imageClass"
        :uses-two-factor="usesTwoFactorAuth"></avatar>
    </div>
</template>

<script>
export default {
    props: {
        dataUserId: {
            type: [Number, String],
            default: window.Spark.userId
        },

        dataImageClass: {
            type: String,
            default: ""
        },

        dataSize: {
            type: String,
            default: "thumbnail_small"
        },

        dataLoadingHeight: {
            type: [String, Number],
            default: 44
        },

        dataLoadingWidth: {
            type: [String, Number],
            default: 44
        }
    },

    data() {
        return {
            size: this.dataSize,
            imageClass: this.dataImageClass,
            userId: this.dataUserId,
            images: undefined,
            loading: true
        };
    },

    computed: {
        usesTwoFactorAuth() {
            return window.Spark.uses2FA;
        },

        getUrl() {
            return `/dashboard/profile/${this.userId}/images`;
        },

        image() {
            if (
                this.images === undefined ||
                this.images.photo_urls === undefined
            ) {
                return undefined;
            }

            if (this.images.photo_urls[this.size] === undefined) {
                return {
                    src: "",
                    width: this.dataLoadingWidth,
                    height: this.dataLoadingHeight
                };
            }

            return this.images.photo_urls[this.size];
        },

        src() {
            if (this.image === undefined) {
                return "";
            }

            return this.image.src === undefined ? "" : this.image.src;
        },

        width() {
            if (this.image === undefined) {
                return "";
            }

            return this.image.width === undefined ? "" : this.image.width;
        },

        height() {
            if (this.image === undefined) {
                return "";
            }

            return this.image.height === undefined ? "" : this.image.height;
        },

        style() {
            if (!this.loading) {
                return {};
            }

            return {
                width: `${this.dataLoadingWidth}px`,
                height: `${this.dataLoadingHeight}px`,
                opacity: 0
            };
        }
    },

    methods: {
        update() {
            window.axios
                .get(this.getUrl)
                .then(response => {
                    this.images = response.data;
                    window.Vue.nextTick(() => (this.loading = false));
                })
                .catch(() => {
                    this.loading = false;
                    this.images = [];
                });
        },

        handleProfileFormSubmssion() {
            window.Bus.$on(
                "application-profile-edit.successfully-submitted",
                this.update
            );
        }
    },

    created() {
        this.update();
        this.handleProfileFormSubmssion();
    }
};
</script>
