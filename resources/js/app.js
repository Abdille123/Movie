import './bootstrap';

const plannerStorageKey = 'reelroute-shortlist';
const locationStorageKey = 'reelroute-last-location';

document.addEventListener('DOMContentLoaded', () => {
    bindPlannerButtons(document);
    updatePlannerUi();
    initCatalogueSearch();
    initReviewForm();
    initTripTools();
});

function plannerItems() {
    try {
        return JSON.parse(localStorage.getItem(plannerStorageKey) ?? '[]');
    } catch {
        return [];
    }
}

function savePlanner(items) {
    localStorage.setItem(plannerStorageKey, JSON.stringify(items));
    updatePlannerUi();
}

function bindPlannerButtons(root) {
    root.querySelectorAll('[data-plan-button]').forEach((button) => {
        if (button.dataset.bound === 'true') {
            return;
        }

        button.dataset.bound = 'true';
        button.addEventListener('click', () => {
            const item = {
                id: Number(button.dataset.movieId),
                title: button.dataset.movieTitle,
                url: button.dataset.movieUrl,
            };

            const items = plannerItems();
            const exists = items.find((entry) => entry.id === item.id);
            const nextItems = exists
                ? items.filter((entry) => entry.id !== item.id)
                : [...items, item];

            savePlanner(nextItems);
        });
    });
}

function updatePlannerUi() {
    const items = plannerItems();

    document.querySelectorAll('[data-planner-count]').forEach((node) => {
        node.textContent = String(items.length);
    });

    document.querySelectorAll('[data-plan-button]').forEach((button) => {
        const active = items.some((item) => item.id === Number(button.dataset.movieId));
        button.textContent = active ? 'Remove from shortlist' : 'Add to shortlist';
        button.classList.toggle('button-primary', active);
        button.classList.toggle('button-ghost', !active);
    });

    document.querySelectorAll('[data-planner-list]').forEach((container) => {
        if (!items.length) {
            container.innerHTML = '<p class="planner-empty">No films saved yet.</p>';
            return;
        }

        container.innerHTML = items
            .map(
                (item) => `
                    <article class="planner-item">
                        <a href="${item.url}">${item.title}</a>
                        <button class="button button-ghost" type="button" data-remove-id="${item.id}">Remove</button>
                    </article>
                `,
            )
            .join('');

        container.querySelectorAll('[data-remove-id]').forEach((button) => {
            button.addEventListener('click', () => {
                savePlanner(plannerItems().filter((item) => item.id !== Number(button.dataset.removeId)));
            });
        });
    });
}

function initCatalogueSearch() {
    const form = document.querySelector('[data-catalogue-form]');

    if (!form) {
        return;
    }

    const grid = document.querySelector('[data-movie-grid]');
    const count = document.querySelector('[data-results-count]');
    const suggestions = document.querySelector('[data-suggestions]');
    const endpoint = form.action;
    const fields = form.querySelectorAll('input, select');
    let timeoutId = null;

    const runSearch = async () => {
        const params = new URLSearchParams(new FormData(form));
        const response = await fetch(`${endpoint}?${params.toString()}`, {
            headers: { Accept: 'application/json' },
        });
        const payload = await response.json();

        grid.innerHTML = payload.html;
        count.textContent = String(payload.count);
        bindPlannerButtons(grid);

        const query = String(form.querySelector('[name="q"]').value || '').trim();
        suggestions.innerHTML = query.length > 1
            ? payload.suggestions
                .map((item) => `<a class="suggestion-link" href="${item.url}">${item.title}</a>`)
                .join('')
            : '';
    };

    fields.forEach((field) => {
        field.addEventListener('input', () => {
            window.clearTimeout(timeoutId);
            timeoutId = window.setTimeout(runSearch, 220);
        });

        field.addEventListener('change', runSearch);
    });
}

function initReviewForm() {
    const form = document.querySelector('[data-review-form]');

    if (!form) {
        return;
    }

    const feedback = document.querySelector('[data-review-errors]');
    const reviewList = document.querySelector('[data-review-list]');
    const reviewCount = document.querySelector('[data-review-count]');
    const reviewAverage = document.querySelector('[data-review-average]');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        feedback.textContent = 'Sending review...';

        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: new FormData(form),
        });

        const payload = await response.json();

        if (!response.ok) {
            const errors = Object.values(payload.errors ?? {}).flat();
            feedback.textContent = errors.join(' ');
            return;
        }

        reviewList.insertAdjacentHTML('afterbegin', payload.html);
        reviewCount.textContent = payload.review_count;
        reviewAverage.textContent = Number(payload.average_rating).toFixed(1);
        feedback.textContent = payload.message;
        form.reset();
    });
}

function initTripTools() {
    const wrapper = document.querySelector('[data-nearby-tool]');

    if (!wrapper) {
        return;
    }

    const button = wrapper.querySelector('[data-locate-button]');
    const status = wrapper.querySelector('[data-location-status]');
    const weatherPanel = wrapper.querySelector('[data-weather-panel]');
    const cinemaList = wrapper.querySelector('[data-cinema-list]');
    const mapNode = wrapper.querySelector('[data-map]');
    let map = null;
    let markers = [];

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

    const renderWeather = (payload) => {
        weatherPanel.innerHTML = `
            <span class="eyebrow">Weather API</span>
            <h2>${payload.summary}</h2>
            <p>${payload.advice}</p>
            <div class="movie-scores">
                ${payload.temperature !== undefined ? `<span>${payload.temperature}&deg;C</span>` : ''}
                ${payload.rain_chance !== undefined ? `<span>${payload.rain_chance}% rain</span>` : ''}
                ${payload.wind_speed !== undefined ? `<span>${payload.wind_speed} km/h wind</span>` : ''}
            </div>
        `;
    };

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

    const loadData = async (coords, label = 'Saved location loaded.') => {
        status.textContent = 'Loading nearby cinemas and weather...';
        localStorage.setItem(locationStorageKey, JSON.stringify(coords));

        const [cinemaResponse, weatherResponse] = await Promise.all([
            fetch(`/api/cinemas/nearby?lat=${coords.lat}&lng=${coords.lng}`, { headers: { Accept: 'application/json' } }),
            fetch(`/api/weather?lat=${coords.lat}&lng=${coords.lng}`, { headers: { Accept: 'application/json' } }),
        ]);

        const cinemaPayload = await cinemaResponse.json();
        const weatherPayload = await weatherResponse.json();

        renderCinemas(cinemaPayload.cinemas ?? []);
        renderWeather(weatherPayload);
        renderMap(coords, cinemaPayload.cinemas ?? []);
        status.textContent = label;
    };

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

    const savedLocation = localStorage.getItem(locationStorageKey);

    if (savedLocation) {
        try {
            loadData(JSON.parse(savedLocation));
        } catch {
            localStorage.removeItem(locationStorageKey);
        }
    }
}
