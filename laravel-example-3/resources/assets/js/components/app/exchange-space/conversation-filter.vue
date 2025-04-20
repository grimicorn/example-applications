<template>
    <div class="dilligence-center-filter-cards">
        <app-exchange-space-conversation-filter-card
        v-if="conversations.length >= 1"
        v-for="conversation in conversations"
        :key="conversation.id"
        :conversation="conversation"
        :isInquiry="inquiry"></app-exchange-space-conversation-filter-card>
        <div
        class="flex pt2 pb1 pl2 pr1 justify-center bb1"
        v-if="conversations.length < 1">
            <p class="mb2 mt2 ml2 mr2 text-center"
            v-if="inquiry">The first step to a deal is the introduction. When you send or
                receive an inquiry through the 'Start an Exchange Space' button on a business summary page, a conversation is created here to kick off the process.</p>
            <p class="mb2 mt2 ml2 mr2 text-center"
            v-else>No Conversations Found.</p>
        </div>
    </div>
</template>

<script>
let _filter = require('lodash.filter');

module.exports = {
    props: {
        paginatedConversations: {
            type: Object,
            required: true,
        },

        page: {
            type: Number,
            default: 1,
        },

        route: {
            type: String,
        },
        inquiry: {
            type: Boolean,
            default: false,
        },
    },

    data() {
        return {
            search: '',
            category: '',
            resolved: '',
            title: '',
            inquirer: '',
            sortKey: '',
            conversations: this.paginatedConversations.data,
        };
    },

    computed: {
        filters() {
            let filters = {};

            if (this.search) {
                filters.search = this.search;
            }

            if (this.category) {
                filters.category = this.category;
            }

            if (this.resolved === 1 || this.resolved === 0) {
                filters.resolved = this.resolved;
            }

            if (this.title === 1 || this.title === 0) {
                filters.title = this.title;
            }

            if (this.inquirer === 1 || this.inquirer === 0) {
                filters.inquirer = this.inquirer;
            }

            if (this.sortKey) {
                filters.sortKey = this.sortKey;
            }

            return filters;
        },
    },

    methods: {
        filter() {
            window.axios.post(this.route, this.filters).then(({ data }) => {
                this.conversations = data.data;
            });
        },
    },

    mounted() {
        window.Bus.$on('converation-filters:category-change', value => {
            this.category = value;
            this.filter();
        });
        window.Bus.$on('converation-filters:status-change', value => {
            this.resolved = value;
            this.filter();
        });

        window.Bus.$on('converation-filters:title-change', value => {
            this.title = value;
            this.filter();
        });
        window.Bus.$on('converation-filters:inquirer-change', value => {
            this.inquirer = value;
            this.filter();
        });
        window.Bus.$on('converation-filters:sort-change', value => {
            this.sortKey = value;
            this.filter();
        });

        window.Bus.$on('converation-filters:search-submit', value => {
            this.search = value;
            this.filter();
        });

        window.Bus.$on('conversation-resolved', () => {
            this.filter();
        });

        window.Bus.$on('conversation-unresolved', () => {
            this.filter();
        });
    },
};
</script>
