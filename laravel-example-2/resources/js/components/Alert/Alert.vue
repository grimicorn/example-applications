<style lang="scss" scoped>
  @import "./Alert.css";
</style>

<template>
  <transition name="fade">
    <div
      v-if="visible"
      class="border-l-4 p-4 mb-2 flex"
      :class="wrapClass"
      role="alert"
    >
      <div
        :class="{'mr-2': dismissible}"
        class="flex-1"
      >
        <span class="font-bold block mb-2">
          <icon
            data-icon-class="cursor-pointer h-3 w-3 fill-current"
            :data-name="iconName"
          ></icon>

          {{ label }}
        </span>
        <slot></slot>
      </div>

      <button
        v-if="dismissible"
        @click="dismiss"
      >
        <icon
          data-icon-class="cursor-pointer h-3 w-3 fill-current"
          data-name="times"
        ></icon>
        <span class="sr-only">Dismiss</span>
      </button>
    </div>
  </transition>
</template>

<script>
export default {
  props: {
    dataDismissible: {
      type: Boolean,
      default: true
    },

    dataType: {
      type: String,
      default: "info",
      validator(value) {
        return ["success", "info", "warning", "danger"].indexOf(value) !== -1;
      }
    },

    dataTimeout: {
      type: Number,
      default: 0,
      validator(value) {
        return parseInt(value, 10) >= 0;
      }
    }
  },

  data() {
    return {
      labels: {
        info: "Info",
        success: "Success",
        warning: "Warning",
        danger: "Danger"
      },

      iconNames: {
        info: "info",
        success: "check",
        warning: "exclamation",
        danger: "exclamation"
      },

      visible: !!this.$slots["default"]
    };
  },

  computed: {
    label() {
      if (typeof this.labels[this.type] !== undefined) {
        return this.labels[this.type];
      }

      return "";
    },

    type() {
      return this.dataType;
    },

    dismissible() {
      return this.dataDismissible;
    },

    timeout() {
      return this.dataTimeout;
    },

    wrapClass() {
      return `alert-${this.type}`;
    },

    iconName() {
      if (typeof this.iconNames[this.type] !== undefined) {
        return this.iconNames[this.type];
      }

      return "notifications-outline";
    }
  },

  methods: {
    dismiss() {
      this.visible = false;
      this.$emit("dimissed");
    },

    startTimeout() {
      if (this.timeout > 0) {
        setTimeout(this.dismiss, this.timeout);
      }
    }
  },

  mounted() {
    this.startTimeout();
  }
};
</script>
