<template>
    <i title="Add to Dashboard" class="fa pointer fz-22 -ml30-m add-to-dashboard-icon" :class="{
                'fc-color7 fa-tachometer': !onDashboard,
                'fc-color4 fa-tachometer': onDashboard,
            }" aria-hidden="true" @click="toggleOnDashboard">
    </i>
</template>

<script>
module.exports = {
    name: 'app-exchange-space-add-to-dashboard',

    props: {
        space: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            onDashboard: this.getOnDashboard(),
        };
    },

    computed: {
        route() {
            return `/dashboard/exchange-spaces/${this.space.id}/dashboard`;
        },
    },

    methods: {
        getOnDashboard() {
            let member = this.space.current_member;

            return member === null ? false : !!member.dashboard;
        },

        toggleOnDashboard() {
            if (this.onDashboard) {
                this.remove();
            } else {
                this.add();
            }
        },

        add() {
            window.axios.post(this.route).then(response => {
                this.onDashboard = true;
                this.$emit('change', this.onDashboard);
                this.$emit('added');
            });
        },

        remove() {
            window.axios.delete(this.route).then(response => {
                this.onDashboard = false;
                this.$emit('change', this.onDashboard);
                this.$emit('removed');
            });
        },
    },
};
</script>
