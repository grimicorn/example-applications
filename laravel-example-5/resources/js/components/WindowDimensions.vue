<template>
    <div>
        <span v-text="`Window Width: ${windowWidth}px`" class="block"></span>
        <span v-text="`Window Height: ${windowHeight}px`" class="block"></span>
    </div>
</template>

<script>
import _debounce from "lodash.debounce";

export default {
    props: {},

    data() {
        return {
            windowWidth: null,
            windowHeight: null
        };
    },

    computed: {},

    methods: {
        getWidth() {
            return document.querySelector("body").offsetWidth;
        },

        getHeight() {
            return document.querySelector("body").offsetHeight;
        }
    },

    mounted() {
        this.windowWidth = this.getWidth();
        this.windowHeight = this.getHeight();

        let vm = this;
        window.addEventListener(
            "resize",
            _debounce(function() {
                vm.windowWidth = vm.getWidth();
                vm.windowHeight = vm.getHeight();
            })
        );
    }
};
</script>
