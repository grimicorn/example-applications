export default (parameter, defaultValue = undefined) => {
    const urlParams = new URLSearchParams(window.location.search);
    const isArray = parameter.indexOf("[]") !== -1;
    let value;

    if (isArray) {
        value = urlParams.getAll(parameter);
    } else {
        value = urlParams.get(parameter);
    }

    return value ? value : defaultValue;
};
