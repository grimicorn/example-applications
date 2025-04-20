<template>
  <div>
    <slot></slot>
  </div>
</template>

<script>
import { mapMutations } from "vuex";

export default {
  props: {},

  data() {
    return {};
  },

  computed: {},

  methods: {
    addAlerts() {
      this.addAlert({
        dismissible: true,
        type: "success", // "success", "info", "warning", "danger"
        timeout: 0,
        message: "This is a success message"
      });

      this.addAlert({
        dismissible: true,
        type: "info", // "success", "info", "warning", "danger"
        timeout: 0,
        message: "This is an info message"
      });

      this.addAlert({
        dismissible: true,
        type: "warning", // "success", "info", "warning", "danger"
        timeout: 0,
        message: "This is a warning message"
      });

      this.addAlert({
        dismissible: false,
        type: "danger", // "success", "info", "warning", "danger"
        timeout: 0,
        message: "This is a danger message"
      });

      this.addAlert({
        dismissible: false,
        type: "danger", // "success", "info", "warning", "danger"
        timeout: 0,
        message: "This is a danger message"
      });
    },

    ...mapMutations(["addAlert"])
  },

  mounted() {
    this.addAlerts();
  }
};
</script>
