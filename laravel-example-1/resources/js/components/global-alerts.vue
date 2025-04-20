<template>
  <div class="fixed top-0 left-0 right-0 z-50">
    <alert
      v-for="({ message, timeout, dismissible, type }, index) in globalAlerts"
      :key="index"
      :dataTimeout="timeout"
      :dataDismissible="dismissible"
      :dataType="type"
    >
      {{ decodeHtml(message) }}
    </alert>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  props: {},

  data() {
    return {};
  },

  computed: {
    ...mapState(["globalAlerts"]),
  },

  methods: {
    decodeHtml(html) {
      var text = document.createElement("textarea");
      text.innerHTML = html;
      return text.value;
    },
  },
};
</script>
