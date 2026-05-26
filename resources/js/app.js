const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

const operationSettings = {
    grayscale: { label: 'Grayscale', defaultValue: 0, requiresValue: false, help: 'Convert the image to grayscale.' },
    invert: { label: 'Invert', defaultValue: 0, requiresValue: false, help: 'Invert the image colors.' },
    blur: { label: 'Blur', defaultValue: 5, requiresValue: true, min: 1, max: 31, step: 1, help: 'Blur strength. Use an odd value.' },

    histogram_show: { label: 'Show histogram', defaultValue: 0, requiresValue: false },
    histogram_stretching: { label: 'Stretching', defaultValue: 0, requiresValue: false },
    histogram_equalization: { label: 'Equalization', defaultValue: 0, requiresValue: false },

    noise_gaussian: { label: 'Gaussian', defaultValue: 20, requiresValue: true, min: 1, max: 100, step: 1, help: 'Gaussian noise level.' },
    noise_salt_pepper: { label: 'Salt & Pepper', defaultValue: 5, requiresValue: true, min: 1, max: 30, step: 1, help: 'Salt & Pepper noise level.' },
    noise_speckle: { label: 'Speckle', defaultValue: 10, requiresValue: true, min: 1, max: 50, step: 1, help: 'Speckle noise level.' },
    noise_poisson: { label: 'Poisson', defaultValue: 15, requiresValue: true, min: 1, max: 100, step: 1, help: 'Poisson noise level.' },

    filter_mean: { label: 'Mean', defaultValue: 5, requiresValue: true, min: 1, max: 31, step: 2, help: 'Kernel size should be odd.' },
    filter_gaussian: { label: 'Gaussian', defaultValue: 5, requiresValue: true, min: 1, max: 31, step: 2, help: 'Kernel size should be odd.' },
    filter_median: { label: 'Median', defaultValue: 5, requiresValue: true, min: 1, max: 31, step: 2, help: 'Kernel size should be odd.' },
    filter_knn: { label: 'KNN', defaultValue: 9, requiresValue: true, min: 1, max: 31, step: 1, help: 'Neighborhood size.' },
    filter_sigma: { label: 'Sigma', defaultValue: 9, requiresValue: true, min: 1, max: 31, step: 1, help: 'Neighborhood size.' },
    filter_snn: { label: 'SNN', defaultValue: 5, requiresValue: true, min: 1, max: 31, step: 2, help: 'Kernel size should be odd.' },
    filter_minmax: { label: 'Min/Max', defaultValue: 5, requiresValue: true, min: 1, max: 31, step: 2, help: 'Kernel size should be odd.' },
    filter_nagao: { label: 'NAGAO', defaultValue: 9, requiresValue: true, min: 1, max: 31, step: 1, help: 'Neighborhood size.' },

    edge_roberts: { label: 'Roberts', defaultValue: 0, requiresValue: false },
    edge_prewitt: { label: 'Prewitt', defaultValue: 0, requiresValue: false },
    edge_sobel: { label: 'Sobel', defaultValue: 0, requiresValue: false },
    edge_laplacian: { label: 'Laplacian', defaultValue: 0, requiresValue: false },
    edge_log: { label: 'LoG', defaultValue: 0, requiresValue: false },
    edge_canny: { label: 'Canny', defaultValue: 100, requiresValue: true, min: 1, max: 255, step: 1, help: 'Canny low threshold.' },

    seg_simple_threshold: { label: 'Thresholding (Simple)', defaultValue: 127, requiresValue: true, min: 0, max: 255, step: 1, help: 'Threshold value.' },
    seg_double_threshold: { label: 'Thresholding (Double)', defaultValue: 127, requiresValue: true, min: 0, max: 255, step: 1, help: 'Threshold value.' },
    seg_otsu: { label: 'Thresholding (Otsu)', defaultValue: 0, requiresValue: false },
    seg_region_growing: { label: 'Region Growing', defaultValue: 4, requiresValue: true, min: 2, max: 10, step: 1, help: 'Cluster count proxy.' },
    seg_split_merge: { label: 'Split&Merge', defaultValue: 4, requiresValue: true, min: 2, max: 10, step: 1, help: 'Cluster count proxy.' },
    seg_kmeans: { label: 'K-means', defaultValue: 4, requiresValue: true, min: 2, max: 10, step: 1, help: 'Number of clusters.' },
    seg_fcm: { label: 'FCM', defaultValue: 4, requiresValue: true, min: 2, max: 10, step: 1, help: 'Number of clusters.' },

    morph_erosion: { label: 'Erosion', defaultValue: 3, requiresValue: true, min: 1, max: 31, step: 2, help: 'Kernel size should be odd.' },
    morph_dilation: { label: 'Dilation', defaultValue: 3, requiresValue: true, min: 1, max: 31, step: 2, help: 'Kernel size should be odd.' },
    morph_opening: { label: 'Opening', defaultValue: 3, requiresValue: true, min: 1, max: 31, step: 2, help: 'Kernel size should be odd.' },
    morph_closing: { label: 'Closing', defaultValue: 3, requiresValue: true, min: 1, max: 31, step: 2, help: 'Kernel size should be odd.' },

    eval_mse: { label: 'MSE', defaultValue: 0, requiresValue: false, help: 'Requires reference image (not yet implemented in backend).' },
    eval_psnr: { label: 'PSNR', defaultValue: 0, requiresValue: false, help: 'Requires reference image (not yet implemented in backend).' },
    eval_iou: { label: 'IoU', defaultValue: 0, requiresValue: false, help: 'Requires reference image (not yet implemented in backend).' },
    eval_dice: { label: 'Dice', defaultValue: 0, requiresValue: false, help: 'Requires reference image (not yet implemented in backend).' },
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

    // Don't abort entirely if some UI parts are missing. Initialize upload handlers
    // when the upload form is present, and initialize operation-specific UI only
    // when those elements exist.

    let uploadedName = '';
    let currentOperation = document.querySelector('[data-default-operation]')?.dataset.defaultOperation ?? 'histogram_show';

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

        const settings = operationSettings[operationName] ?? operationSettings.histogram_show;

        operationTitle.textContent = settings.label;
        operationKey.textContent = operationName;
        operationHelp.textContent = settings.help ?? 'No additional description available.';

        // Only touch parameter UI if it exists in the DOM.
        if (valueInput && valueLabel && valueHelp) {
            if (settings.requiresValue) {
                if (valueInput.parentElement) valueInput.parentElement.classList.remove('hidden');
                valueLabel.textContent = `${settings.label} value`;
                valueInput.value = settings.defaultValue;
                if (settings.min !== undefined) valueInput.min = settings.min;
                if (settings.max !== undefined) valueInput.max = settings.max;
                if (settings.step !== undefined) valueInput.step = settings.step;
                valueInput.placeholder = String(settings.defaultValue);
                valueHelp.textContent = settings.help ?? 'Adjust the numeric parameter before processing.';
            } else {
                if (valueInput.parentElement) valueInput.parentElement.classList.add('hidden');
                valueInput.value = settings.defaultValue ?? 0;
                valueHelp.textContent = 'No numeric parameter is needed for this tool.';
            }
        }

        operationButtons.forEach((item) => {
            item.classList.remove('border-cyan-300/50', 'bg-slate-950/80', 'ring-2', 'ring-cyan-400/30');
            item.setAttribute('aria-pressed', 'false');
        });

        if (button) {
            button.classList.add('border-cyan-300/50', 'bg-slate-950/80', 'ring-2', 'ring-cyan-400/30');
            button.setAttribute('aria-pressed', 'true');
        }
    };

    // Operation-specific initialization (only require operation UI elements)
    if (operationButtons && operationButtons.length && operationTitle && operationHelp && operationKey) {
        updateOperation(currentOperation, document.querySelector('[data-operation="histogram_show"]'));

        operationButtons.forEach((button) => {
            button.addEventListener('click', () => {
                updateOperation(button.dataset.operation ?? 'histogram_show', button);
                setStatus(`Selected ${button.dataset.label ?? button.dataset.operation}. Upload an image or run the operation if one is already loaded.`);
            });
        });
    }

    // Upload handlers (initialize when upload form exists)
    if (uploadForm && imageInput) {
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

            if (typeof processButton !== 'undefined' && processButton) {
                processButton.disabled = true;
            }
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
                if (originalPreview && originalPlaceholder) {
                    setPreview(originalPreview, originalPlaceholder, payload.url);
                }
                if (processButton) {
                    processButton.disabled = false;
                }
                if (downloadButton) {
                    downloadButton.classList.add('hidden');
                }
                if (processedPreview) {
                    processedPreview.removeAttribute('src');
                    processedPreview.classList.add('hidden');
                }
                if (processedPlaceholder) {
                    processedPlaceholder.classList.remove('hidden');
                }
                if (originalStatus) {
                    originalStatus.textContent = 'Loaded';
                }
                if (processedStatus) {
                    processedStatus.textContent = 'Waiting for processing';
                }
                setStatus('Image uploaded successfully. Choose an operation and run processing.');
            } catch (error) {
                setStatus(error.message ?? 'Upload failed.');
            }
        });
    }

    processButton.addEventListener('click', async () => {
        if (!uploadedName) {
            setStatus('Upload an image before running any processing tool.');
            return;
        }

        const payload = new FormData();
        payload.append('image', uploadedName);
        payload.append('operation', currentOperation);
        payload.append('value', (valueInput?.value) ?? '0');

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
            if (downloadButton) {
                downloadButton.href = result.download_url;
                downloadButton.classList.remove('hidden');
            }
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
        if (downloadButton) {
            downloadButton.classList.add('hidden');
            downloadButton.removeAttribute('href');
        }
        setPreview(originalPreview, originalPlaceholder, '');
        setPreview(processedPreview, processedPlaceholder, '');
        originalStatus.textContent = 'Waiting for upload';
        processedStatus.textContent = 'Waiting for processing';
        setStatus('Previews reset. Upload another image to continue.');
    });
});
