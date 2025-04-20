export default class ModelEvents {
    constructor(user) {
        this.user = user;
    }

    deleted(modelType, callback) {
        window.Echo.private(`user.${this.user.id}`).listen(
            "BroadcastingModelDeletedEvent",
            e => {
                if (e.modelType === modelType || !e.modelKey) {
                    callback(e);
                }
            }
        );
    }

    saved(modelType, callback) {
        window.Echo.private(`user.${this.user.id}`).listen(
            "BroadcastingModelEvent",
            e => {
                if (e.modelType !== modelType || !e.model) {
                    return;
                }

                if (e.eventType !== "updated" && e.eventType !== "created") {
                    return;
                }

                callback(e);
            }
        );
    }
}
