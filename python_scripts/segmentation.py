import cv2
import numpy as np


def kmeans_segmentation(image, cluster_count):
	k = max(2, int(cluster_count))
	data = image.reshape((-1, 3)).astype(np.float32)
	criteria = (cv2.TERM_CRITERIA_EPS + cv2.TERM_CRITERIA_MAX_ITER, 10, 1.0)
	_, labels, centers = cv2.kmeans(data, k, None, criteria, 10, cv2.KMEANS_RANDOM_CENTERS)
	centers = np.uint8(centers)
	segmented = centers[labels.flatten()]
	return segmented.reshape(image.shape)


def watershed_segmentation(image):
	original = image.copy()
	gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
	_, thresh = cv2.threshold(gray, 0, 255, cv2.THRESH_BINARY_INV + cv2.THRESH_OTSU)
	kernel = np.ones((3, 3), np.uint8)
	opening = cv2.morphologyEx(thresh, cv2.MORPH_OPEN, kernel, iterations=2)
	sure_bg = cv2.dilate(opening, kernel, iterations=3)
	dist_transform = cv2.distanceTransform(opening, cv2.DIST_L2, 5)
	_, sure_fg = cv2.threshold(dist_transform, 0.4 * dist_transform.max(), 255, 0)
	sure_fg = np.uint8(sure_fg)
	unknown = cv2.subtract(sure_bg, sure_fg)
	_, markers = cv2.connectedComponents(sure_fg)
	markers = markers + 1
	markers[unknown == 255] = 0
	markers = cv2.watershed(original, markers)
	original[markers == -1] = [0, 0, 255]
	return original
