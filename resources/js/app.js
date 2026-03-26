import './bootstrap';
import { initCatalogueSearch } from './features/catalogueSearch.js';
import { initHeaderWeather } from './features/headerWeather.js';
import { initMobileNav } from './features/mobileNav.js';
import { bindPlannerButtons, updatePlannerUi } from './features/planner.js';
import { initReviewForm } from './features/reviews.js';
import { initTripTools } from './features/tripTools.js';

// Start each front-end feature after the page is ready.
document.addEventListener('DOMContentLoaded', () => {
    bindPlannerButtons(document);
    updatePlannerUi();
    initMobileNav();
    initHeaderWeather();
    initCatalogueSearch();
    initReviewForm();
    initTripTools();
});
