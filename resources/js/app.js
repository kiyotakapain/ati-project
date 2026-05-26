const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

const operationSettings = {
    grayscale: { label: 'Grayscale', defaultValue: 0, requiresValue: false },
    invert: { label: 'Invert', defaultValue: 0, requiresValue: false },
    sepia: { label: 'Sepia', defaultValue: 0, requiresValue: false },
    stretch: { label: 'Stretch', defaultValue: 0, requiresValue: false },
    equalize: { label: 'Equalize', defaultValue: 0, requiresValue: false },
    histogram: { label: 'Histogram', defaultValue: 0, requiresValue: false },
    analysis: { label: 'Analysis', defaultValue: 0, requiresValue: false },
    threshold: { label: 'Binary Threshold', defaultValue: 127, requiresValue: true, min: 0, max: 255, step: 1, help: 'Set the cutoff between black and white.' },
    otsu: { label: 'Otsu', defaultValue: 0, requiresValue: false },
    adaptive: { label: 'Adaptive', defaultValue: 0, requiresValue: false },
    blur: { label: 'Gaussian Blur', defaultValue: 5, requiresValue: true, min: 1, max: 31, step: 2, help: 'Kernel size should be odd.' },
    median: { label: 'Median Filter', defaultValue: 5, requiresValue: true, min: 1, max: 31, step: 2, help: 'Kernel size should be odd.' },
    sharpen: { label: 'Sharpen', defaultValue: 0, requiresValue: false },
    bilateral: { label: 'Bilateral', defaultValue: 9, requiresValue: true, min: 1, max: 31, step: 1, help: 'Diameter controls the filter footprint.' },
    gaussian_noise: { label: 'Gaussian Noise', defaultValue: 20, requiresValue: true, min: 1, max: 100, step: 1, help: 'Value is the standard deviation.' },
    salt_pepper_noise: { label: 'Salt & Pepper Noise', defaultValue: 5, requiresValue: true, min: 1, max: 30, step: 1, help: 'Value is the percentage of corrupted pixels.' },
    speckle_noise: { label: 'Speckle Noise', defaultValue: 10, requiresValue: true, min: 1, max: 50, step: 1, help: 'Value is the noise strength.' },
    canny: { label: 'Canny', defaultValue: 100, requiresValue: true, min: 1, max: 255, step: 1, help: 'Controls the low threshold.' },
    sobel: { label: 'Sobel', defaultValue: 0, requiresValue: false },
    laplacian: { label: 'Laplacian', defaultValue: 0, requiresValue: false },
    erode: { label: 'Erode', defaultValue: 3, requiresValue: true, min: 1, max: 31, step: 2, help: 'Kernel size should be odd.' },
    dilate: { label: 'Dilate', defaultValue: 3, requiresValue: true, min: 1, max: 31, step: 2, help: 'Kernel size should be odd.' },
    open: { label: 'Open', defaultValue: 3, requiresValue: true, min: 1, max: 31, step: 2, help: 'Opening removes small bright spots.' },
    close: { label: 'Close', defaultValue: 3, requiresValue: true, min: 1, max: 31, step: 2, help: 'Closing fills small dark gaps.' },
    brightness: { label: 'Brightness', defaultValue: 25, requiresValue: true, min: -100, max: 100, step: 1, help: 'Negative values darken the image.' },
    contrast: { label: 'Contrast', defaultValue: 20, requiresValue: true, min: -100, max: 100, step: 1, help: 'Negative values flatten the contrast.' },
    gamma: { label: 'Gamma', defaultValue: 1.2, requiresValue: true, min: 0.1, max: 5, step: 0.1, help: 'Gamma values above 1 brighten shadows less aggressively.' },
    clahe: { label: 'CLAHE', defaultValue: 0, requiresValue: false },
    resize: { label: 'Resize', defaultValue: 100, requiresValue: true, min: 20, max: 200, step: 10, help: 'Value is a percentage scale.' },
    compress: { label: 'Compress', defaultValue: 85, requiresValue: true, min: 5, max: 100, step: 1, help: 'Value is JPEG quality.' },
    kmeans: { label: 'K-Means', defaultValue: 4, requiresValue: true, min: 2, max: 10, step: 1, help: 'Value is the number of color clusters.' },
    watershed: { label: 'Watershed', defaultValue: 0, requiresValue: false },
};

