import registerScript from '@/libs/register-script';

let autocomplete;

const geoLocate = () => {
  if (!navigator.geolocation) {
    return;
  }

  navigator.geolocation.getCurrentPosition((position) => {
    const geolocation = {
      lat: position.coords.latitude,
      lng: position.coords.longitude,
    };
    const circle = new google.maps.Circle({
      center: geolocation,
      radius: position.coords.accuracy,
    });
    autocomplete.setBounds(circle.getBounds());
  });
};

export default ($el, callback) => {
  document.addEventListener('google-maps-loaded', () => {
    if (autocomplete) {
      return;
    }

    autocomplete = new google.maps.places.Autocomplete($el, {
      types: ['geocode'],
    });
    autocomplete.setFields(['address_component']);
    autocomplete.addListener('place_changed', () => callback(autocomplete));
    geoLocate();
  });

  registerScript();
};
