<template>
    <div class="notification-view-all">
        <a
        class="fc-color7"
        v-if="url"
        :href="url"
        v-text="label"></a>

        <a
        class="fc-color7"
        v-else
        href="#"
        @click.prevent="handleClick"
        v-text="label"></a>
    </div>
</template>

<script>
module.exports = {
    mixins: [require("./notifications.js")],

    props: {
        dataUrl: {
            type: String,
            default: ""
        },

        dataShowAll: {
            type: Boolean,
            default: false
        }
    },

    data() {
        return {
            url: this.dataUrl,
            showAll: this.dataShowAll
        };
    },

    computed: {
        label() {
            let label = "See All Notifications";

            if (this.showAll) {
                label = "Close All Notifications";
            }

            return label;
        }
    },

    methods: {
        handleClick() {
            this.showAll = !this.showAll;
            this.$emit("show-all", this.showAll);
        }
    }
};
</script>
