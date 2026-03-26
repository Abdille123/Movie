import { bindPlannerButtons } from './planner.js';
import { fetchJson } from '../shared/fetchJson.js';

// Run the AJAX movie search and its suggestion dropdown.
export function initCatalogueSearch() {
    const form = document.querySelector('[data-catalogue-form]');

    if (!form) {
        return;
    }

    const grid = document.querySelector('[data-movie-grid]');
    const count = document.querySelector('[data-results-count]');
    const searchFeedback = document.querySelector('[data-search-feedback]');
    const suggestions = document.querySelector('[data-suggestions]');
    const searchField = form.querySelector('[name="q"]');
    const endpoint = form.action;
    const fields = form.querySelectorAll('input, select');
    let timeoutId = null;
    let requestId = 0;

    const setSearchFeedback = (message = 'AJAX search is always on.') => {
        if (searchFeedback) {
            searchFeedback.textContent = message;
        }
    };

    // Remove any open suggestion links.
    const clearSuggestions = () => {
        suggestions.replaceChildren();
    };

    // Show the latest suggestion links under the search box.
    const renderSuggestions = (items) => {
        clearSuggestions();

        if (!items.length) {
            return;
        }

        items.forEach((item) => {
            const link = document.createElement('a');
            link.className = 'suggestion-link';
            link.href = item.url;
            link.textContent = item.title;
            suggestions.append(link);
        });
    };

    // Fetch filtered movies and replace the movie grid.
    const runSearch = async () => {
        const activeRequestId = ++requestId;
        const params = new URLSearchParams(new FormData(form));
        try {
            const { response, data: payload } = await fetchJson(`${endpoint}?${params.toString()}`);

            if (activeRequestId !== requestId) {
                return;
            }

            if (!response.ok) {
                throw new Error('Search request failed');
            }

            grid.innerHTML = payload.html;
            count.textContent = String(payload.count);
            bindPlannerButtons(grid);
            setSearchFeedback();

            const query = String(searchField?.value || '').trim();
            if (query.length > 1) {
                renderSuggestions(Array.isArray(payload.suggestions) ? payload.suggestions : []);
                return;
            }

            clearSuggestions();
        } catch {
            if (activeRequestId !== requestId) {
                return;
            }

            clearSuggestions();
            setSearchFeedback('Search could not update right now. Showing the last loaded results.');
            return;
        }
    };

    // Keep search results on the page without a full reload.
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        await runSearch();
    });

    fields.forEach((field) => {
        field.addEventListener('input', () => {
            if (field === searchField && String(searchField.value || '').trim().length < 2) {
                clearSuggestions();
            }

            window.clearTimeout(timeoutId);
            timeoutId = window.setTimeout(runSearch, 220);
        });

        field.addEventListener('change', runSearch);
    });

    // Reopen suggestions when the user returns to the search box.
    searchField?.addEventListener('focus', async () => {
        if (String(searchField.value || '').trim().length > 1 && !suggestions.children.length) {
            await runSearch();
        }
    });

    // Let the user close the dropdown with the Escape key.
    searchField?.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            clearSuggestions();
            searchField.blur();
        }
    });

    // Close the dropdown when the user clicks somewhere else.
    document.addEventListener('click', (event) => {
        if (!form.contains(event.target)) {
            clearSuggestions();
        }
    });
}
