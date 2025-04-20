<template>
  <button
    type="submit"
    class="btn btn-color6 btn-model-submit"
    @click="emitClick"
  >
    Save
  </button>
</template>

<script>
module.exports = {
  name: "app-model-save-button",

  props: {},

  data() {
    return {};
  },

  computed: {},

  methods: {
    emitClick() {
      this.$emit("click");
    }
  }
};
</script>
