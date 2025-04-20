<template>
  <div :class="`px-4 py-4 text-white alert ${bgClass}`" v-if="display">
    <div class="flex items-center" :class="dataContainerClass">
      <icon
        :data-name="iconName"
        class="w-4 h-4 mr-4 font-bold"
        :data-animation="iconSpin ? 'spin' : undefined"
      ></icon>
      <div class="flex-1">
        <slot></slot>
      </div>
      <button
        @click="() => (display = false)"
        type="button"
        v-if="dataDismissible"
        class="ml4"
      >
        <icon data-name="close" class="w-4 h-4"></icon>
        <span class="sr-only">Close Alert</span>
      </button>
    </div>
  </div>
</template>

<script>
import sha256 from "crypto-js/sha256";

export default {
  props: {
    dataTimeout: {
      type: Number,
    },

    dataDismissible: {
      type: Boolean,
      default: true,
    },

    dataType: {
      type: String,
      validator: function (value) {
        return (
          ["success", "warning", "danger", "info", "loading"].indexOf(value) !==
          -1
        );
      },
    },

    dataContainerClass: {
      type: String,
      default: "container",
    },
  },

  data() {
    return {
      display: true,
    };
  },

  computed: {
    iconSpin() {
      return this.dataType === "loading";
    },
    iconName() {
      const names = {
        info: "information-outline",
        success: "checkmark-outline",
        warning: "minus-outline",
        danger: "exclamation-outline",
        loading: "cog",
      };

      return names[this.dataType];
    },

    bgClass() {
      const classes = {
        info: "bg-info",
        success: "bg-success",
        warning: "bg-warning",
        danger: "bg-danger",
        loading: "bg-info",
      };

      return classes[this.dataType];
    },

    id() {
      return sha256(
        this.$slots.default.map((slot) => slot.text).join("")
      ).toString();
    },
  },

  mounted() {
    if (this.dataTimeout) {
      setTimeout(() => (this.display = false), this.dataTimeout);
    }

    document.addEventListener("keyup", (e) => {
      if (e.key.toLowerCase() !== "escape") {
        return;
      }

      this.display = false;
    });
  },

  methods: {},
};
</script>
