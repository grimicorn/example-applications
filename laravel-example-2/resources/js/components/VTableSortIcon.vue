<template>
  <div class="flex flex-col pl-2 sort-icons">
    <icon
      data-name="caret-up"
      v-show="showSortedDesc"
      @click="$emit('sorted', isSorted)"
    ></icon>
    <icon
      data-name="caret-down"
      v-show="showSortedAsc"
      @click="$emit('sorted', isSorted)"
    ></icon>
  </div>
</template>

<script>
export default {
  props: {
    dataSortAsc: {
      type: Boolean,
      default: true
    },

    dataIsSorted: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {};
  },

  computed: {
    isSorted() {
      return this.dataIsSorted;
    },

    showSortedAsc() {
      if (!this.isSorted) {
        return true;
      }

      return this.isSorted && this.dataSortAsc;
    },

    showSortedDesc() {
      if (!this.isSorted) {
        return true;
      }

      return this.isSorted && !this.dataSortAsc;
    }
  },

  methods: {}
};
</script>
