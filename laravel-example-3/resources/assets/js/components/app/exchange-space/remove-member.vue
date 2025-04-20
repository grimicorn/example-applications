<template>
    <modal
    button-label="Remove Member"
    button-class="btn btn-color6"
    title="Remove Member"
    @opened="modalOpened">
        <template
        scope="props">
            <div v-show="!confirming">
                <loader
                :loading="loading || removing"
                :cover="false"></loader>

                <div
                class="flex items-end"
                v-if="!loading && !removing">
                    <input-textual
                    name="new_member_filter"
                    label="Filter"
                    placeholder="Name or email"
                    :value="filterValue"
                    @change="({value}) => filterValue = value"
                    wrap-class="mb0 mr2"></input-textual>

                    <button
                    type="submit"
                    class="btn btn-color5 inline-block fe-input-height"
                    :disabled="removing || loading"
                    @click="filter">Filter</button>
                </div>

                <input-label
                class="mt3"
                label="Results:"
                v-if="!loading && !removing"></input-label>

                <ul
                class="list-unstyled mt3"
                v-if="!loading && !removing">
                    <li
                    class="mb3"
                    v-for="user in filteredUsers"
                    :key="user.id">
                        <div class="inline-block">
                            <div class="member-item-edit-header">
                                <avatar
                                :src="user.photo_thumbnail_small_url"
                                width="44"
                                height="44"
                                image-class="rounded"
                                :initals="user.initials"
                                :uses-two-factor="user.uses_two_factor_auth"
                                class="mr1"></avatar>

                                {{user.initials}}

                                <span class="member-item-edit-name" v-text="user.name"></span>
                            </div>

                            <div v-text="user.tagline" v-if="user.tagline" class="pb2 clear"></div>
                        </div>
                        <div>
                            <a
                            :href="user.profile_url"
                            target="_blank"
                            class="btn btn-color4 mr1">View Profile</a>

                            <button
                            class="btn btn-color5"
                            :disabled="removing || loading"
                            @click="handleRemove(user.member_id, props.close)">
                                Remove
                            </button>
                        </div>
                    </li>
                </ul>

                <ul
                v-if="filteredUsers.length === 0 && !loading && !removing"
                class="list-unstyled">
                    <li class="text-center">
                        No removable users found.
                    </li>
                </ul>
            </div>
        </template>
    </modal>
</template>

<script>
let _filter = require('lodash.filter');

module.exports = {
    name: 'app-exchange-space-remove-member',

    mixins: [require('./../../../mixins/confirm.js')],

    props: {
        loadRoute: {
            type: String,
            default: '',
        },

        route: {
            type: String,
            default: '',
        },
    },

    data() {
        return {
            loading: false,
            removing: false,
            users: true,
            filterValue: '',
            filteredUsers: [],
            confirming: false,
        };
    },

    computed: {},

    methods: {
        removeRoute(memberId) {
            let route = this.route;
            return route.replace('/destroy', memberId + '/destroy');
        },

        load() {
            this.loading = true;

            window.axios
                .post(this.loadRoute, { members: 1 })
                .then(({ data }) => {
                    this.loading = false;
                    this.users = this.filteredUsers = data.results;
                });
        },

        filter() {
            if (this.filterValue === '') {
                this.filteredUsers = this.users;
                return;
            }

            this.filteredUsers = _filter(this.users, user => {
                return JSON.stringify(user).indexOf(this.filterValue) >= 0;
            });
        },

        remove(memberId, close) {
            window.axios
                .delete(this.removeRoute(memberId))
                .then(({ data }) => {
                    this.removing = false;
                    window.flashAlert(data.status, {
                        type: 'success',
                        timeout: 5000,
                    });
                    close();
                    this.emitUpdated(data);
                })
                .catch(() => {
                    this.removing = false;
                    window.flashAlert('Something went wrong please try again', {
                        type: 'error',
                    });
                });
        },

        handleRemove(memberId, close) {
            this.removing = true;
            this.confirming = true;

            this.confirm(
                'remove-member',
                'Are you sure you want to remove this advisor?',
                () => {
                    this.removing = true;
                    this.confirming = false;
                    this.remove(memberId, close);
                },
                () => {
                    this.removing = false;
                    this.confirming = false;
                    close();
                }
            );
        },

        emitUpdated(data) {
            // Global
            window.Bus.$emit(
                'app-exchange-space-member:updated',
                data.member,
                data.group
            );

            // Parent
            this.$emit('updated', data.member, data.group);
        },

        modalOpened() {
            this.load();
        },
    },
};
</script>
