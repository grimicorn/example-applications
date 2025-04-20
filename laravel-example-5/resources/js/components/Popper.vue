<script>
import Popper from "popper.js";

export default {
  props: {
    dataIsVisible: {
      type: Boolean,
      default: true
    },
    dataPlacement: {
      type: [String, Array],
      default: "auto"
    },
    dataOptions: {
      type: Object,
      default() {
        let vm = this;
        return {
          onCreate(dataObject) {
            vm.$emit("create", dataObject);
          },
          onUpdate(dataObject) {
            vm.$emit("update", dataObject);
          }
        };
      }
    }
  },

  data() {
    return {
      popper: null
    };
  },

  computed: {
    options() {
      return Object.assign(
        {},
        {
          placement: this.dataPlacement
        },
        this.dataOptions
      );
    }
  },

  methods: {
    initializePopper() {
      this.$nextTick(() => {
        if (this.popper) {
          this.popper.scheduleUpdate();
          return;
        }

        let refrence = this.$el.parentNode;
        let popper = this.$el;
        new Popper(refrence, popper, this.options);
      });
    }
  },

  render() {
    return this.$slots.default[0];
  },

  mounted() {
    if (this.dataIsVisible) {
      this.initializePopper();
    }
  },

  watch: {
    dataIsVisible(newValue) {
      if (newValue) {
        this.initializePopper();
      }
    }
  },

  beforeDestroy() {
    if (this.popper) {
      this.popper.destroy();
    }
  }
};
</script>
