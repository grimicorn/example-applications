<template>
  <div v-if="dataTags" class="v-select-wrap tags">
    <v-select
      label="name"
      :options="dataTags"
      v-model="currentValue"
      :taggable="true"
      :multiple="true"
      :clearable="true"
      placeholder="Search tags"
    ></v-select>
    <input
      type="hidden"
      v-for="{ id, name } in currentValue"
      :key="id ? id : name"
      :value="id ? id : name"
      v-text="name"
      :name="`${dataName}[]`"
      :id="dataId ? dataId : dataName"
    />
  </div>
</template>

<script>
import vSelect from "~/vue-select/src/index";

export default {
  components: {
    "v-select": vSelect,
  },

  props: {
    dataTags: {
      type: Array,
      default() {
        return [];
      },
      validator: function (value) {
        return (
          value.filter(({ name, id }) => {
            return name === undefined || id === undefined;
          }).length === 0
        );
      },
    },

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

    dataAllowCreate: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      currentValue: this.value,
      skipNextEmitInput: false,
    };
  },

  computed: {},

  methods: {},

  watch: {
    value(value) {
      this.skipNextEmitInput = true;
      this.currentValue = value;
    },

    currentValue(value) {
      if (this.skipNextEmitInput) {
        this.skipNextEmitInput = false;
        return;
      }

      this.$emit("input", value);
    },
  },
};
</script>
