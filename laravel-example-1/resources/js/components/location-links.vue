<template>
  <repeater
    :data-empty-item="emptyItem"
    data-add-button-label="Add Link"
    :value="links"
    v-model="links"
  >
    <template v-slot:item="{ item, number }" hidden>
      <div class="w-full">
        <div class="flex items-center w-full mb-2">
          <label :for="`link_${number}_name`" class="mr-2 whitespace-nowrap">
            Name
          </label>
          <input
            type="text"
            :name="`links[${number}][name]`"
            :id="`link_${number}_name`"
            :value="nameInputDefault(item.name, item.url)"
            class="w-full"
            @input="($e) => (item.name = $e.target.value)"
            placeholder="Defaults to Link Website URL"
          />
        </div>

        <div class="flex items-center w-full">
          <label :for="`link_${number}_url`" class="mr-2 whitespace-nowrap">
            URL
          </label>
          <input
            type="url"
            :name="`links[${number}][url]`"
            :id="`link_${number}_url`"
            v-model="item.url"
            class="w-full"
            placeholder="https://domain.com/link/to/page/"
          />
        </div>

        <input
          type="hidden"
          :name="`links[${number}][id]`"
          :id="`link_${number}_id`"
          v-model="item.id"
          class="w-full"
        />
      </div>
    </template>
  </repeater>
</template>

<script>
export default {
  props: {
    dataLinks: {
      type: Array,
      default() {
        return [];
      },
    },
  },

  data() {
    return {
      links: this.dataLinks,
      emptyItem: {
        url: "",
      },
    };
  },

  computed: {},

  methods: {
    nameInputDefault(name, url) {
      if (name) {
        return name;
      }

      try {
        return new URL(url).host.replace(/^www./g, "");
      } catch (error) {
        return url;
      }
    },
  },

  watch: {
    dataLinks(value) {
      this.dataLinks = value;
    },
  },
};
</script>
