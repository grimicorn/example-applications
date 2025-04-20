<template>
    <modal
    :button-label="modalButtonLabel"
    :button-class="modalButtonClass"
    :title="modalTitle"
    :auto-open="autoOpen"
    @opened="modalOpened"
    @closed="modalClosed">
        <template scope="props">
            <div v-show="!confirming">
                <loader :loading="updating" :cover="false"></loader>

                <form
                @submit.prevent="search"
                class="flex items-end"
                v-if="!loadUserId"
                v-show="!updating">
                    <input-textual
                    name="new_member_search"
                    label="Search Registered Users"
                    placeholder="Name or email"
                    :value="searchValue"
                    @change="({value}) => searchValue = value"
                    wrap-class="mb0 mr2"></input-textual>

                    <button
                    type="submit"
                    class="btn btn-color5 inline-block fe-input-height"
                    :disabled="searching">Search</button>
                </form>

                <div
                class="mt3"
                v-if="searched"
                v-show="!updating">
                    <input-label
                    label="Results:"
                    v-if="!loadUserId"></input-label>

                    <loader :loading="searching" :cover="false"></loader>

                    <ul
                    class="list-unstyled"
                    v-if="!searching">
                        <li
                        class="mb3"
                        v-for="user in results"
                        :key="user.id">
                            <div>
                                <div class="member-item-edit-header">
                                    <avatar
                                    :src="user.photo_thumbnail_small_url"
                                    width="44"
                                    height="44"
                                    image-class="rounded"
                                    :initials="user.initials"
                                    :uses-two-factor="user.uses_two_factor_auth"
                                    class="pr1 rounded"></avatar>

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
                                :class="{
                                    mr1: denyRoute,
                                }"
                                v-text="confirmButtonLabel"
                                @click="confirmAdd(user.id, props.close)"></button>

                                <button
                                class="btn btn-color7"
                                v-if="denyRoute"
                                @click="handleDeny(props.close)">
                                    Deny
                                </button>
                            </div>
                        </li>
                    </ul>

                    <ul v-if="results.length === 0 && !searching" class="list-unstyled">
                        <li class="text-center">
                            No available users found.
                        </li>
                    </ul>
                </div>
            </div>
        </template>
    </modal>
</template>

<script>
module.exports = {
    name: 'app-exchange-space-new-member',

    mixins: [require('./../../../mixins/confirm.js')],

    props: {
        isRequest: {
            type: Boolean,
            default: false,
        },

        isApproval: {
            type: Boolean,
            default: false,
        },

        searchRoute: {
            type: String,
            default: '',
        },

        route: {
            type: String,
            default: '',
        },

        denyRoute: {
            type: String,
            default: '',
        },

        loadSearch: {
            type: String,
            default: '',
        },

        memberName: {
            type: String,
            default: '',
        },

        dataLoadUserId: {
            type: Number,
            default: 0,
        },

        allowAutoOpenModal: {
            type: Boolean,
            default: true,
        },
    },

    data() {
        return {
            loadUserId: this.dataLoadUserId,
            searchValue: this.loadSearch,
            searched: false,
            searching: false,
            updating: false,
            results: [],
            confirming: false,
        };
    },

    computed: {
        autoOpen() {
            return (
                !!(this.loadSearch || this.loadUserId) &&
                this.allowAutoOpenModal
            );
        },
        modalTitle() {
            if (this.loadUserId || this.isApproval) {
                return 'Approve Advisor';
            }

            return this.isRequest ? 'Request Member' : 'Add Member';
        },
        modalButtonClass() {
            if (this.isApproval) {
                return 'btn-link btn-color4 a-ul';
            }

            return this.isRequest ? 'btn-color4' : 'btn btn-color6';
        },
        modalButtonLabel() {
            if (this.isApproval && this.memberName) {
                return this.memberName;
            }

            return this.isRequest ? 'Request Member' : 'Add Member';
        },
        confirmButtonLabel() {
            if (this.loadUserId || this.isApproval) {
                return 'Approve';
            }

            return this.isRequest ? 'Request' : 'Confirm';
        },
        confirmMessage() {
            if (this.isRequest) {
                return 'Are you sure you want to request this user?';
            }

            return 'Are you sure you want to confirm this user?';
        },
    },

    methods: {
        deny(close) {
            window.axios
                .delete(this.denyRoute)
                .then(({ data }) => {
                    this.updating = false;
                    this.confirming = false;
                    window.flashAlert(data.status, {
                        type: 'success',
                        timeout: 5000,
                    });
                    close();
                    this.emitUpdated(data);
                })
                .catch(() => {
                    this.updating = false;
                    this.confirming = false;
                    window.flashAlert('Something went wrong please try again', {
                        type: 'error',
                    });
                });
        },

        handleDeny(close) {
            if (!this.denyRoute) {
                return;
            }

            this.updating = true;
            this.confirming = true;

            this.confirm(
                'deny-member',
                'Are you sure you want to deny this advisor?',
                () => {
                    this.updating = true;
                    this.confirming = false;
                    this.deny(close);
                },
                () => {
                    this.updating = false;
                    this.confirming = false;
                    close();
                }
            );
        },

        search() {
            this.searched = true;
            this.searching = true;
            this.results = [];
            let params = {
                search: this.searchValue,
                user_id: this.loadUserId,
            };

            window.axios
                .post(this.searchRoute, params)
                .then(({ data }) => {
                    if (typeof data.results !== 'undefined') {
                        this.results = data.results;
                    }
                    this.searching = false;
                })
                .catch(({ data }) => {
                    this.searching = false;
                });
        },

        add(userId, closeModal) {
            this.updating = true;

            window.axios
                .post(this.route, {
                    user_id: userId,
                })
                .then(({ data }) => {
                    this.searchValue = '';
                    this.results = [];
                    window.flashAlert(data.status, {
                        type: 'success',
                        timeout: 5000,
                    });
                    this.updating = false;
                    this.updating = false;
                    this.emitUpdated(data);
                    closeModal();
                })
                .catch(({ data }) => {
                    window.flashAlert(
                        'Something went wrong please try again.',
                        { type: 'error', timeout: 5000 }
                    );
                    this.updating = false;
                    this.updating = false;
                });
        },

        confirmAdd(userId, closeModal) {
            this.updating = true;

            this.confirm(
                'new-member',
                this.confirmMessage,
                () => {
                    this.add(userId, closeModal);
                },
                () => {
                    this.updating = false;
                    closeModal();
                }
            );
        },

        clearMemberUserId() {
            if (!window.location.search.includes('member_user_id')) {
                return;
            }

            let baseUrl = `${window.location.origin}${
                window.location.pathname
            }`;
            let search = window.location.search
                .replace(/member_user_id=[a-z0-9A-Z]+/gm, '')
                .replace('?&', '?')
                .replace('&&', '&');

            // Set the state
            window.history.replaceState(
                {},
                document.title,
                search === '?' ? baseUrl : `${baseUrl}${search}`
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
            if (this.loadSearch || this.loadUserId) {
                this.search();
            }

            this.clearMemberUserId();
        },

        modalClosed() {
            if (this.loadUserId) {
                this.loadUserId = 0;
                this.results = [];
                this.searched = false;
            }
        },
    },
};
</script>
