let _merge = require('lodash.merge');

module.exports = function(message, props = {}) {
    window.Bus.$emit('flash-alert', _merge({
        message: message,
        type: 'info',
        dismissible: true,
        timeout: -1,
    }, props));
};
