const snapshotStorageKey = 'reelroute-camera-snapshot';

// Read the last saved camera snapshot from local storage.
function savedSnapshot() {
    try {
        return localStorage.getItem(snapshotStorageKey);
    } catch {
        return null;
    }
}

// Save the latest camera snapshot in the browser.
function storeSnapshot(dataUrl) {
    localStorage.setItem(snapshotStorageKey, dataUrl);
}

// Stop every video track in the current camera stream.
function stopStream(stream) {
    stream?.getTracks().forEach((track) => track.stop());
}

// Start the camera feature used on the movies page.
export function initCameraTools() {
    const wrapper = document.querySelector('[data-camera-tool]');

    if (!wrapper) {
        return;
    }

    const startButton = wrapper.querySelector('[data-camera-start]');
    const captureButton = wrapper.querySelector('[data-camera-capture]');
    const resetButton = wrapper.querySelector('[data-camera-reset]');
    const status = wrapper.querySelector('[data-camera-status]');
    const emptyState = wrapper.querySelector('[data-camera-empty]');
    const preview = wrapper.querySelector('[data-camera-preview]');
    const snapshot = wrapper.querySelector('[data-camera-shot]');
    let stream = null;

    const showSnapshot = (dataUrl) => {
        snapshot.src = dataUrl;
        snapshot.hidden = false;
        preview.hidden = true;
        emptyState.hidden = true;
        captureButton.hidden = true;
        resetButton.hidden = false;
        startButton.textContent = 'Start camera';
    };

    const showPreview = () => {
        preview.hidden = false;
        snapshot.hidden = true;
        emptyState.hidden = true;
        captureButton.hidden = false;
        resetButton.hidden = true;
        startButton.textContent = 'Restart camera';
    };

    const clearToEmpty = () => {
        preview.hidden = true;
        snapshot.hidden = true;
        emptyState.hidden = false;
        captureButton.hidden = true;
        resetButton.hidden = true;
        preview.srcObject = null;
        status.textContent = 'Camera access stays in the browser and does not use tracking.';
    };

    const startCamera = async () => {
        if (!navigator.mediaDevices?.getUserMedia) {
            status.textContent = 'Camera access is not available in this browser.';
            return;
        }

        stopStream(stream);
        status.textContent = 'Starting camera...';

        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'user',
                },
                audio: false,
            });

            preview.srcObject = stream;
            showPreview();
            status.textContent = 'Camera ready. Take a quick movie night photo.';
        } catch {
            clearToEmpty();
            status.textContent = 'Camera access was denied or unavailable.';
        }
    };

    startButton.addEventListener('click', startCamera);

    captureButton.addEventListener('click', () => {
        if (!stream) {
            return;
        }

        const canvas = document.createElement('canvas');
        canvas.width = preview.videoWidth || 960;
        canvas.height = preview.videoHeight || 540;
        canvas.getContext('2d')?.drawImage(preview, 0, 0, canvas.width, canvas.height);
        const dataUrl = canvas.toDataURL('image/jpeg', 0.85);

        storeSnapshot(dataUrl);
        stopStream(stream);
        stream = null;
        preview.srcObject = null;
        showSnapshot(dataUrl);
        status.textContent = 'Snapshot saved in this browser.';
    });

    resetButton.addEventListener('click', startCamera);

    const savedImage = savedSnapshot();
    if (savedImage) {
        showSnapshot(savedImage);
        status.textContent = 'Showing your last saved movie night snapshot.';
    }

    window.addEventListener('beforeunload', () => {
        stopStream(stream);
    });
}
