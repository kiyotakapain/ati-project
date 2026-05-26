import cv2
import numpy as np


def gaussian_noise(image, sigma):
	noise = np.random.normal(0, max(float(sigma), 0.1), image.shape).astype(np.float32)
	result = image.astype(np.float32) + noise
	return np.clip(result, 0, 255).astype(np.uint8)


def salt_pepper_noise(image, amount):
	result = image.copy()
	total = result.shape[0] * result.shape[1]
	count = int(total * max(float(amount), 0) / 100.0)
	ys = np.random.randint(0, result.shape[0], count)
	xs = np.random.randint(0, result.shape[1], count)

	half = count // 2
	result[ys[:half], xs[:half]] = 255
	result[ys[half:], xs[half:]] = 0
	return result


def speckle_noise(image, sigma):
	noise = np.random.normal(0, max(float(sigma), 0.1) / 100.0, image.shape).astype(np.float32)
	result = image.astype(np.float32) + image.astype(np.float32) * noise
	return np.clip(result, 0, 255).astype(np.uint8)
