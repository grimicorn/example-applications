<template>
  <span
    v-if="message"
    class="block pt-2 font-bold text-danger"
    v-text="message"
  >
  </span>
</template>

<script>
import { mapState } from "vuex";

export default {
  props: {
    dataKey: {
      required: true,
      type: String,
    },
  },

  data() {
    return {};
  },

  computed: {
    ...mapState(["errors"]),
    message() {
      if (!this.errors.has(this.dataKey)) {
        return;
      }

      return this.errors.get(this.dataKey).first();
    },
  },

  methods: {},
};
</script>
