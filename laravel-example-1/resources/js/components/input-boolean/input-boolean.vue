<style scoped lang="css">
/* Styles are in app.css. Upgrading to Laravel Mix v6 broke @apply in vue SFC :( */
</style>

<template>
  <div class="flex w-auto input-boolean">
    <input
      class="w-0 h-0 p-0 m-0"
      type="radio"
      :id="inputTrue.id"
      :name="dataName"
      :value="inputTrue.value"
      :checked="currentValue"
      @input="(event) => handleInputUpdate(event, true)"
    />
    <label :for="inputTrue.id" v-text="inputTrue.label"></label>
    <span class="px-1">|</span>

    <input
      type="radio"
      :id="inputFalse.id"
      :name="dataName"
      :value="inputFalse.value"
      :checked="!currentValue"
      @input="(event) => handleInputUpdate(event, false)"
    />
    <label :for="inputFalse.id" v-text="inputFalse.label"></label>
  </div>
</template>

<script>
export default {
  props: {
    value: {
      type: Boolean,
      default: false,
    },
    dataName: {
      type: String,
      required: true,
    },
    dataId: {
      type: String,
    },
    dataInputTrue: {
      type: Object,
      validator(prop) {
        return prop && prop.value !== undefined && prop.label !== undefined;
      },
    },
    dataInputFalse: {
      type: Object,
      validator(prop) {
        return prop && prop.value !== undefined && prop.label !== undefined;
      },
    },
  },

  data() {
    return {
      currentValue: this.value,
    };
  },

  computed: {
    id() {
      return this.dataId ? this.dataId : this.dataName;
    },

    inputTrue() {
      const inputTrue = this.dataInputTrue ? this.dataInputTrue : {};

      return {
        ...inputTrue,
        id: this.dataInputTrue.id ? this.dataInputTrue.id : `${this.id}_true`,
      };
    },

    inputFalse() {
      const inputFalse = this.dataInputFalse ? this.dataInputFalse : {};

      return {
        ...inputFalse,
        id: this.dataInputFalse.id
          ? this.dataInputFalse.id
          : `${this.id}_false`,
      };
    },
  },

  methods: {
    handleInputUpdate(event, value) {
      this.currentValue = value;
      this.$emit("input", event, value);
    },
  },

  watch: {
    value(value) {
      this.currentValue = value;
    },
  },
};
</script>
