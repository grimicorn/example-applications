module.exports = {
    gtagReportConversion(url, callback = function() {}) {
        if (typeof gtag !== 'function') {
            return;
        }

        gtag('event', 'conversion', {
            send_to: 'AW-837465225/G7EOCOSHwoYBEInpqo8D',
            event_callback: callback,
        });
        return false;
    },
};
