export default function (callback, timeout = 1000) {
    let timer;

    return {
        set(callback, timeout = 1000) {
            console.log(timer);
            clearTimeout(timer);
            timer = setTimeout(callback, timeout);
        }
    };
}