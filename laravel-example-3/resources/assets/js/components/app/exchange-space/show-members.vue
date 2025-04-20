<template>
    <app-form-accordion
    id="overlay_tour_exchange_space_members_step"
    header-title="Members"
    class="exchange-space-members mb3"
    :collapsible="false">
        <template slot="content">
            <div class="row">
                <!-- Buyer -->
                <div class="col-sm-3">
                    <h3 class="fz-16">Buyer</h3>
                    <app-exchange-space-show-member
                    v-for="member in buyers"
                    :key="member.id"
                    :member="member"
                    :current-member="currentMember"
                    :add-route="addRoute"
                    :add-search-route="addSearchRoute"></app-exchange-space-show-member>
                </div>

                <!-- Buyer Advisors -->
                <div class="col-sm-3">
                    <h3 class="fz-16">Buyer's Deal Team</h3>

                    <app-exchange-space-show-member
                    v-for="member in buyerAdvisors"
                    :key="member.id"
                    :member="member"
                    :current-member="currentMember"
                    :add-route="addRoute"
                    :add-search-route="addSearchRoute"></app-exchange-space-show-member>
                </div>

                <!-- Seller Advisors -->
                <div class="col-sm-3">
                    <h3 class="fz-16">Seller's Deal Team</h3>

                    <app-exchange-space-show-member
                    v-for="member in sellerAdvisors"
                    :key="member.id"
                    :member="member"
                    :current-member="currentMember"
                    :add-route="addRoute"
                    :add-search-route="addSearchRoute"></app-exchange-space-show-member>
                </div>

                <!-- Seller -->
                <div class="col-sm-3">
                    <h3 class="fz-16">Seller</h3>

                    <app-exchange-space-show-member
                    v-for="member in sellers"
                    :key="member.id"
                    :member="member"
                    :current-member="currentMember"
                    :add-route="addRoute"
                    :add-search-route="addSearchRoute"></app-exchange-space-show-member>
                </div>
            </div>
        </template>
    </app-form-accordion>
</template>

<script>
let _map = require('lodash.map');
let camelCase = require('lodash.camelcase');

module.exports = {
    props: {
        groups: {
            type: Object,
            required: true,
        },

        currentMember: {
            type: Object,
            required: true,
        },

        addSearchRoute: {
            type: String,
            default: '',
        },

        addRoute: {
            type: String,
            default: '',
        },
    },

    data() {
        return {
            buyers: [],
            buyerAdvisors: [],
            sellers: [],
            sellerAdvisors: [],
        };
    },

    computed: {},

    methods: {
        getGroup(group) {
            return typeof this.groups[group] === 'undefined'
                ? []
                : this.groups[group];
        },

        updateMember(newMember, groupKey) {
            // Only sellers should be able to see reuqested members
            if (!this.currentMember.is_seller) {
                return;
            }

            let objKey = camelCase(groupKey);
            let group = this[objKey];
            if (typeof group === 'undefined') {
                return;
            }

            // Check if the user already exists
            let found = false;
            let foundIndex = -1;
            group = _map(group, (member, key) => {
                if (newMember.id !== member.id) {
                    return member;
                }

                found = true;
                foundIndex = parseInt(key, 10);
                return newMember;
            });

            // Add them if they are active and they do not exist
            if (!found && newMember.active) {
                group.push(newMember);
            }

            // Remove them if they are not active.
            if (found && !newMember.active && foundIndex >= 0) {
                group.splice(foundIndex, 1);
            }

            this[objKey] = group;
        },
    },

    mounted() {
        this.buyers = this.getGroup('buyers');
        this.buyerAdvisors = this.getGroup('buyer_advisors');
        this.sellers = this.getGroup('sellers');
        this.sellerAdvisors = this.getGroup('seller_advisors');
        window.Bus.$on(
            'app-exchange-space-member:updated',
            (member, groupKey) => {
                this.updateMember(member, groupKey);
            }
        );
    },
};
</script>
