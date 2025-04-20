<template>
  <div v-if="dataContent" v-html="content" class="prose"></div>
</template>

<script>
import marked from "marked";
import _debounce from "lodash.debounce";

export default {
  props: {
    dataContent: {
      type: String,
      default: "",
    },
  },

  data() {
    return {};
  },

  computed: {
    content: function () {
      return marked(this.dataContent);
    },
  },

  methods: {},
};
</script>
