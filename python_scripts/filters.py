import cv2


def _odd_kernel(value):
	kernel = max(1, int(value))
	return kernel if kernel % 2 == 1 else kernel + 1


def gaussian_blur(image, kernel_size):
	kernel = _odd_kernel(kernel_size)
	return cv2.GaussianBlur(image, (kernel, kernel), 0)


def median_filter(image, kernel_size):
	kernel = _odd_kernel(kernel_size)
	return cv2.medianBlur(image, kernel)


def sharpen_image(image):
	kernel = cv2.getStructuringElement(cv2.MORPH_RECT, (3, 3))
	blurred = cv2.GaussianBlur(image, (0, 0), 1.2)
	return cv2.addWeighted(image, 1.6, blurred, -0.6, 0)


def bilateral_filter(image, diameter):
	d = max(1, int(diameter))
	return cv2.bilateralFilter(image, d, 75, 75)
