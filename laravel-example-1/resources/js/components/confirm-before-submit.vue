<template>
  <button class="button" type="submit" @click.prevent="handleClick">
    <slot></slot>
  </button>
</template>

<script>
export default {
  props: {
    confirmationMessage: {
      type: String,
      required: true,
    },
  },

  data() {
    return {};
  },

  computed: {},

  methods: {
    handleClick() {
      const confirmation = window.confirm(this.confirmationMessage);

      if (confirmation && this.$el.form) {
        this.$el.form.submit();
      }
    },
  },
};
</script>
