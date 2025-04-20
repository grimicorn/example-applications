<template>
    <div class="">
        <input-toggle
        :name="name"
        :value="value"
        :label="label"
        :reverse="true"
        :tooltip="tooltip"
        @change="change"></input-toggle>
    </div>
</template>

<script>
    module.exports = {
        props: {
            name: {
                type: String,
                required: true,
            },

            value: {
                type: Boolean,
                default: false,
            },

            label: {
                type: String,
                default: '',
            },

            tooltip: {
                type: String,
                default: '',
            },

            route: {
                type: String,
                required: true,
            },
        },

        data() {
            return {
                hasChanged: false,
            };
        },

        computed: {},

        methods: {
            shouldChange(value) {
                // We only want it to change if the value has been changed from the default.
                if (this.hasChanged) {
                    return true;
                }

                if (this.value === value) {
                    return false;
                }

                this.hasChanged = true;
                return true;
            },

            change(input) {
                // Since the value can be set as true this will create a "change" so we will want to make sure this only happens on real changes not the initalization.
                if (!this.shouldChange(input.value)) {
                    return;
                }

                window.axios.post(this.route, {public: !!input.value})
                .then(({data}) => {
                    window.flashAlert(data.status, {
                        type: 'success',
                        timeout: 5000,
                    });
                });
            },
        },
    };
</script>
