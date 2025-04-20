<template>
    <div class="feature-switcher feature-switcher-small container">
        <div class="row">
            <div
            v-for="(feature, index) in features"
            class="feature-switcher-details-wrap col-xs-12 text-center">
                <i
                class="feature-switcher-icon fc-color5"
                :class="feature.iconClass"></i>

                 <h3
                class="feature-switcher-title fc-color5"
                v-text="feature.title"></h3>

                <div
                class="feature-switcher-content"
                v-text="feature.content"></div>

                <a
                v-if="feature.href"
                class="btn btn-color5 btn-ghost"
                :href="feature.href">Learn More</a>
            </div>
        </div>
    </div>
</template>

<script>
    module.exports = {
        data() {
            return {
                current: 0,
            };
        },

        computed: {
            currentFeature() {
                if (typeof this.features[this.current] === 'undefined') {
                    return false;
                }

                return this.features[this.current];
            },

            currentFeatureStyle() {
                let currentFeature = this.currentFeature;

                if (!currentFeature || ! currentFeature.imageUrl) {
                    return '';
                }

                return `background-image: url('${this.currentFeature.imageUrl}');`;
            },
        },

        props: {
            features: {
                type: Array,
                required: true,
            },
        },
    };
</script>
