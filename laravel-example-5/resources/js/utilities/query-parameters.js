import collect from "collect.js";

function getParameters() {
    return collect(window.location.search.replace("?", "").split("&"))
        .filter()
        .mapWithKeys(function(item) {
            item = item.split("=");
            return [item[0], decodeURIComponent(item[1])];
        });
}

function removeParameters(...keys) {
    if (!keys) {
        return;
    }

    let parameters = getParameters()
        .filter((item, property) => {
            return keys.indexOf(property) === -1;
        })
        .map(function(item, property) {
            item = item;
            return encodeURI(`${property}=${item}`);
        })
        .values()
        .implode("&")
        .trim();

    if (parameters) {
        parameters = `?${parameters}`;
    }

    let location = window.location;
    let origin = location.origin;
    let pathname = location.pathname;

    history.replaceState(
        {},
        document.title,
        `${origin}${pathname}${parameters}`
    );
}

export default {
    get: getParameters,
    remove: removeParameters
};
