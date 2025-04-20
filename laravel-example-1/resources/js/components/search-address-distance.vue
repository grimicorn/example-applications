<template>
  <div class="w-full mb-4 -mt-2" v-if="!isNaN(distance)">
    ~{{ distance }} mile(s) away
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
    ...mapState(["user"]),
    distance() {
      const distance = this.user.searchAddressDistance;

      if (!distance) {
        return;
      }

      return distance.toFixed(2).replace(/^0./g, ".");
    },
  },

  methods: {},
};
</script>
