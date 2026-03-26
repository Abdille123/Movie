const locationStorageKey = 'reelroute-last-location';

// Read the last saved map location from local storage.
export function savedLocation() {
    try {
        const value = localStorage.getItem(locationStorageKey);

        return value ? JSON.parse(value) : null;
    } catch {
        return null;
    }
}

// Save the user's chosen map location for later visits.
export function storeLocation(coords) {
    localStorage.setItem(locationStorageKey, JSON.stringify(coords));
}
