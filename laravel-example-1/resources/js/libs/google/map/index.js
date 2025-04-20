import mapStyles from "./styles";
import registerScript from "@/libs/register-script";
let map;
let usersCurrentLocation = {};
let locations = [];
let centerCoordinates = {};

const getCurrentLocation = () => {
    if (!usersCurrentLocation.lat || !usersCurrentLocation.lng) {
        return;
    }

    return {
        lat: usersCurrentLocation.latitude,
        lng: usersCurrentLocation.longitude
    };
};

const getCenter = () => {
    const shared = {
        name: "Current Location",
        isCenter: true,
        icon: {
            url: "/images/google-map-icons/you-are-here-2.png"
        }
    };

    if (centerCoordinates.lat && centerCoordinates.lng) {
        return {
            lat: centerCoordinates.lat,
            lng: centerCoordinates.lng,
            ...shared
        };
    }

    const currentLocation = getCurrentLocation();
    if (currentLocation) {
        return { ...currentLocation, ...shared };
    }

    return {
        lat: 38.6530169,
        lng: -90.3835463,
        ...shared
    };
};

const addMarker = (location, config = {}) => {
    let content = [
        `<h3 class="mb-0 font-bold">${location.name}</h3>`,
        `<strong>Rating:</strong> ${location.rating ? location.rating : "N/A"}`,
        `<strong>Visited:</strong> ${location.visited ? "Yes" : "No"}`,
        ``,
        [
            `<a href="${location.google_maps_link})}" target="_blank" class="underline">Directions</a>`,
            `|`,
            `<a href="${window.route("locations.edit", {
                location
            })}" target="_blank" class="underline">Edit</a>`
        ].join(" ")
    ];

    if (location.isCenter) {
        content = [location.name];
    }

    const info = new google.maps.InfoWindow({
        content: content.join("<br>")
    });

    const marker = new google.maps.Marker({
        position: {
            lat: location.latitude ? location.latitude : location.lat,
            lng: location.longitude ? location.longitude : location.lng
        },
        map: map,
        animation: google.maps.Animation.DROP,
        title: location.name,
        icon: location.icon ? location.icon.url : undefined,
        ...config
    });

    marker.addListener("click", () => {
        info.open(map, marker);
    });

    return marker;
};

const setBoundsForMarkers = markers => {
    let bounds = new google.maps.LatLngBounds();
    for (let i = 0; i < markers.length; i++) {
        bounds.extend(markers[i].getPosition());
    }

    map.fitBounds(bounds);
};

const addMarkers = ({ center }) => {
    let markers = [];
    markers.push(addMarker({ ...center }));

    const currentLocation = getCurrentLocation();
    if (currentLocation) {
        markers.push(
            addMarker({
                ...currentLocation,
                name: "Current Location"
            })
        );
    }

    locations.forEach(location => {
        markers.push(addMarker(location));
    });

    return markers;
};

const initializeMap = $map => {
    const center = getCenter();

    map = new google.maps.Map($map, {
        center: center,
        styles: mapStyles
    });

    setBoundsForMarkers(addMarkers({ center }));
};

export default configuration => {
    const $map = configuration.$map;
    locations = configuration.locations ?? [];
    centerCoordinates = configuration.centerCoordinates ?? {};
    usersCurrentLocation = configuration.usersCurrentLocation ?? {};
    if (!$map) {
        return;
    }

    // Handle the initial load.
    document.addEventListener("google-maps-loaded", () => {
        if (map) {
            return;
        }

        initializeMap($map);
    });

    registerScript();

    // Handle Re-renders
    if (map) {
        initializeMap($map);
    }
};
