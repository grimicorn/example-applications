<style>
  @import "./JobFlag.css";
</style>

<template>
  <div
    class="job-flag"
    :class="`bg-flag-${flagSlug}`"
  >
    <v-date
      v-if="dataShowDate"
      class="flag-date"
      data-output-format="MM/DD"
      :data-date="dataJob.due_at"
    ></v-date>

  </div>
</template>

<script>
import { mapState } from "vuex";
import collect from "collect.js";

export default {
  props: {
    dataJob: {
      type: Object,
      required: true
    },

    dataShowDate: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {};
  },

  computed: {
    flagSlug() {
      return this.flag.slug;
    },

    flag() {
      let key = parseInt(this.dataJob.flag, 10);
      let flags = collect(this.jobFlags);
      return flags.get(key, {});
    },

    ...mapState(["jobFlags"])
  },

  methods: {}
};
</script>
