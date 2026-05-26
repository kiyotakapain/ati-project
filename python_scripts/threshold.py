import cv2


def _gray(image):
	return cv2.cvtColor(image, cv2.COLOR_BGR2GRAY) if image.ndim == 3 else image


def manual_threshold(image, threshold_value):
	gray = _gray(image)
	_, result = cv2.threshold(gray, int(threshold_value), 255, cv2.THRESH_BINARY)
	return result


def otsu_threshold(image):
	gray = _gray(image)
	_, result = cv2.threshold(gray, 0, 255, cv2.THRESH_BINARY + cv2.THRESH_OTSU)
	return result


def adaptive_threshold(image):
	gray = _gray(image)
	return cv2.adaptiveThreshold(
		gray,
		255,
		cv2.ADAPTIVE_THRESH_GAUSSIAN_C,
		cv2.THRESH_BINARY,
		11,
		2,
	)
