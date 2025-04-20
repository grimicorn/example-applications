import _filter from "lodash.filter";
import _foreach from "lodash.foreach";

export default {
    keyMatches(model, key, property = "id") {
        return model[property] == key;
    },

    removeByKey(models, key, property = "id") {
        return _filter(models, model => {
            return !this.keyMatches(model, key, property);
        });
    },

    matchingIndex(models, key, property = "id") {
        let matchingIndex = null;
        _foreach(models, (model, index) => {
            if (this.keyMatches(model, key, property)) {
                matchingIndex = index;
                return false;
            }
        });

        return matchingIndex;
    },

    replaceByKey(models, key, model, property = "id") {
        let matchingIndex = this.matchingIndex(models, key, property);

        if (matchingIndex === null) {
            return;
        }

        models[matchingIndex] = model;

        return models;
    },

    addByKey(models, key, model, property = "id") {
        let matchingIndex = this.matchingIndex(models, key, property);

        if (matchingIndex === null) {
            models.push(model);
            return models;
        }

        return this.replaceByKey(models, key, model, property);
    }
};
