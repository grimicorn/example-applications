module.exports = {
    methods: {
        confirm(id, content, confirmed, canceled, config = {}) {
            window.Bus.$emit('confirmation:requested', {
                id: id,
                content: content,
                title: config.title ? config.title : 'Confirm',
                submitLabel: config.submitLabel
                    ? config.submitLabel
                    : 'Confirm',
                challenge: config.challenge ? config.challenge : '',
            });

            let recieved = `${id}:confirmation:receieved`;
            window.Bus.$once(recieved, (confirmation) => {
                if (typeof confirmed === 'function' && confirmation) {
                    confirmed(confirmation);
                }

                if (typeof canceled === 'function' && !confirmation) {
                    canceled(confirmation);
                }
            });
        },
    },
};
