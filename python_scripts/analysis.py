import cv2
import numpy as np

from histogram import histogram_image


def analysis_panel(image):
	canvas = np.zeros((600, 1000, 3), dtype=np.uint8)
	canvas[:] = (15, 23, 42)

	stats = {
		'Width': image.shape[1],
		'Height': image.shape[0],
		'Channels': 1 if image.ndim == 2 else image.shape[2],
		'Mean': round(float(np.mean(image)), 2),
		'Std Dev': round(float(np.std(image)), 2),
		'Min': int(np.min(image)),
		'Max': int(np.max(image)),
	}

	cv2.putText(canvas, 'Image Analysis', (30, 52), cv2.FONT_HERSHEY_SIMPLEX, 1.2, (255, 255, 255), 2, cv2.LINE_AA)
	cv2.putText(canvas, 'Summary statistics and histogram preview', (30, 90), cv2.FONT_HERSHEY_SIMPLEX, 0.7, (148, 163, 184), 2, cv2.LINE_AA)

	y = 150
	for label, value in stats.items():
		cv2.putText(canvas, f'{label}: {value}', (40, y), cv2.FONT_HERSHEY_SIMPLEX, 0.85, (226, 232, 240), 2, cv2.LINE_AA)
		y += 48

	histogram = histogram_image(image, width=520, height=300)
	canvas[260:560, 430:950] = histogram

	return canvas
