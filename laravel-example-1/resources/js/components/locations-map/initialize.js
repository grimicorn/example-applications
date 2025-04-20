import mapStyles from "./styles";
let map;
let storageCoordinates = {};

const getCurrentLocation = () => {
    if (!storageCoordinates.lat || !storageCoordinates.lng) {
        return;
    }

    return {
        lat: storageCoordinates.latitude,
        lng: storageCoordinates.longitude
    };
};

const getCoordinatesFromData = ({ coordinates }) => {
    if (!coordinates.lat || !coordinates.lng) {
        return {};
    }

    return { lat: coordinates.lat, lng: coordinates.lng };
};

const getCenter = data => {
    const coordinates = getCoordinatesFromData(data);
    if (coordinates.lat && coordinates.lng) {
        return { lat: coordinates.lat, lng: coordinates.lng };
    }

    const currentLocation = getCurrentLocation();
    if (currentLocation) {
        return currentLocation;
    }

    return {
        lat: 38.6530169,
        lng: -90.3835463
    };
};

const addMarker = (location, config = {}) => {
    return new google.maps.Marker({
        position: {
            lat: location.latitude ? location.latitude : location.lat,
            lng: location.longitude ? location.longitude : location.lng
        },
        map: map,
        animation: google.maps.Animation.DROP,
        title: location.name,
        ...config
    });
};

const setBoundsForMarkers = markers => {
    let bounds = new google.maps.LatLngBounds();
    for (let i = 0; i < markers.length; i++) {
        bounds.extend(markers[i].getPosition());
    }

    map.fitBounds(bounds);
};

const addMarkers = ({ center, locations }) => {
    let markers = [];
    markers.push(addMarker({ ...center, name: "Search Location" }));

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

const initializeMap = ($map, data) => {
    const center = getCenter(data.data);

    map = new google.maps.Map($map, {
        center: center,
        styles: mapStyles
    });

    setBoundsForMarkers(
        addMarkers({ center, locations: data.data.locations.data ?? [] })
    );
};

export default ({ $map, usersCurrentLocation }) => {
    storageCoordinates = usersCurrentLocation;

    if (!$map) {
        return;
    }
    window.axios.get(window.location.href).then(({ data }) => {
        initializeMap($map, data);
    });
};
