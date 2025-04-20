module.exports = {
    fbq(message) {
        if (typeof fbq !== 'function') {
            return;
        }

        fbq('track', message);
    },
};
