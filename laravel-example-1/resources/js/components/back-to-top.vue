<template>
  <a
    href="#"
    @click.prevent.stop="scrollToTop"
    class="fixed bottom-0 right-0 z-40 leading-none text-white transition-opacity duration-500 rounded-full bg-primary-900"
    :title="label"
    :class="{
      'pointer-events-none opacity-0 h-0 w-0 m-0 p-0 overflow-hidden': !isDisplayed,
      'opacity-90 hover:opacity-100 p-2 mb-2 mr-2': isDisplayed,
    }"
  >
    <icon data-name="arrow-up" class="w-5 h-5" />
    <span v-text="label" class="sr-only"></span>
  </a>
</template>

<script>
import _debounce from "lodash.debounce";

export default {
  props: {},

  data() {
    return {
      label: "Scroll to the top of the page.",
      isDisplayed: false,
    };
  },

  computed: {},

  methods: {
    scrollToTop() {
      window.scrollTo({
        top: 0,
        left: 0,
        behavior: "smooth",
      });
    },

    setIsDisplayed() {
      const displayedPastHeight = document.body.scrollHeight * 0.25;
      this.isDisplayed = window.scrollY > displayedPastHeight;
    },

    watchScroll() {
      document.addEventListener(
        "scroll",
        _debounce(() => {
          this.setIsDisplayed();
        }, 300)
      );
    },
  },

  mounted() {
    setTimeout(() => {
      this.setIsDisplayed();
      this.watchScroll();
    }, 1000);
  },
};
</script>
