<template>
  <div class="w-full input-fancy-select v-select-wrap">
    <v-select
      :options="options"
      v-model="selection"
      :placeholder="dataPlaceholder"
    ></v-select>

    <input
      type="hidden"
      :name="dataName"
      :id="dataId ? dataId : dataName"
      v-if="selection"
      :value="selection.value"
    />
  </div>
</template>

<script>
import vSelect from '../../../node_modules/vue-select/src/index';

export default {
  components: {
    'v-select': vSelect,
  },

  props: {
    dataPlaceholder: {
      type: String,
    },
    dataId: {
      type: String,
      required: true,
    },
    dataName: {
      type: String,
      required: true,
    },
    dataOptions: {
      type: Array,
      required: true,
      validator: function (value) {
        return (
          value.filter(
            ({ label, value }) => value === undefined || label === undefined
          ).length === 0
        );
      },
    },
    value: {
      type: [String, Number],
      default: '',
    },
  },

  data() {
    return {
      selection: this.convertValueToSelection(this.value),
    };
  },

  watch: {
    value(value) {
      this.selection = this.convertValueToSelection(value);
    },
  },

  computed: {
    options() {
      let options = this.dataOptions;

      if (this.defaultValue) {
        options.unshift(this.defaultValue);
      }

      return options;
    },

    defaultValue() {
      if (!this.dataPlaceholder) {
        return;
      }

      return {
        label: this.dataPlaceholder,
        value: '',
      };
    },
  },

  methods: {
    handleInput(value) {
      value = value === null ? undefined : value;
      this.selection = value ? value : this.defaultValue;

      this.$emit('input', this.selection);
    },

    convertValueToSelection(value) {
      value = value === null ? '' : value;
      value = value === undefined ? '' : value;

      const matches = this.dataOptions.filter((item) => item.value === value);
      return matches.length > 0 ? matches[0] : undefined;
    },
  },

  watch: {
    selection(value) {
      this.$emit('input', value);
    },
  },
};
</script>
