import { fetchJson } from '../shared/fetchJson.js';

// Submit the review form with AJAX and update the page instantly.
export function initReviewForm() {
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

        const { response, data: payload } = await fetchJson(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: new FormData(form),
        });

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
