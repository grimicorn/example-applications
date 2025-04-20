<template>
  <img v-if="icon" :src="icon.url" :alt="icon.alt" width="32" height="37" />
</template>

<script>
export default {
  props: {
    dataIcon: {
      type: Object,
    },

    dataShowDefault: {
      type: Boolean,
      default: true,
    },
  },

  data() {
    return {};
  },

  computed: {
    icon() {
      if (!this.dataShowDefault && !this.dataIcon) {
        return;
      }

      if (!this.dataIcon) {
        return {
          url: "/images/google-map-icons/symbol_inter.png",
          alt: "Default icon",
        };
      }

      return {
        url: this.dataIcon.url,
        alt: `${this.dataIcon.name} icon`,
      };
    },
  },

  methods: {},
};
</script>
