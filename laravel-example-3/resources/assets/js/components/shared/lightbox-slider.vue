<template>
    <div>
        <modal
        class="modal-lightbox-slider"
        :display-button="false"
        :data-modal-id="modalId"
        @opened="open"
        @closed="close">
            <agile
            v-if="slidesExist"
            :fade="true"
            :arrows="arrows"
            :dots="dots">
                <div
                v-for="slide in slides"
                :key="slide.url"
                class="slide text-center pointer">
                    <img :src="slide.lightbox_url" :alt="slide.name">
                </div>
            </agile>
        </modal>
    </div>
</template>

<script>
let uuidv1 = require("uuid/v1");

export default {
    props: {
        dataSlides: {
            type: [Array, Object],
            default() {
                return [];
            }
        },
        dataOpened: {
            type: Boolean,
            default: false
        }
    },

    data() {
        return {
            modalId: uuidv1()
        };
    },

    computed: {
        slides() {
            return Object.values(this.dataSlides);
        },

        slidesExist() {
            return this.slides.length > 0;
        },

        arrows() {
            return this.slides.length > 1;
        },

        dots() {
            return this.slides.length > 1;
        },

        opened: {
            get() {
                return this.dataOpened;
            },

            set() {}
        }
    },

    methods: {
        close() {
            this.$emit("closed");
            window.Bus.$emit(`modal-should-close.${this.modalId}`);
            this.opened = false;
        },

        open() {
            this.$emit("opened");
            window.Bus.$emit(`modal-should-open.${this.modalId}`);
            this.opened = false;
        }
    },

    watch: {
        opened(value) {
            if (!value) {
                this.close();
                return;
            }

            this.open();
        }
    }
};
</script>
