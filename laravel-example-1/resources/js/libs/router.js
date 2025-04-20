/**
 * Simple router used until Vue Router makes sense.
 */

class Router {
    push(url, state, title) {
        history.pushState(state ?? "", title ?? document.title, url);
    }
}

export default new Router();
