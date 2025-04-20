let _merge = require('lodash.merge');

module.exports = {
    individual(message, props = {}) {
        window.Bus.$emit(
            'clear-alert',
            _merge(
                {
                    message: message,
                    type: 'info',
                    dismissible: true,
                    timeout: -1,
                },
                props
            )
        );
    },

    all() {
        window.Bus.$emit('clear-alert-all');
    },
};
