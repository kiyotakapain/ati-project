import cv2
import numpy as np


def adjust_brightness(image, value):
	return cv2.convertScaleAbs(image, alpha=1.0, beta=float(value))


def adjust_contrast(image, value):
	alpha = max(0.1, 1.0 + float(value) / 100.0)
	return cv2.convertScaleAbs(image, alpha=alpha, beta=0)


def apply_gamma(image, gamma_value):
	gamma = max(float(gamma_value), 0.1)
	inverse = 1.0 / gamma
	table = np.array([(i / 255.0) ** inverse * 255 for i in range(256)]).astype('uint8')
	return cv2.LUT(image, table)


def apply_clahe(image):
	lab = cv2.cvtColor(image, cv2.COLOR_BGR2LAB)
	l_channel, a_channel, b_channel = cv2.split(lab)
	clahe = cv2.createCLAHE(clipLimit=2.0, tileGridSize=(8, 8))
	cl = clahe.apply(l_channel)
	merged = cv2.merge((cl, a_channel, b_channel))
	return cv2.cvtColor(merged, cv2.COLOR_LAB2BGR)


def resize_image(image, percent):
	scale = max(float(percent), 1.0) / 100.0
	new_width = max(1, int(image.shape[1] * scale))
	new_height = max(1, int(image.shape[0] * scale))
	return cv2.resize(image, (new_width, new_height), interpolation=cv2.INTER_AREA)


def compress_image(image, quality):
	quality = int(np.clip(quality, 5, 100))
	encode_param = [int(cv2.IMWRITE_JPEG_QUALITY), quality]
	success, encoded = cv2.imencode('.jpg', image, encode_param)
	if not success:
		return image
	decoded = cv2.imdecode(encoded, cv2.IMREAD_COLOR)
	return decoded if decoded is not None else image
