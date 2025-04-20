<template>
    <div class="share-page">
        <i
        class="fa fa-share share-page-icon"
        aria-hidden="true"
        @click="toggle"></i>

        <div
        v-show="showNetworks"
        class="share-page-networks">
            <div
            v-for="network in networks"
            :key="network.id"
            class="st-custom-button share-page-network"
            :class="network.iconClass"
            :data-network="network.id">
                <span
                class="sr-only"
                v-text="network.label"></span>
            </div>
        </div>
    </div>
</template>

<script>
    module.exports = {
        mixins: [require('./../../mixins/utilities.js')],

        data() {
            return {
                showNetworks: false,
                networks: [
                    {
                        iconClass: 'fa fa-facebook',
                        id: 'facebook',
                        label: 'Share on Facebook',
                    },
                    {
                        iconClass: 'fa fa-twitter',
                        id: 'twitter',
                        label: 'Share on Twitter',
                    },
                    {
                        iconClass: 'fa fa-linkedin',
                        id: 'linkedin',
                        label: 'Share on LinkedIn',
                    }
                ],
            };
        },

        computed: {},

        methods: {
            toggle() {
                this.showNetworks = !this.showNetworks;
            },

            close() {
                this.showNetworks = false;
            }
        },

        mounted() {
            this.listenClickOutside(this.close);
        }
    };
</script>
