<template>
  <i
    v-if="svg"
    v-show="!loading"
    v-html="svg"
    class="leading-0"
    @click="$emit('click')"
  >
  </i>
</template>

<script>
export default {
  props: {
    dataName: {
      type: String,
      required: ""
    },

    dataIconClass: {
      type: String,
      default: ""
    }
  },

  data() {
    return {
      svg: "",
      loading: true
    };
  },

  computed: {
    iconClass() {
      return this.dataIconClass;
    },

    name() {
      return this.dataName;
    },

    url() {
      return `/svg/${this.name}.svg`;
    }
  },

  methods: {
    getSvg() {
      this.$http
        .get(this.url)
        .then(response => {
          this.svg = response.data;
          this.loading = false;

          window.Vue.nextTick(() => {
            let $svg = this.$el.querySelector("svg");

            if ($svg) {
              $svg.setAttribute("class", this.dataIconClass);
            }
          });
        })
        .catch(error => {
          this.loading = false;
          this.svg = "";
        });
    }
  },

  watch: {
    url() {
      this.getSvg();
    }
  },

  created() {
    this.getSvg();
  }
};
</script>
