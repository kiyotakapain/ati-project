import cv2
import numpy as np


def _normalize_channel(channel):
	return cv2.normalize(channel, None, 0, 255, cv2.NORM_MINMAX)


def _equalize_gray(image):
	gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY) if image.ndim == 3 else image
	return cv2.equalizeHist(gray)


def stretch_contrast(image):
	if image.ndim == 2:
		return _normalize_channel(image)

	channels = cv2.split(image)
	stretched = [_normalize_channel(channel) for channel in channels]
	return cv2.merge(stretched)


def equalize_histogram(image):
	if image.ndim == 2:
		return _equalize_gray(image)

	ycrcb = cv2.cvtColor(image, cv2.COLOR_BGR2YCrCb)
	ycrcb[:, :, 0] = cv2.equalizeHist(ycrcb[:, :, 0])
	return cv2.cvtColor(ycrcb, cv2.COLOR_YCrCb2BGR)


def histogram_image(image, width=512, height=400):
	canvas = np.zeros((height, width, 3), dtype=np.uint8)
	bins = 256
	bin_width = width // bins

	if image.ndim == 2:
		hist = cv2.calcHist([image], [0], None, [bins], [0, 256])
		cv2.normalize(hist, hist, 0, height - 20, cv2.NORM_MINMAX)
		for x in range(1, bins):
			cv2.line(
				canvas,
				(bin_width * (x - 1), height - 1 - int(hist[x - 1][0])),
				(bin_width * x, height - 1 - int(hist[x][0])),
				(220, 220, 220),
				1,
			)
	else:
		colors = [(255, 80, 80), (80, 255, 80), (80, 80, 255)]
		for channel_index, color in enumerate(colors):
			hist = cv2.calcHist([image], [channel_index], None, [bins], [0, 256])
			cv2.normalize(hist, hist, 0, height - 20, cv2.NORM_MINMAX)
			for x in range(1, bins):
				cv2.line(
					canvas,
					(bin_width * (x - 1), height - 1 - int(hist[x - 1][0])),
					(bin_width * x, height - 1 - int(hist[x][0])),
					color,
					1,
				)

	cv2.rectangle(canvas, (0, 0), (width - 1, height - 1), (255, 255, 255), 1)
	cv2.putText(canvas, 'Histogram', (18, 34), cv2.FONT_HERSHEY_SIMPLEX, 1, (255, 255, 255), 2, cv2.LINE_AA)
	return canvas
