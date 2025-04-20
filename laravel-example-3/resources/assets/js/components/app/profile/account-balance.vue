<template>
    <loader
    class="inline"
    :loading="true"
    :cover="false"
    :spinner-large="false"
    v-if="loading"
    color-class="fc-color7"></loader>

    <span
    class="account-balance"
    v-else>{{ balance | price }}</span>
</template>

<script>
module.exports = {
    mixins: [require('./../../../mixins/filters.js')],

    props: {
        userId: {
            type: Number,
            required: true,
        },
    },

    data() {
        return {
            loading: true,
            balance: 0,
        };
    },

    computed: {
        url() {
            return `/dashboard/profile/${this.userId}/account-balance`;
        },
    },

    methods: {
        update() {
            this.loading = true;

            window.axios.get(this.url)
            .then(({data}) => {
                this.loading = false;

                if (typeof data.account_balance === 'undefined') {
                    return 0;
                }

                let balance = parseFloat(data.account_balance, 10);
                this.balance = isNaN(balance) ? 0 : balance;
            });
        }
    },

    created() {
        this.update();
    },

    watch: {
        userId(value) {
            this.update();
        },
    },
};
</script>
