export default {
    new() {
        return {
            timer: undefined,
            set(callback, timeout = 1000) {
                clearTimeout(this.timer);
                this.timer = setTimeout(callback, timeout);
            }
        };
    }
};
