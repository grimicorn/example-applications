<template>
  <div>
    <ul class="mb-4">
      <li
        v-for="(item, index) in items"
        :key="index"
        class="flex items-center pb-4 pr-4 mb-4 border-b-2 border-gray-300 last:border-0 last:pb-0 last:mb-0"
      >
        <div class="flex-1 mr-4">
          <slot
            name="item"
            :item="item"
            :number="index + 1"
            :index="index"
          ></slot>
        </div>
        <button
          type="button"
          :title="`Remove Item ${index + 1}`"
          @click="removeItem(index)"
        >
          <icon data-name="close" class="w-4 h-4"></icon>
          <span class="sr-only" v-text="`Delete Item ${index + 1}`"></span>
        </button>
      </li>
    </ul>
    <button
      type="button"
      class="button-sm button"
      v-text="dataAddButtonLabel"
      @click.prevent="addItem"
    ></button>
  </div>
</template>

<script>
export default {
  props: {
    value: {
      type: Array,
      default() {
        return [];
      },
    },

    dataEmptyItem: {
      default: null,
    },

    dataAddButtonLabel: {
      type: String,
      default: "Add Item",
    },
  },

  data() {
    return {
      items: [...this.value],
    };
  },

  computed: {},

  methods: {
    addItem() {
      this.items.push(JSON.parse(JSON.stringify(this.dataEmptyItem)));
    },

    removeItem(index) {
      this.items.splice(index, 1);
    },
  },

  watch: {
    value(value) {
      this.items = value;
    },
  },

  mounted() {
    this.$emit("input", this.items);
  },
};
</script>
