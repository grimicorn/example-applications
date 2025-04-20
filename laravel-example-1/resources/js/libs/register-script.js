const googleLoaded = (callback, interval = 100, maxWaitDuration = 5000) => {
    const checkInterval = setInterval(() => {
        if (!window.google) {
            return;
        }

        clearInterval(checkInterval);
        callback();
    }, interval);

    // Make sure we are not infinitely checking.
    setTimeout(() => clearInterval(checkInterval), maxWaitDuration);
};

// Simulate loading App
setTimeout(() => (window.App = {}), Math.floor(Math.random() * 5000));

const scriptExists = () => {
    return !!document.head.querySelector("#google_maps_api");
};

const dispatchLoadedEvent = $eventEl => {
    googleLoaded(() => {
        $eventEl.dispatchEvent(new Event("google-maps-loaded"));
    });
};

export default ($eventEl = document) => {
    if (scriptExists()) {
        dispatchLoadedEvent($eventEl);
        return;
    }

    let script = document.createElement("script");
    script.id = "google_maps_api";
    script.src = `https://maps.googleapis.com/maps/api/js?key=${process.env.MIX_GOOGLE_API_KEY}&callback=initGoogleMap&libraries=places`;
    script.defer = true;

    window.initGoogleMap = () => dispatchLoadedEvent($eventEl);

    document.head.appendChild(script);
};
