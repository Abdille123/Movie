import { fetchJson } from '../shared/fetchJson.js';
import { savedLocation } from './location.js';

// Write the latest weather text into the header bar.
export function setHeaderWeather({
    label = 'Weather',
    headline = 'Set location',
    meta = 'Use the movies page to load your local forecast.',
} = {}) {
    const labelNode = document.querySelector('[data-header-weather-label]');
    const tempNode = document.querySelector('[data-header-weather-temp]');
    const metaNode = document.querySelector('[data-header-weather-meta]');

    if (!labelNode || !tempNode || !metaNode) {
        return;
    }

    labelNode.textContent = label;
    tempNode.textContent = headline;
    metaNode.textContent = meta;
}

// Ask the Laravel weather API for forecast data.
export async function fetchWeather(coords) {
    const { data } = await fetchJson(`/api/weather?lat=${coords.lat}&lng=${coords.lng}`);

    return data;
}

// Turn weather API data into the simple header display.
export function renderWeatherInHeader(payload) {
    if (payload.temperature === undefined) {
        setHeaderWeather({
            headline: payload.summary ?? 'Weather unavailable',
            meta: payload.advice ?? 'Unable to load forecast for the saved location.',
        });

        return;
    }

    setHeaderWeather({
        label: payload.summary ?? 'Weather',
        headline: `${payload.temperature}\u00b0C`,
        meta: `${payload.rain_chance}% rain \u00b7 ${payload.wind_speed} km/h wind`,
    });
}

// Load fresh weather data for the saved location.
async function refreshHeaderWeather(coords) {
    try {
        const payload = await fetchWeather(coords);
        renderWeatherInHeader(payload);
    } catch {
        setHeaderWeather({
            headline: 'Weather unavailable',
            meta: 'Forecast could not be loaded for the saved location.',
        });
    }
}

// Start the header weather widget on page load.
export function initHeaderWeather() {
    const coords = savedLocation();

    if (!coords) {
        setHeaderWeather();
        return;
    }

    setHeaderWeather({
        headline: 'Loading...',
        meta: 'Refreshing forecast for your saved location.',
    });

    refreshHeaderWeather(coords);
}
