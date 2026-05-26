import sys

import cv2
import numpy as np

from analysis import analysis_panel
from edges import canny_edges, laplacian_edges, sobel_edges
from enhancement import (
    adjust_brightness,
    adjust_contrast,
    apply_clahe,
    apply_gamma,
    compress_image,
    resize_image,
)
from filters import bilateral_filter, gaussian_blur, median_filter, sharpen_image
from histogram import equalize_histogram, histogram_image, stretch_contrast
from morphology import close_image, dilate_image, erode_image, open_image
from noise import gaussian_noise, salt_pepper_noise, speckle_noise
from segmentation import kmeans_segmentation, watershed_segmentation
from threshold import adaptive_threshold, manual_threshold, otsu_threshold


def _as_uint8(image):
    if image.dtype == np.uint8:
        return image
    return np.clip(image, 0, 255).astype(np.uint8)


def _run_operation(image, operation, value):
    if operation in {'grayscale'}:
        return cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
    if operation in {'invert', 'negative'}:
        return cv2.bitwise_not(image)
    if operation == 'sepia':
        kernel = np.array([
            [0.272, 0.534, 0.131],
            [0.349, 0.686, 0.168],
            [0.393, 0.769, 0.189],
        ])
        result = cv2.transform(image, kernel)
        return _as_uint8(result)
    if operation == 'stretch':
        return stretch_contrast(image)
    if operation == 'equalize':
        return equalize_histogram(image)
    if operation == 'histogram':
        return histogram_image(image)
    if operation == 'analysis':
        return analysis_panel(image)
    if operation == 'threshold':
        return manual_threshold(image, int(value))
    if operation == 'otsu':
        return otsu_threshold(image)
    if operation == 'adaptive':
        return adaptive_threshold(image)
    if operation == 'blur':
        return gaussian_blur(image, int(value))
    if operation == 'median':
        return median_filter(image, int(value))
    if operation == 'sharpen':
        return sharpen_image(image)
    if operation == 'bilateral':
        return bilateral_filter(image, int(value))
    if operation == 'gaussian_noise':
        return gaussian_noise(image, float(value))
    if operation == 'salt_pepper_noise':
        return salt_pepper_noise(image, float(value))
    if operation == 'speckle_noise':
        return speckle_noise(image, float(value))
    if operation == 'canny':
        return canny_edges(image, float(value))
    if operation == 'sobel':
        return sobel_edges(image)
    if operation == 'laplacian':
        return laplacian_edges(image)
    if operation == 'erode':
        return erode_image(image, int(value))
    if operation == 'dilate':
        return dilate_image(image, int(value))
    if operation == 'open':
        return open_image(image, int(value))
    if operation == 'close':
        return close_image(image, int(value))
    if operation == 'brightness':
        return adjust_brightness(image, float(value))
    if operation == 'contrast':
        return adjust_contrast(image, float(value))
    if operation == 'gamma':
        return apply_gamma(image, float(value))
    if operation == 'clahe':
        return apply_clahe(image)
    if operation == 'resize':
        return resize_image(image, float(value))
    if operation == 'compress':
        return compress_image(image, int(value))
    if operation == 'kmeans':
        return kmeans_segmentation(image, int(value))
    if operation == 'watershed':
        return watershed_segmentation(image)
    raise ValueError(f'Unsupported operation: {operation}')


def main():
    if len(sys.argv) < 4:
        raise SystemExit('Usage: processor.py <input> <output> <operation> [value]')

    input_path = sys.argv[1]
    output_path = sys.argv[2]
    operation = sys.argv[3].strip().lower()
    value = float(sys.argv[4]) if len(sys.argv) > 4 else 0

    image = cv2.imread(input_path)
    if image is None:
        raise SystemExit(f'Unable to read image: {input_path}')

    result = _run_operation(image, operation, value)
    result = _as_uint8(result)

    cv2.imwrite(output_path, result)


if __name__ == '__main__':
    main()
