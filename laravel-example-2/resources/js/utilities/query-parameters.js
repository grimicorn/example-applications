import collect from "collect.js";

function getParameter($key) {
    return allParameters().get($key);
}

function allParameters() {
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

    let parameters = allParameters()
        .filter((item, property) => {
            return keys.indexOf(property) === -1;
        })
        .map(function(item, property) {
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
    all: allParameters,
    remove: removeParameters,
    get: getParameter
};
