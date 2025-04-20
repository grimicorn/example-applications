import sha256 from "crypto-js/sha256";

const hashValues = $form => {
    if (!$form) {
        return;
    }

    const values = Object.values($form).reduce((obj, field) => {
        const hasFieldName = field.name && !field.name.match(/\[\]/);
        const hasFieldValue =
            typeof field.value !== "undefined" && field.value.length !== 0;
        if (hasFieldName && hasFieldValue) {
            obj[field.name] = field.value;
        }
        return obj;
    }, {});

    return sha256(JSON.stringify(values)).toString();
};

export default $form => {
    if (!$form) {
        return;
    }

    let startHash = hashValues($form);

    $form.addEventListener("submit", () => {
        startHash = hashValues($form);
    });

    window.onbeforeunload = function() {
        if (startHash === hashValues($form)) {
            return;
        }

        return "Changes you made may not be saved.";
    };
};
