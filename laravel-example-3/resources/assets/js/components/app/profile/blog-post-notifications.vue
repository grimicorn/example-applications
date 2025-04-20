<template>
    <div class="">
        <input-toggle
                name="emailNotificationSettings[blog_posts]"
                :value="enable"
                @change="enableChanged"
                label="Blog Post Notifications"></input-toggle>
    </div>
</template>

<script>
    module.exports = {
        props: {
            dataEnable: {
                type: Boolean,
                default: true,
            },
        },

        data() {
            return {
                enable: this.dataEnable,
            };
        },

        computed: {},

        methods: {
            enableChanged({value}) {
                this.enable = value;
            }
        },
    };
</script>