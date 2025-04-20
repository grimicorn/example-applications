<template>
  <ul class="flex flex-wrap -mx-2 -mb-2">
    <li
      v-for="{
        link,
        title,
        image: {
          thumbnailLink,
          height,
          width,
          thumbnailHeight,
          thumbnailWidth,
          contextLink,
        },
      } in searchImages"
      :key="link"
      class="flex items-center justify-center px-2 mb-2"
    >
      <lightbox
        :data-original-link="contextLink"
        :data-is-open="lightBoxOpen"
        :data-full-src="link"
        :data-full-width="height"
        :data-full-height="width"
        :data-alt="title"
        :data-thumbnail-src="thumbnailLink"
        :data-thumbnail-width="thumbnailWidth"
        :data-thumbnail-height="thumbnailHeight"
      />
    </li>
  </ul>
</template>

<script>
export default {
  props: {
    dataSearchQuery: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
      searchImages: [],
      lightBoxOpen: false,
    };
  },

  computed: {},

  methods: {
    setSearchImages() {
      this.$http
        .get(
          this.$route("image-search.index", {
            query: this.dataSearchQuery,
          })
        )
        .then(({ data }) => {
          this.searchImages = data ?? [];
        });
    },
  },

  mounted() {
    this.setSearchImages();
  },
};
</script>
