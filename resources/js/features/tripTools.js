import { fetchJson } from '../shared/fetchJson.js';
import { fetchWeather, renderWeatherInHeader, setHeaderWeather } from './headerWeather.js';
import { savedLocation, storeLocation } from './location.js';

// Ask the Laravel cinema API for nearby cinema data.
async function fetchCinemas(coords) {
    const { data } = await fetchJson(`/api/cinemas/nearby?lat=${coords.lat}&lng=${coords.lng}`);

    return data;
}

// Handle location lookup, weather loading, cinema loading, and the map.
export function initTripTools() {
    const wrapper = document.querySelector('[data-nearby-tool]');

    if (!wrapper) {
        return;
    }

    const button = wrapper.querySelector('[data-locate-button]');
    const status = wrapper.querySelector('[data-location-status]');
    const cinemaList = wrapper.querySelector('[data-cinema-list]');
    const mapNode = wrapper.querySelector('[data-map]');
    let map = null;
    let markers = [];

    // Draw the map and place pins for the user and nearby cinemas.
    const renderMap = (origin, cinemas) => {
        if (!window.L) {
            return;
        }

        if (!map) {
            map = window.L.map(mapNode).setView([origin.lat, origin.lng], 12);
            window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors',
            }).addTo(map);
        }

        markers.forEach((marker) => marker.remove());
        markers = [];

        markers.push(window.L.marker([origin.lat, origin.lng]).addTo(map).bindPopup('Your location'));

        cinemas.forEach((cinema) => {
            markers.push(
                window.L.marker([cinema.latitude, cinema.longitude])
                    .addTo(map)
                    .bindPopup(`<strong>${cinema.name}</strong><br>${cinema.address || 'Address unavailable'}`),
            );
        });

        map.setView([origin.lat, origin.lng], 12);
    };

    // Show the nearby cinema list under the map.
    const renderCinemas = (cinemas) => {
        cinemaList.innerHTML = cinemas.length
            ? cinemas
                .map(
                    (cinema) => `
                        <article class="cinema-item">
                            <div>
                                <strong>${cinema.name}</strong>
                                <p>${cinema.address || 'Address unavailable'}</p>
                            </div>
                            <strong>${cinema.distance_km} km</strong>
                        </article>
                    `,
                )
                .join('')
            : '<p>No cinema results were returned for this area.</p>';
    };

    // Load all live trip data for a set of coordinates.
    const loadData = async (coords, label = 'Saved location loaded.') => {
        status.textContent = 'Loading nearby cinemas and weather...';
        storeLocation(coords);

        try {
            const [cinemaPayload, weatherPayload] = await Promise.all([
                fetchCinemas(coords),
                fetchWeather(coords),
            ]);

            renderCinemas(cinemaPayload.cinemas ?? []);
            renderMap(coords, cinemaPayload.cinemas ?? []);
            renderWeatherInHeader(weatherPayload);
            status.textContent = label;
        } catch {
            status.textContent = 'Could not load cinema and weather data for this location.';
            setHeaderWeather({
                headline: 'Weather unavailable',
                meta: 'Forecast could not be loaded for the selected location.',
            });
        }
    };

    // Ask the browser for the user's current location.
    button.addEventListener('click', () => {
        if (!navigator.geolocation) {
            status.textContent = 'Geolocation is not available on this device.';
            return;
        }

        status.textContent = 'Requesting your location...';

        navigator.geolocation.getCurrentPosition(
            ({ coords }) => loadData(
                { lat: coords.latitude, lng: coords.longitude },
                'Current location found.',
            ),
            () => {
                status.textContent = 'Location access was denied or unavailable.';
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
            },
        );
    });

    const coords = savedLocation();

    // Reuse the saved location when the page loads again.
    if (coords) {
        loadData(coords);
    }
}
