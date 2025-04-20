<template>
    <!--
    - Each notification should have a message that is resolved via \App\Support\Notification\NotificationMessage::getBody()
    - Each message should have an icon that is resolved via \App\Support\Notification\NotificationMessage::getIcon()
    -->
    <div
    class="notification-message"
    :class="{
        'bg-notify-new': !this.read,
    }"
    @click="goToUrl">
        <div class="flex bb1 pt2 pb1  pl2 pr1">
            <div class="pr2 flex-auto">
                <i
                class="fa img-circle bg-color5 fc-color2"
                :class="icon"
                aria-hidden="true"></i>
            </div>
            <div class="flex-12">
                <div
                class="lh-copy"
                v-html="body"></div>

                <div class="nowrap">
                    <timezone-datetime
                    class="text-italic fz-14"
                    :date="createdAt"></timezone-datetime>

                    <span>|</span>

                    <button
                    v-if="!read"
                    class="a-ul btn btn-link pointer"
                    type="button"
                    :disabled="markingAsRead || deleting"
                    @click.stop="handleMarkRead">
                        <i
                        class="fa fa-spinner fa-spin"
                        aria-hidden="true"
                        v-if="markingAsRead"></i>
                        {{ readLabel }}
                    </button>

                    <span v-else @click.stop>Read</span>

                    <span>|</span>

                    <button
                    class="a-ul btn btn-link pointer"
                    type="button"
                    :disabled="markingAsRead || deleting"
                    @click.stop="handleDelete">
                        <i
                        class="fa fa-spinner fa-spin"
                        aria-hidden="true"
                        v-if="deleting"></i>

                        {{ deleteLabel }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
module.exports = {
    props: {
        notification: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            read: this.notification.read,
            url: this.notification.url,
            id: this.notification.id,
            body: this.notification.message_body,
            icon: this.notification.message_icon,
            createdAt: this.notification.created_at,
            markingAsRead: false,
            deleting: false,
        };
    },

    computed: {
        readUrl() {
            return `/dashboard/notifications/${this.id}/update`;
        },

        readLabel() {
            return this.markingAsRead ? 'Marking Read' : 'Mark as read';
        },

        deleteUrl() {
            return `/dashboard/notifications/${this.id}/destroy`;
        },

        deleteLabel() {
            return this.deleting ? 'Deleting' : 'Delete';
        },
    },

    methods: {
        isCurrentUrl() {
            if (!this.url) {
                return false;
            }

            return window.location.href.indexOf(this.url) >= 0;
        },

        handleMarkRead() {
            this.markingAsRead = true;
            this.markRead(() => {
                this.markingAsRead = false;
            });
        },

        markRead(callback = function() {}) {
            window.axios
                .post(this.readUrl, {
                    read: 1,
                    type: this.notification.type,
                })
                .then(response => {
                    this.read = true;
                    callback(true, response);
                    this.$emit('read', this.id);
                })
                .catch(response => {
                    callback(false, response);
                });
        },

        handleDelete() {
            this.deleting = true;
            this.delete(success => {
                this.deleting = false;
            });
        },

        delete(callback = function() {}) {
            window.axios
                .post(this.deleteUrl, {
                    _method: 'DELETE',
                    type: this.notification.type,
                })
                .then(response => {
                    callback(true, response);
                    this.$emit('deleted', this.id);
                })
                .catch(response => {
                    callback(false, response);
                });
        },

        goToUrl() {
            // If it has not been marked as read then mark it.
            if (!this.read) {
                this.markRead(() => {
                    if (this.url) {
                        window.location = this.url;
                    }
                });

                return;
            }

            // If it has been marked just go to the url
            if (this.url && !this.isCurrentUrl()) {
                window.location = this.url;
            }
        },
    },
};
</script>
