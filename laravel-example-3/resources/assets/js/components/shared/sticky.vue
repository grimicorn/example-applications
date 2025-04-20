<template>
  <div
    class="sticky-wrap"
    :class="{
            'is-stuck': isStuck,
            'not-stuck': !isStuck,
        }"
  >
    <div
      class="sticky-content"
      :style="style"
    >
      <slot></slot>
    </div>
  </div>
</template>

<script>
let uuidv1 = require("uuid/v1");
export default {
  props: {
    dataOffset: {
      type: Number,
      default: 0
    }
  },

  data() {
    return {
      id: "sticky_" + uuidv1(),
      isStuck: false,
      offset: this.dataOffset
    };
  },

  computed: {
    style() {
      if (!this.isStuck) {
        return {};
      }

      return {
        position: "fixed",
        top: `${this.offset}px`,
        width: `${this.$el.offsetWidth}px`
      };
    }
  },

  methods: {
    setIsSticky() {
      this.isStuck = this.$el.getBoundingClientRect().top <= this.offset;
    }
  },

  mounted() {
    this.setIsSticky();
    window.addEventListener("scroll", () => {
      this.setIsSticky();
    });
  }
};
</script>
