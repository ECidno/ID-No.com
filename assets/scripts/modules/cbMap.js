import { control, latLng, map, tileLayer, Browser } from 'leaflet';

export const name = 'cbMap';

/**
 * cloudbase | map
 *
 * @param int lat
 * @param int lon
 */
const cbMap = (lat, lon) => {
  const mapContainer = document.getElementById('mapContainer') || null;

  var bounds = [];
  var positionMap;
  var positionLatLng;
  var positionMarker;
  var positionPopup;

  let latitude = lat || mapContainer.dataset.lat;
  let longitude = lon || mapContainer.dataset.lon;
  let geocodeUrl = [
    mapContainer.dataset.geocodeUrl,
    'lat=' + latitude,
    'lon=' + longitude,
    'apiKey=' + mapContainer.dataset.geocodeKey
  ].join('&');

  // move to position
  if(positionMap) {
    positionLatLng = L.latLng(
      latitude,
      longitude
    );

    // position marker
    positionMarker.setLatLng(positionLatLng);

    // position map
    positionMap.flyTo(
      positionLatLng,
      mapContainer.dataset.zoom
    );

  // create map
  } else {
    positionLatLng = L.latLng(
      latitude,
      longitude
    );

    // position map
    positionMap = L
      .map(mapContainer.id)
      .setView(
        [
          latitude,
          longitude
        ],
        mapContainer.dataset.zoom
      );

    // Retina displays require different mat tiles quality
    const isRetina = L.Browser.retina;
    const baseUrl = mapContainer.dataset.baseUrl;
    const retinaUrl = mapContainer.dataset.retinaUrl;

    // Add map tiles layer. Set 20 as the maximal zoom and provide map data attribution.
    L
      .tileLayer(
        isRetina
          ? retinaUrl
          : baseUrl,
        {
          attribution: 'Powered by <a href="https://www.geoapify.com/" target="_blank">Geoapify</a> | <a href="https://openmaptiles.org/" target="_blank">© OpenMapTiles</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">© OSM</a>',
          apiKey: mapContainer.dataset.apiKey,
          maxZoom: 20,
          id: mapContainer.dataset.type,
        }
      )
      .addTo(positionMap);

      // position marker
      positionMarker = L.marker(
        positionLatLng,
        {
          icon: L.divIcon({
            html: '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#91B54D" class="bi bi-geo-alt-fill" viewBox="0 0 16 16"><path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/></svg>',
            iconSize: [1, 1],
            iconAnchor: [16, 0],
          })
        }
      )
      .addTo(positionMap);

    // geocode
    let geocodeRes = fetch(
      geocodeUrl,
      {
        method: 'GET',
      }
    )
    .then(response => response.json())
    .then(result => {

      if(result.features[0].properties.formatted || false) {
        positionPopup = L
          .popup()
          .setContent('<small>' + result.features[0].properties.formatted + '</small>')

        positionMarker
          .bindPopup(positionPopup)
          .openPopup();
      }
    })
    .catch(error => console.log('error', error));
  }
}

/**
 * cloudbase | map
 *
 * @param object error
 * @param int lon
 */
const cbMapLocationError = (error) => {
  const mapErrorContainer = document.getElementById('mapErrorContainer') || null;
  let errorStr;

  // switch error
  switch (error.code) {
    case error.PERMISSION_DENIED:
      errorStr = mapContainer.dataset.errorDenied;
      break;
    case error.POSITION_UNAVAILABLE:
      errorStr = mapContainer.dataset.errorUnavailable;
      break;
    case error.TIMEOUT:
      errorStr = mapContainer.dataset.errorTimeout;
      break;
    case error.UNKNOWN_ERROR:
      errorStr = mapContainer.dataset.errorUnknown;
      break;
    default:
      errorStr = mapContainer.dataset.errorUnknown;
  }
  console.error('Error occurred: ' + errorStr);

  // show error
  mapErrorContainer.innerHTML = errorStr;
  mapErrorContainer.classList.remove('d-none');
}

// export
export { cbMap, cbMapLocationError }