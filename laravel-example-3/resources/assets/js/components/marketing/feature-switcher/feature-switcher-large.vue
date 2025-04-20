<template>
    <div class="feature-switcher feature-switcher-large">
        <nav class="feature-switcher-navigation-wrap">
            <ul
            class="feature-switcher-navigation list-plain clearfix">
                <li
                v-for="(feature, index) in features"
                :class="{'is-active' : isCurrent(index)}"
                :style="navigationItemStyle"
                @click="handleNavigationClick(index)">
                    <i
                    class="feature-switcher-navigation-icon"
                    :class="feature.iconClass"></i>

                    <div
                    class="feature-switcher-navigation-title"
                    v-text="feature.title"></div>
                </li>
            </ul>
        </nav>

        <div
        class="feature-switcher-details-wrap  bg-color5 fc-color2"
        v-if="current">
            <div
            class="feature-switcher-content-image"
            :style="currentStyle"></div>

            <div class="feature-switcher-content-wrap">
                <div class="inner">
                    <h3
                    class="feature-switcher-content-title fc-color2"
                    v-text="current.title"></h3>

                    <div
                    class="feature-switcher-content"
                    v-text="current.content"></div>

                    <a
                    v-if="current.href"
                    class="btn btn-color2 btn-ghost"
                    :href="current.href">Learn More</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    module.exports = {
        data() {
            return {
                currentIndex: 0,
                autoAdvanceInterval: false,
                autoAdvanceMilliseconds: 6000,
            };
        },

        computed: {
            current() {
                if (typeof this.features[this.currentIndex] === 'undefined') {
                    return false;
                }

                return this.features[this.currentIndex];
            },

            currentStyle() {
                let current = this.current;

                if (!current || ! current.imageUrl) {
                    return '';
                }

                return `background-image: url('${this.current.imageUrl}');`;
            },

            navigationItemStyle() {
                let widthPercentage = (100 / this.features.length);

                return `width: ${widthPercentage}%;`;
            },
        },

        methods: {
            handleNavigationClick(index) {
                this.stopAutoAdvance();

                this.setCurrent(index);
            },

            setCurrent(index) {
                let maxIndex = this.features.length - 1;

                // We have tried to go past the start lets loop around to the end.
                if (index < 0) {
                     this.currentIndex = maxIndex;

                     return;
                }

                // We have tried to go past the end lets go back to the beginning.
                if (index > maxIndex) {
                    this.currentIndex = 0;

                    return;
                }

                // We are between the min and maximum indexes so just set the index.
                this.currentIndex = index
            },

            isCurrent(index) {
                return this.currentIndex === index;
            },

            advance() {
                this.setCurrent(this.currentIndex + 1);
            },

            startAutoAdvance() {
                // We only want to allow auto advance to happen one at a time.
                if (this.autoAdvanceInterval !== false) {
                   return;
                }

                 this.autoAdvanceInterval = setInterval(() => {
                    this.advance();
                }, this.autoAdvanceMilliseconds);
            },

            stopAutoAdvance() {
                clearInterval(this.autoAdvanceInterval);
                this.autoAdvanceInterval = false;
            },
        },

        props: {
            features: {
                type: Array,
                required: true,
            },
        },

        mounted() {
            this.startAutoAdvance();
        },

        destroyed() {
            this.stopAutoAdvance();
        },
    };
</script>
