const plannerStorageKey = 'reelroute-shortlist';

// Read the saved shortlist from local storage.
function plannerItems() {
    try {
        return JSON.parse(localStorage.getItem(plannerStorageKey) ?? '[]');
    } catch {
        return [];
    }
}

// Save the shortlist and refresh all shortlist widgets.
function savePlanner(items) {
    localStorage.setItem(plannerStorageKey, JSON.stringify(items));
    updatePlannerUi();
}

// Wire up shortlist buttons on movie cards and detail pages.
export function bindPlannerButtons(root) {
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

// Update shortlist counts, button labels, and saved item lists.
export function updatePlannerUi() {
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
