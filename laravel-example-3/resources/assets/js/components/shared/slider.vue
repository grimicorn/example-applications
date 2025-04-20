<template>
    <div class="slider-wrap">
        <agile
        v-if="slidesExist"
        :fade="true"
        :arrows="arrows"
        :dots="dots">
            <div
            v-for="slide in slides"
            :key="slide.url"
            class="slide text-center pointer"
            @click="openLightbox(slide)">
                <img :src="slide.url" :alt="slide.name">
            </div>
        </agile>

        <lightbox-slider
        :data-slides="slides"
        :data-opened="lightboxOpened"
        @closed="closeLightbox"></lightbox-slider>
    </div>
</template>

<script>
export default {
    props: {
        dataSlides: {
            type: [Array, Object],
            required: true
        }
    },

    data() {
        return {
            lightboxSlide: this.defaultSlide(),
            lightboxOpened: false
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
        }
    },

    methods: {
        openLightbox(slide) {
            this.lightboxOpened = true;
            this.lightboxSlide = slide;
        },

        closeLightbox() {
            this.lightboxOpened = false;
            this.lightboxSlide = this.defaultSlide();
        },

        defaultSlide() {
            return {
                lightbox_url: "",
                url: "",
                name: ""
            };
        }
    }
};
</script>
