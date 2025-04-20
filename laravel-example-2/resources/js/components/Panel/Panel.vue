<style>
  @import "./Panel.css";
</style>

<template>
  <div>
    <button
      type="button"
      @click="toggle"
      class="w-full"
    >
      <slot
        name="toggle"
        :isOpen="isOpen"
      ></slot>
    </button>

    <portal
      to="panel"
      v-if="isOpen"
      multiple
    >
      <div class="panel">
        <div>
          <div class="panel-header">
            <button
              type="button"
              class="panel-close"
              @click="close"
            >
              <icon data-name="arrow-right"></icon>
            </button>
            <div class="header-content">
              <slot name="header"></slot>
            </div>
          </div>
          <div class="panel-content">
            <slot>
            </slot>
          </div>
        </div>
      </div>
    </portal>

  </div>
</template>

<script>
export default {
  props: {
    dataOpened: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {
      isOpen: this.dataOpened
    };
  },

  computed: {},

  methods: {
    toggle() {
      if (this.isOpen) {
        return this.close();
      }

      this.open();
    },

    open() {
      this.isOpen = true;
      this.$emit("opened");
    },

    close() {
      this.isOpen = false;
      this.$emit("closed");
    }
  },

  watch: {
    dataOpened(value) {
      this.isOpen = value;
    }
  },

  mounted() {
    document.querySelector("body").addEventListener("keydown", event => {
      if (event.key.toLowerCase() === "escape" && this.isOpen) {
        this.close();
      }
    });
  }
};
</script>
