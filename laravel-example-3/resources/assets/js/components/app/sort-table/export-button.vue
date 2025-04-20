<template>
<div
class="sort-table-export-button-wrap"
v-if="route">
    <form@submit.prevent="submit">
        <button
        type="submit"
        class="sort-table-export-button text-uppercase">
            Export CSV
        </button>
    </form>
</div>
</template>

<script>
    let _foreach = require('lodash.foreach');

    module.exports = {
        methods: {
            submit() {
                let route = (-1 === this.route.indexOf('?')) ? `${this.route}?` : this.route;
                let params = [];

                _foreach(this.filters, function(filter, key) {
                    if (filter) {
                        params.push(`${key}=${filter}`);
                    }
                });

                window.open(encodeURI(`${route}${params.join('&')}`));
            }
        },

        props: {
            route: {
                type: String,
                default: '',
            },

            filters: {
                type: Object,
                required: true,
            },
        },
    };
</script>
