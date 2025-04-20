<template>
    <app-form-accordion
    header-title="Acquisition Interests (as a buyer)"
    class="profile-edit-desired-purchase-criteria"
    v-if="shouldDisplay">
        <template slot="content">
            <slot></slot>
        </template>
    </app-form-accordion>
</template>

<script>
    module.exports = {
        props: {},

        data() {
            return {
                shouldDisplay: false,
            };
        },

        computed: {},

        methods: {},

        mounted() {
            window.Bus.$on('application-profile-edit.change', (input) => {
                if (input.name !== 'primary_roles[Buyer]') {
                    return;
                }

                this.shouldDisplay = input.value;
            });
        },
    };
</script>
