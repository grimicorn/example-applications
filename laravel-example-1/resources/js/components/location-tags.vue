<template>
  <div>
    <tags
      :data-tags="locationTags"
      :data-name="dataName"
      :data-id="dataId"
      :value="currentValue"
      @input="handleInput"
    ></tags>
  </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
  props: {
    dataName: {
      type: String,
      required: true,
    },

    dataId: {
      type: String,
    },

    value: {
      type: Array,
      default() {
        return [];
      },
    },
  },

  data() {
    return {
      currentValue: [],
    };
  },

  computed: {
    ...mapState(['locationTags']),
  },

  watch: {
    value(value) {
      this.currentValue = this.convertValueToTags(value);
    },
  },

  methods: {
    handleInput(value) {
      this.currentValue = value;
      this.$emit(
        'input',
        this.currentValue.map((item) => (item.id ? item.id : item.name))
      );
    },

    convertValueToTags(value) {
      if (!value) {
        return [];
      }

      return value
        .map((item) => {
          const idMatches = this.locationTags.filter(
            (tag) => parseInt(tag.id, 10) === parseInt(item, 10)
          );
          if (idMatches.length > 0) {
            return idMatches[0];
          }

          const nameMatches = this.locationTags.filter(
            (tag) => tag.name === item
          );
          if (nameMatches.length > 0) {
            return nameMatches[0];
          }

          return undefined;
        })
        .filter((item) => item !== undefined);
    },
  },

  mounted() {
    this.currentValue = this.convertValueToTags(this.value);
  },
};
</script>
