import cv2
import numpy as np


def _kernel(size):
	kernel_size = max(1, int(size))
	if kernel_size % 2 == 0:
		kernel_size += 1
	return np.ones((kernel_size, kernel_size), np.uint8)


def erode_image(image, size):
	return cv2.erode(image, _kernel(size), iterations=1)


def dilate_image(image, size):
	return cv2.dilate(image, _kernel(size), iterations=1)


def open_image(image, size):
	return cv2.morphologyEx(image, cv2.MORPH_OPEN, _kernel(size))


def close_image(image, size):
	return cv2.morphologyEx(image, cv2.MORPH_CLOSE, _kernel(size))
