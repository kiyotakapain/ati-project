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
    const addOperationButton = document.querySelector('[data-add-operation-button]');
    const clearQueueButton = document.querySelector('[data-clear-queue-button]');
    const queueList = document.querySelector('[data-operation-queue]');
    const multiWorkflow = document.querySelector('[data-multi-workflow]') !== null;

    // Don't abort entirely if some UI parts are missing. Initialize upload handlers
    // when the upload form is present, and initialize operation-specific UI only
    // when those elements exist.

    let uploadedName = '';
    let sourceImageName = '';
    let pendingOperations = [];
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

    const getCurrentSourceName = () => sourceImageName || uploadedName;

    const renderQueue = () => {
        if (!queueList) {
            return;
        }

        queueList.innerHTML = '';

        if (!pendingOperations.length) {
            const emptyItem = document.createElement('li');
            emptyItem.className = 'rounded-2xl border border-dashed border-white/10 bg-slate-950/30 px-4 py-3 text-slate-500';
            emptyItem.textContent = 'No operations queued yet.';
            queueList.appendChild(emptyItem);
            return;
        }

        pendingOperations.forEach((step, index) => {
            const item = document.createElement('li');
            item.className = 'flex items-start justify-between gap-4 rounded-2xl border border-white/10 bg-slate-950/50 px-4 py-3';

            const labelWrap = document.createElement('div');
            labelWrap.className = 'min-w-0';

            const title = document.createElement('p');
            title.className = 'text-sm font-semibold text-white';
            title.textContent = `${index + 1}. ${step.label}`;

            const meta = document.createElement('p');
            meta.className = 'mt-1 text-xs text-slate-400';
            meta.textContent = step.requiresValue ? `Value: ${step.value}` : 'No parameter required';

            labelWrap.appendChild(title);
            labelWrap.appendChild(meta);

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-semibold text-white transition hover:bg-white/10';
            removeButton.textContent = 'Remove';
            removeButton.addEventListener('click', () => {
                pendingOperations.splice(index, 1);
                renderQueue();
                setStatus('Removed one step from the queue.');
            });

            item.appendChild(labelWrap);
            item.appendChild(removeButton);
            queueList.appendChild(item);
        });
    };

    const queueOperation = () => {
        const settings = operationSettings[currentOperation] ?? operationSettings.histogram_show;
        pendingOperations.push({
            operation: currentOperation,
            label: settings.label,
            value: valueInput?.value ?? settings.defaultValue,
            requiresValue: settings.requiresValue,
        });
        renderQueue();
        setStatus(`${settings.label} added to the queue.`);
    };

    const runProcessStep = async (sourceName, operation, value) => {
        const payload = new FormData();
        payload.append('image', sourceName);
        payload.append('operation', operation);
        payload.append('value', value ?? '0');

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

        return response.json();
    };

    const applyQueuedOperations = async () => {
        if (!pendingOperations.length) {
            setStatus('Add at least one operation before applying the queue.');
            return;
        }

        const startingSource = getCurrentSourceName();
        if (!startingSource) {
            setStatus('Upload an image before applying any operation chain.');
            return;
        }

        processButton.disabled = true;
        if (addOperationButton) {
            addOperationButton.disabled = true;
        }
        if (clearQueueButton) {
            clearQueueButton.disabled = true;
        }

        let currentSource = startingSource;

        try {
            for (const [index, step] of pendingOperations.entries()) {
                setStatus(`Applying ${step.label} (${index + 1}/${pendingOperations.length})...`);
                if (processedStatus) {
                    processedStatus.textContent = `Applying step ${index + 1}/${pendingOperations.length}`;
                }

                const result = await runProcessStep(currentSource, step.operation, step.value);
                currentSource = result.processed_name ?? currentSource;
                sourceImageName = currentSource;

                if (originalPreview && originalPlaceholder) {
                    setPreview(originalPreview, originalPlaceholder, result.processed);
                }
                if (originalStatus) {
                    originalStatus.textContent = 'Ready for next step';
                }
                if (processedPreview && processedPlaceholder) {
                    setPreview(processedPreview, processedPlaceholder, result.processed);
                }
                if (processedStatus) {
                    processedStatus.textContent = 'Done';
                }
                if (downloadButton) {
                    downloadButton.href = result.download_url;
                    downloadButton.classList.remove('hidden');
                }

                setStatus(result.message || `${step.label} completed successfully.`);
            }

            pendingOperations = [];
            renderQueue();
            setStatus('All queued operations were applied successfully. You can add more steps or download the latest result.');
        } catch (error) {
            setStatus(error.message ?? 'Processing failed.');
            if (processedStatus) {
                processedStatus.textContent = 'Error';
            }
        } finally {
            processButton.disabled = false;
            if (addOperationButton) {
                addOperationButton.disabled = false;
            }
            if (clearQueueButton) {
                clearQueueButton.disabled = false;
            }
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
        updateOperation(currentOperation, document.querySelector(`[data-operation="${currentOperation}"]`));

        operationButtons.forEach((button) => {
            button.addEventListener('click', () => {
                updateOperation(button.dataset.operation ?? 'histogram_show', button);
                setStatus(`Selected ${button.dataset.label ?? button.dataset.operation}. Upload an image or run the operation if one is already loaded.`);
            });
        });
    }

    if (multiWorkflow) {
        renderQueue();

        addOperationButton?.addEventListener('click', () => {
            queueOperation();
        });

        clearQueueButton?.addEventListener('click', () => {
            pendingOperations = [];
            renderQueue();
            setStatus('Queue cleared.');
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
                sourceImageName = payload.name;
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
        if (multiWorkflow) {
            await applyQueuedOperations();
            return;
        }

        const sourceName = getCurrentSourceName();

        if (!sourceName) {
            setStatus('Upload an image before running any processing tool.');
            return;
        }

        processButton.disabled = true;
        setStatus(`Running ${currentOperation}...`);
        if (processedStatus) {
            processedStatus.textContent = 'Processing...';
        }

        try {
            const result = await runProcessStep(sourceName, currentOperation, valueInput?.value ?? '0');
            setPreview(processedPreview, processedPlaceholder, `${result.processed}?t=${Date.now()}`);
            if (processedStatus) {
                processedStatus.textContent = 'Done';
            }
            if (downloadButton) {
                downloadButton.href = result.download_url;
                downloadButton.classList.remove('hidden');
            }
            setStatus(result.message || `${currentOperation} completed successfully.`);
        } catch (error) {
            setStatus(error.message ?? 'Processing failed.');
            if (processedStatus) {
                processedStatus.textContent = 'Error';
            }
        } finally {
            processButton.disabled = false;
        }
    });

    resetButton.addEventListener('click', () => {
        uploadedName = '';
        sourceImageName = '';
        pendingOperations = [];
        imageInput.value = '';
        fileLabel.textContent = 'Choose an image';
        processButton.disabled = true;
        if (downloadButton) {
            downloadButton.classList.add('hidden');
            downloadButton.removeAttribute('href');
        }
        renderQueue();
        setPreview(originalPreview, originalPlaceholder, '');
        setPreview(processedPreview, processedPlaceholder, '');
        if (originalStatus) {
            originalStatus.textContent = 'Waiting for upload';
        }
        if (processedStatus) {
            processedStatus.textContent = 'Waiting for processing';
        }
        setStatus('Previews reset. Upload another image to continue.');
    });
});
