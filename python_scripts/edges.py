import cv2
import numpy as np


def _gray(image):
	return cv2.cvtColor(image, cv2.COLOR_BGR2GRAY) if image.ndim == 3 else image


def canny_edges(image, threshold_value):
	gray = _gray(image)
	low = max(1, int(threshold_value))
	high = min(255, low * 2)
	return cv2.Canny(gray, low, high)


def sobel_edges(image):
	gray = _gray(image)
	grad_x = cv2.Sobel(gray, cv2.CV_16S, 1, 0)
	grad_y = cv2.Sobel(gray, cv2.CV_16S, 0, 1)
	abs_x = cv2.convertScaleAbs(grad_x)
	abs_y = cv2.convertScaleAbs(grad_y)
	return cv2.cvtColor(cv2.addWeighted(abs_x, 0.5, abs_y, 0.5, 0), cv2.COLOR_GRAY2BGR)


def laplacian_edges(image):
	gray = _gray(image)
	laplacian = cv2.Laplacian(gray, cv2.CV_16S)
	return cv2.convertScaleAbs(laplacian)
