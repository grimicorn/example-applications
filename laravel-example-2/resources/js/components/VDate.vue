<template>
  <span v-text="date"></span>
</template>

<script>
import dateToLocalTimezone from "@utilities/dateToLocalTimezone";
export default {
  props: {
    dataOutputFormat: {
      type: String,
      // 01/01/2020
      default: "MM/DD/YYYY"
    },
    dataDate: {
      type: String,
      required: ""
    }
  },

  data() {
    return {};
  },

  computed: {
    date() {
      if (!this.dataDate) {
        return "";
      }

      return dateToLocalTimezone(this.dataDate, this.dataOutputFormat);
    }
  },

  methods: {}
};
</script>
