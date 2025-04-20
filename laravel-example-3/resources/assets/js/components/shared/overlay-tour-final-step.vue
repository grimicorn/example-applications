<template>
    <div
      class="overlay-tour-final-step-wrap"
      :class="{
          'active': this.active
      }"
    >
        <i
          id="overlay_tour_final_step"
          class="fa fa-plus overlay-tour-final-step js-overlay-tour-final-step"
        ></i>
    </div>
</template>

<script>
export default {
    props: {},

    data() {
        return {
            active: false
        };
    },

    computed: {},

    methods: {
        handleChange(overlayTour) {
            overlayTour.onbeforechange(targetElement => {
                this.active = targetElement.classList.contains(
                    "js-overlay-tour-final-step"
                );
            });
        },

        handleExit(overlayTour) {
            overlayTour.onexit(targetElement => {
                this.active = false;
            });
        }
    },

    mounted() {
        window.Bus.$on("introjs-started", overlayTour => {
            this.handleChange(overlayTour);
            this.handleExit(overlayTour);
        });
    }
};
</script>