document.addEventListener('DOMContentLoaded', () => {
    const themeToggle = document.querySelector('[data-theme-toggle]');
    const themeIcon = document.querySelector('[data-theme-icon]');

    const applyTheme = (theme) => {
        const isLight = theme === 'light';
        document.documentElement.dataset.theme = isLight ? 'light' : 'dark';
        document.documentElement.style.colorScheme = isLight ? 'light' : 'dark';

        if (themeToggle) {
            themeToggle.setAttribute('aria-pressed', String(isLight));
        }

        if (themeIcon) {
            themeIcon.textContent = isLight ? '☀' : '☾';
        }

        try {
            localStorage.setItem('theme', theme);
        } catch {
            // Ignore storage errors.
        }
    };

    const storedTheme = (() => {
        try {
            return localStorage.getItem('theme');
        } catch {
            return null;
        }
    })();

    const prefersDark = window.matchMedia?.('(prefers-color-scheme: dark)')?.matches ?? true;
    applyTheme(storedTheme ?? (prefersDark ? 'dark' : 'light'));

    themeToggle?.addEventListener('click', () => {
        const nextTheme = document.documentElement.dataset.theme === 'light' ? 'dark' : 'light';
        applyTheme(nextTheme);
    });

    const uploadForm = document.querySelector('[data-upload-form]');
    const imageInput = document.querySelector('[data-image-input]');
    const fileLabel = document.querySelector('[data-file-label]');
    const processButton = document.querySelector('[data-process-button]');
    const resetButton = document.querySelector('[data-reset-button]');
    const operationButtons = document.querySelectorAll('[data-operation]');
    const operationTitle = document.querySelector('[data-operation-title]');
    const operationHelp = document.querySelector('[data-operation-help]');
    const operationKey = document.querySelector('[data-operation-key]');
    const valueInput = document.querySelector('[data-value-input]');
    const valueLabel = document.querySelector('[data-value-label]');
    const valueHelp = document.querySelector('[data-value-help]');
    const originalPreview = document.querySelector('[data-original-preview]');
    const processedPreview = document.querySelector('[data-processed-preview]');
    const originalPlaceholder = document.querySelector('[data-original-placeholder]');
    const processedPlaceholder = document.querySelector('[data-processed-placeholder]');
    const downloadButton = document.querySelector('[data-download-button]');
    const statusBox = document.querySelector('[data-status]');
    const originalStatus = document.querySelector('[data-original-status]');
    const processedStatus = document.querySelector('[data-processed-status]');

    if (!uploadForm || !imageInput || !fileLabel || !processButton || !resetButton || !operationButtons.length || !operationTitle || !operationHelp || !operationKey || !valueInput || !valueLabel || !valueHelp || !originalPreview || !processedPreview || !originalPlaceholder || !processedPlaceholder || !downloadButton || !statusBox || !originalStatus || !processedStatus) {
        return;
    }

    let uploadedName = '';
    let currentOperation = 'grayscale';

    const setStatus = (message) => {
        if (statusBox) {
            statusBox.textContent = message;
        }
    };

    const setPreview = (image, placeholder, source) => {
        if (!image || !placeholder) {
            return;
        }

        if (source) {
            image.src = source;
            image.classList.remove('hidden');
            placeholder.classList.add('hidden');
        } else {
            image.removeAttribute('src');
            image.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
    };

    const updateOperation = (operationName, button = null) => {
        currentOperation = operationName;

        const settings = operationSettings[operationName] ?? operationSettings.grayscale;

        operationTitle.textContent = settings.label;
        operationKey.textContent = operationName;
        operationHelp.textContent = settings.help ?? 'No additional description available.';

        if (settings.requiresValue) {
            valueInput.parentElement.classList.remove('hidden');
            valueLabel.textContent = `${settings.label} value`;
            valueInput.value = settings.defaultValue;
            valueInput.min = settings.min ?? valueInput.min;
            valueInput.max = settings.max ?? valueInput.max;
            valueInput.step = settings.step ?? valueInput.step;
            valueInput.placeholder = String(settings.defaultValue);
            valueHelp.textContent = settings.help ?? 'Adjust the numeric parameter before processing.';
        } else {
            valueInput.parentElement.classList.add('hidden');
            valueInput.value = settings.defaultValue ?? 0;
            valueHelp.textContent = 'No numeric parameter is needed for this tool.';
        }

        operationButtons.forEach((item) => {
            item.classList.remove('border-cyan-300/50', 'bg-slate-950/80', 'ring-2', 'ring-cyan-400/30');
        });

        if (button) {
            button.classList.add('border-cyan-300/50', 'bg-slate-950/80', 'ring-2', 'ring-cyan-400/30');
        }
    };

    updateOperation(currentOperation, document.querySelector('[data-operation="grayscale"]'));

    operationButtons.forEach((button) => {
        button.addEventListener('click', () => {
            updateOperation(button.dataset.operation ?? 'grayscale', button);
            setStatus(`Selected ${button.dataset.label ?? button.dataset.operation}. Upload an image or run the operation if one is already loaded.`);
        });
    });

    imageInput.addEventListener('change', () => {
        const file = imageInput.files?.[0];

        if (file) {
            fileLabel.textContent = file.name;
        } else {
            fileLabel.textContent = 'Choose an image';
        }
    });

    uploadForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        const file = imageInput.files?.[0];

        if (!file) {
            setStatus('Please choose an image file first.');
            return;
        }

        const formData = new FormData();
        formData.append('image', file);

        processButton.disabled = true;
        setStatus('Uploading image...');

        try {
            const response = await fetch('/upload', {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: formData,
            });

            if (!response.ok) {
                const payload = await response.json().catch(() => ({}));
                throw new Error(payload.message ?? 'Upload failed.');
            }

            const payload = await response.json();
            uploadedName = payload.name;
            setPreview(originalPreview, originalPlaceholder, payload.url);
            processButton.disabled = false;
            downloadButton.classList.add('hidden');
            processedPreview?.removeAttribute('src');
            processedPreview?.classList.add('hidden');
            processedPlaceholder?.classList.remove('hidden');
            originalStatus.textContent = 'Loaded';
            processedStatus.textContent = 'Waiting for processing';
            setStatus('Image uploaded successfully. Choose an operation and run processing.');
        } catch (error) {
            setStatus(error.message ?? 'Upload failed.');
        }
    });

    processButton.addEventListener('click', async () => {
        if (!uploadedName) {
            setStatus('Upload an image before running any processing tool.');
            return;
        }

        const payload = new FormData();
        payload.append('image', uploadedName);
        payload.append('operation', currentOperation);
        payload.append('value', valueInput.value || '0');

        processButton.disabled = true;
        setStatus(`Running ${currentOperation}...`);
        processedStatus.textContent = 'Processing...';

        try {
            const response = await fetch('/process', {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: payload,
            });

            if (!response.ok) {
                const errorPayload = await response.json().catch(() => ({}));
                throw new Error(errorPayload.message ?? 'Processing failed.');
            }

            const result = await response.json();
            setPreview(processedPreview, processedPlaceholder, `${result.processed}?t=${Date.now()}`);
            processedStatus.textContent = 'Done';
            downloadButton.href = result.download_url;
            downloadButton.classList.remove('hidden');
            setStatus(result.message || `${currentOperation} completed successfully.`);
        } catch (error) {
            setStatus(error.message ?? 'Processing failed.');
            processedStatus.textContent = 'Error';
        } finally {
            processButton.disabled = false;
        }
    });

    resetButton.addEventListener('click', () => {
        uploadedName = '';
        imageInput.value = '';
        fileLabel.textContent = 'Choose an image';
        processButton.disabled = true;
        downloadButton.classList.add('hidden');
        setPreview(originalPreview, originalPlaceholder, '');
        setPreview(processedPreview, processedPlaceholder, '');
        originalStatus.textContent = 'Waiting for upload';
        processedStatus.textContent = 'Waiting for processing';
        setStatus('Previews reset. Upload another image to continue.');
    });
});
