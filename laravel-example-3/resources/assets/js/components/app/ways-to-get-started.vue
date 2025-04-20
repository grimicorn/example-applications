<template>
    <app-form-accordion
        v-if="!dimissed"
        header-title="Ways to Get Started"
        class="dashboard-get-started mb3"
        :collapsible="false"
    >
        <template slot="content" class="row pt3 pl3 pr3">
            <slot name="content"></slot>

            <div class="text-right">
                <button
                    :disabled="dismissing"
                    class="fc-color7 text-italic btn-link a-ul fs-12"
                    @click="dismiss"
                >Dismiss</button>
            </div>
        </template>
    </app-form-accordion>
</template>

<script>
export default {
    props: {
        dataDismissUrl: {
            type: String,
            required: true,
        },

        dataDismissed: {
            type: Boolean,
            required: false,
        },
    },

    data() {
        return {
            dismissUrl: this.dataDismissUrl,
            dimissed: this.dataDismissed,
            dismissing: false,
        };
    },

    computed: {},

    methods: {
        dismiss() {
            this.dismissing = true;

            window.axios
                .delete(this.dismissUrl)
                .then(response => {
                    if (response.data.status) {
                        window.flashAlert(response.data.status, {
                            type: 'success',
                            timeout: 5000,
                        });
                    }

                    this.dimissed = true;
                    this.dismissing = false;
                })
                .catch(error => {
                    window.flashAlert(
                        'Something went wrong please try again.',
                        {
                            type: 'error',
                        }
                    );

                    this.dismissing = false;
                });
        },
    },
};
</script>
