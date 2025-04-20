<template>
  <div class="container m-auto">
    <alert
      v-for="alert in alerts"
      :key="alert.uuid"
      :data-dismissible="alert.dismissible"
      :data-type="alert.type"
      :data-timeout="alert.timeout"
      @dimissed="handleDimissed(alert)"
    >
      {{ alert.message }}
    </alert>
  </div>
</template>

<script>
import { mapState, mapMutations } from "vuex";

export default {
  props: {},

  data() {
    return {};
  },

  computed: {
    ...mapState(["alerts"])
  },

  methods: {
    handleDimissed(alert) {
      this.removeAlert(alert);
    },

    ...mapMutations(["removeAlert"])
  }
};
</script>
