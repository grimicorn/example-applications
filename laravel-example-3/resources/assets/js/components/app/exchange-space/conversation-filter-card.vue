
<template>
<div>
    <div class="pa2 bb1">
        <div id="overlay_diligence_conversation_step" class="flex pt2 pb1 pl2 pr1">
            <div class="flex-4">
                <div class="flex mt1">
                    <div class="flex-3 pr1">
                        <a :href="creatorUser.profile_url" target="_blank">
                            <avatar
                            :src="creatorUser.photo_thumbnail_small_url"
                            width="44"
                            height="44"
                            image-class="rounded"
                            :uses-two-factor="creatorUser.uses_two_factor_auth"
                            :initials="creatorUser.initials"></avatar>
                        </a>

                    </div>

                    <div class="flex-9 mt1">
                        <a :href="creatorUser.profile_url" target="_blank">
                            <div
                            class="fz-16 fc-color6 pb1 lh-solid a-ul"
                            v-text="creatorUser.name"></div>
                        </a>
                        <div class="lh-solid fz-14"
                        v-text="creatorMember.role_label"></div>
                    </div>
                </div>
            </div>
            <div class="flex-7 flex">
                <i
                    aria-hidden="true"
                    class="notification-icon fc-color5 mr1 lh-solid"
                    :style="conversation.has_notifications ? '' : 'opacity:0'"
                ></i>
                <div>
                    <div>
                        <div class="fz-14 lh-16 fc-color6" v-text="conversation.category_label"
                        v-if="!isInquiry"></div>

                        <a :href="conversation.show_url" class="fc-color4 mr1">
                            {{ cardTitle | stringTrim(65, (cardTitle.length > 65)) }}
                        </a>
                    </div>
                    <div
                    class="lh-copy pb1 fz-14 breakword">
                        {{ latestMessage.body | stringTrim(225, (latestMessage.body.length > 225)) }}</div>
                    <div class="fc-color10 fz-14">
                        <span class="text-italic">
                            <timezone-datetime
                            :date="latestMessage.created_at"></timezone-datetime>
                        </span>
                    </div>
                </div>
            </div>

            <div
            class="flex-2 text-center"
            v-if="!isInquiry">
                <div class="conversation-resolve-input inline-block">
                    <input
                    type="checkbox"
                    :name="`resolved_${conversation.id}`"
                    :id="`resolved_${conversation.id}`"
                    value="on"
                    :checked="isResolved"
                    :disabled="conversation.is_inquiry || updatingResolved"
                    @change="resolveToggle">

                    <label :for="`resolved_${conversation.id}`">Resolved</label>
                </div>
            </div>
        </div>
    </div>
</div>

</template>

<script>
let filters = require('./../../../mixins/filters.js');

module.exports = {
    mixins: [filters],

    props: {
        conversation: {
            type: Object,
            required: true,
        },
        space: {
            type: Object,
            required: false,
        },
        isInquiry: {
            type: Boolean,
            default: false,
        },
    },

    data() {
        return {
            isResolved: this.conversation.resolved,
            creatorUser: this.conversation.latest_message.creator_member.user,
            creatorMember: this.conversation.latest_message.creator_member,
            latestMessage: this.conversation.latest_message,
            updatingResolved: false,
        };
    },

    computed: {
        resolveRoute() {
            return `/dashboard/exchange-spaces/conversation/${
                this.conversation.id
            }/resolve`;
        },
        cardTitle() {
            if (this.isInquiry) {
                return this.conversation.space.listing.title;
            }

            return this.conversation.title;
        },
    },

    methods: {
        resolveToggle() {
            if (this.isResolved) {
                this.unresolve();
            } else {
                this.resolve();
            }
        },

        resolve() {
            this.updatingResolved = true;
            window.axios
                .post(this.resolveRoute)
                .then(({ data }) => {
                    this.updatingResolved = false;
                    this.isResolved = true;
                    this.fireAlert(data.status, 'resolved');
                })
                .catch(function(error) {
                    this.updatingResolved = false;
                });
        },

        unresolve() {
            this.updatingResolved = true;
            window.axios
                .delete(this.resolveRoute)
                .then(({ data }) => {
                    this.updatingResolved = false;
                    this.isResolved = false;
                    this.fireAlert(data.status, 'unresolved');
                })
                .catch(function(error) {
                    this.updatingResolved = false;
                });
        },

        fireAlert(status, action) {
            window.flashAlert(status, {
                timeout: 3000,
                type: 'success',
            });
            this.$emit(action);
            window.Bus.$emit(`conversation-${action}`);
        },
    },
};
</script>
