module.exports = {
    props: {
        dataNotifications: {
            type: [Array, Object],
            default() {
                return [];
            },
        },
    },

    data() {
        return {
            notifications: this.getNotifications(),
        };
    },

    methods: {
        getNotifications() {
            let notifications = this.dataNotifications;

            if (typeof notifications === 'object') {
                return Object.values(notifications);
            }

            return notifications;
        },
    },
};
