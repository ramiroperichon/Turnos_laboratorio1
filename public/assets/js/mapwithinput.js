document.addEventListener('DOMContentLoaded', function () {
    const autocompleteInput = document.getElementById('autocomplete');
    const autocomplete = new google.maps.places.Autocomplete(autocompleteInput);
    const prevLat = document.getElementById('latitud').value;
    const prevLng = document.getElementById('logitud').value;

    autocomplete.setFields(['address_components', 'geometry']);
    autocomplete.setComponentRestrictions({
        country: ["ar"],
    });

    let map;
    let marker;
    const geocoder = new google.maps.Geocoder();


    function initMap() {
        const defaultLocation = {
            lat: parseFloat(prevLat ?? -29.14383470536903),
            lng: parseFloat(prevLng ?? -59.26513140291266),
        };

        map = new google.maps.Map(document.getElementById('mapedit'), {
            center: defaultLocation,
            zoom: 16,
            mapId: 'mapedit',
        });

        marker = new google.maps.Marker({
            position: defaultLocation,
            map: map,
            draggable: true,
        });

        marker.addListener('dragend', function () {
            const position = marker.getPosition();
            if (position) {
                updateLocationFields(position.lat(), position.lng());
                updateAutocompleteInput(position);
            }
        });

        map.addListener('click', function (event) {
            const clickedLocation = event.latLng;
            if (clickedLocation) {
                updateLocationFields(clickedLocation.lat(), clickedLocation.lng());
                updateMarkerPosition(clickedLocation);
                updateAutocompleteInput(clickedLocation);
            }
        });
    }

    autocomplete.addListener('place_changed', function () {
        const place = autocomplete.getPlace();
        if (place.geometry && place.geometry.location) {
            const selectedLocation = place.geometry.location;
            updateLocationFields(selectedLocation.lat(), selectedLocation.lng());
            updateMarkerPosition(selectedLocation);
            map.setCenter(selectedLocation);

            const customAddress = formatCustomAddress(place.address_components);
            autocompleteInput.value = customAddress;
        }
    });

    function updateMarkerPosition(location) {
        marker.setPosition(location);
    }

    function updateLocationFields(lat, lng) {
        document.getElementById('latitud').value = lat;
        document.getElementById('logitud').value = lng;
    }

    function updateAutocompleteInput(location) {
        geocoder.geocode({
            location: location
        }, function (results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    const customAddress = formatCustomAddress(results[0].address_components);
                    autocompleteInput.value = customAddress;
                } else {
                    console.error('No hay resultados para esa ubicacion.');
                }
            } else {
                console.error('Fallo no se porque: ' + status);
            }
        });
    }

    function formatCustomAddress(components) {
        const street = components.find(component => component.types.includes('route'))?.long_name || '';
        const streetNumber = components.find(component => component.types.includes('street_number'))
            ?.long_name || '';
        const city = components.find(component => component.types.includes('locality'))?.long_name || '';
        const country = components.find(component => component.types.includes('country'))?.long_name || '';

        return `${streetNumber} ${street}, ${city}, ${country}`.trim().replace(/^,|,$/g, '');
    }

    initMap();
});
