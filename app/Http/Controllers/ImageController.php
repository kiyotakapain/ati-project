<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ImageController extends Controller
{
    private const UPLOAD_DIR = 'uploads';

    private const PROCESSED_DIR = 'processed';

    private const PYTHON_SCRIPT = 'python_scripts/processor.py';

    private const OPERATIONS = [
        'grayscale', 'invert', 'sepia', 'stretch', 'equalize', 'histogram', 'analysis',
        'threshold', 'otsu', 'adaptive', 'blur', 'median', 'sharpen', 'bilateral',
        'gaussian_noise', 'salt_pepper_noise', 'speckle_noise', 'canny', 'sobel', 'laplacian',
        'erode', 'dilate', 'open', 'close', 'brightness', 'contrast', 'gamma', 'clahe',
        'resize', 'compress', 'kmeans', 'watershed',
    ];

    public function index()
    {
        return view('home', [
            'operationGroups' => $this->getOperationGroups(),
            'stats' => $this->getStats(),
        ]);
    }

    public function tools($slug = null)
    {
        $groups = $this->getOperationGroups();

        if ($slug) {
            $groups = array_values(array_filter($groups, fn($g) => ($g['slug'] ?? '') === $slug));
        }

        return view('home', [
            'operationGroups' => $groups,
            'stats' => $this->getStats(),
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'max:20480'],
        ]);

        $image = $request->file('image');
        $extension = strtolower($image->getClientOriginalExtension() ?: 'png');
        $name = Str::uuid()->toString() . '.' . $extension;

        File::ensureDirectoryExists(public_path(self::UPLOAD_DIR));
        File::ensureDirectoryExists(public_path(self::PROCESSED_DIR));

        $image->move(public_path(self::UPLOAD_DIR), $name);

        return response()->json([
            'name' => $name,
            'url' => asset(self::UPLOAD_DIR . '/' . $name),
        ]);
    }

    public function process(Request $request)
    {
        $data = $request->validate([
            'image' => ['required', 'string'],
            'operation' => ['required', Rule::in(self::OPERATIONS)],
            'value' => ['nullable', 'numeric'],
        ]);

        $image = $data['image'];
        $operation = $data['operation'];
        $value = $data['value'] ?? 0;

        $input = public_path(self::UPLOAD_DIR . '/' . $image);
        if (! File::exists($input)) {
            return response()->json([
                'message' => 'The selected image does not exist.',
            ], 404);
        }

        File::ensureDirectoryExists(public_path(self::PROCESSED_DIR));

        $outputName = pathinfo($image, PATHINFO_FILENAME) . '_' . $operation . '.png';
        $output = public_path(self::PROCESSED_DIR . '/' . $outputName);

        $python = env('PYTHON_BINARY', 'python');
        $script = base_path(self::PYTHON_SCRIPT);

        if (! File::exists($script)) {
            return response()->json([
                'message' => 'Python processor script was not found.',
            ], 500);
        }

        $result = Process::timeout(300)->run([
            $python,
            $script,
            $input,
            $output,
            $operation,
            (string) $value,
        ]);

        if ($result->failed() || ! File::exists($output)) {
            return response()->json([
                'message' => trim($result->errorOutput()) ?: 'Image processing failed.',
            ], 500);
        }

        return response()->json([
            'processed' => asset(self::PROCESSED_DIR . '/' . $outputName),
            'download_url' => route('download', ['name' => $outputName]),
            'message' => trim($result->output()),
        ]);
    }

    private function getStats()
    {
        return [
            ['label' => 'Load', 'value' => 'Upload from disk'],
            ['label' => 'Process', 'value' => 'More than 30 operations'],
            ['label' => 'Export', 'value' => 'Download or keep the result'],
        ];
    }

    private function getOperationGroups()
    {
        return [
            [
                'slug' => 'core',
                'title' => 'Core',
                'tone' => 'text-cyan-300',
                'items' => [
                    ['key' => 'grayscale', 'label' => 'Grayscale', 'default' => 0, 'help' => 'Convert the image to a monochrome view.'],
                    ['key' => 'invert', 'label' => 'Invert', 'default' => 0, 'help' => 'Flip the pixel values for a negative effect.'],
                    ['key' => 'sepia', 'label' => 'Sepia', 'default' => 0, 'help' => 'Apply a warm vintage color tone.'],
                ],
            ],
            [
                'slug' => 'histogram',
                'title' => 'Histogram',
                'tone' => 'text-amber-300',
                'items' => [
                    ['key' => 'stretch', 'label' => 'Stretch', 'default' => 0, 'help' => 'Expand the intensity range across the full dynamic span.'],
                    ['key' => 'equalize', 'label' => 'Equalize', 'default' => 0, 'help' => 'Redistribute tones to improve contrast.'],
                    ['key' => 'histogram', 'label' => 'Histogram', 'default' => 0, 'help' => 'Render a histogram analysis panel.'],
                    ['key' => 'analysis', 'label' => 'Analysis', 'default' => 0, 'help' => 'Summarize the image with stats and a histogram preview.'],
                ],
            ],
            [
                'slug' => 'thresholding',
                'title' => 'Thresholding',
                'tone' => 'text-rose-300',
                'items' => [
                    ['key' => 'threshold', 'label' => 'Binary Threshold', 'default' => 127, 'min' => 0, 'max' => 255, 'step' => 1, 'help' => 'Use a manual cutoff value between 0 and 255.'],
                    ['key' => 'otsu', 'label' => 'Otsu', 'default' => 0, 'help' => 'Automatic threshold selection using Otsu’s method.'],
                    ['key' => 'adaptive', 'label' => 'Adaptive', 'default' => 0, 'help' => 'Adaptive local thresholding for uneven illumination.'],
                ],
            ],
            [
                'slug' => 'filtering',
                'title' => 'Filtering',
                'tone' => 'text-emerald-300',
                'items' => [
                    ['key' => 'blur', 'label' => 'Gaussian Blur', 'default' => 5, 'min' => 1, 'max' => 31, 'step' => 2, 'help' => 'Smooth the image with a Gaussian kernel.'],
                    ['key' => 'median', 'label' => 'Median Filter', 'default' => 5, 'min' => 1, 'max' => 31, 'step' => 2, 'help' => 'Reduce impulse noise while keeping edges sharper.'],
                    ['key' => 'sharpen', 'label' => 'Sharpen', 'default' => 0, 'help' => 'Accentuate edges and local contrast.'],
                    ['key' => 'bilateral', 'label' => 'Bilateral', 'default' => 9, 'min' => 1, 'max' => 31, 'step' => 1, 'help' => 'Smooth while preserving strong edges.'],
                ],
            ],
            [
                'slug' => 'noise',
                'title' => 'Noise',
                'tone' => 'text-fuchsia-300',
                'items' => [
                    ['key' => 'gaussian_noise', 'label' => 'Gaussian Noise', 'default' => 20, 'min' => 1, 'max' => 100, 'step' => 1, 'help' => 'Add Gaussian sensor-like noise.'],
                    ['key' => 'salt_pepper_noise', 'label' => 'Salt & Pepper', 'default' => 5, 'min' => 1, 'max' => 30, 'step' => 1, 'help' => 'Corrupt random pixels with black and white impulses.'],
                    ['key' => 'speckle_noise', 'label' => 'Speckle', 'default' => 10, 'min' => 1, 'max' => 50, 'step' => 1, 'help' => 'Introduce multiplicative noise.'],
                ],
            ],
            [
                'slug' => 'edges',
                'title' => 'Edges',
                'tone' => 'text-sky-300',
                'items' => [
                    ['key' => 'canny', 'label' => 'Canny', 'default' => 100, 'min' => 1, 'max' => 255, 'step' => 1, 'help' => 'Detect strong contours with a low threshold control.'],
                    ['key' => 'sobel', 'label' => 'Sobel', 'default' => 0, 'help' => 'Extract gradients using Sobel operators.'],
                    ['key' => 'laplacian', 'label' => 'Laplacian', 'default' => 0, 'help' => 'Highlight rapid intensity changes.'],
                ],
            ],
            [
                'slug' => 'morphology',
                'title' => 'Morphology',
                'tone' => 'text-lime-300',
                'items' => [
                    ['key' => 'erode', 'label' => 'Erode', 'default' => 3, 'min' => 1, 'max' => 31, 'step' => 2, 'help' => 'Shrink bright structures.'],
                    ['key' => 'dilate', 'label' => 'Dilate', 'default' => 3, 'min' => 1, 'max' => 31, 'step' => 2, 'help' => 'Expand bright structures.'],
                    ['key' => 'open', 'label' => 'Open', 'default' => 3, 'min' => 1, 'max' => 31, 'step' => 2, 'help' => 'Remove small foreground artifacts.'],
                    ['key' => 'close', 'label' => 'Close', 'default' => 3, 'min' => 1, 'max' => 31, 'step' => 2, 'help' => 'Fill tiny gaps and holes.'],
                ],
            ],
            [
                'slug' => 'enhancement',
                'title' => 'Enhancement',
                'tone' => 'text-violet-300',
                'items' => [
                    ['key' => 'brightness', 'label' => 'Brightness', 'default' => 25, 'min' => -100, 'max' => 100, 'step' => 1, 'help' => 'Raise or lower the overall luminance.'],
                    ['key' => 'contrast', 'label' => 'Contrast', 'default' => 20, 'min' => -100, 'max' => 100, 'step' => 1, 'help' => 'Expand or compress the tonal separation.'],
                    ['key' => 'gamma', 'label' => 'Gamma', 'default' => 1.2, 'min' => 0.1, 'max' => 5, 'step' => 0.1, 'help' => 'Apply gamma correction for tone mapping.'],
                    ['key' => 'clahe', 'label' => 'CLAHE', 'default' => 0, 'help' => 'Local contrast enhancement on luminance.'],
                    ['key' => 'resize', 'label' => 'Resize', 'default' => 100, 'min' => 20, 'max' => 200, 'step' => 10, 'help' => 'Scale the image using a percentage value.'],
                    ['key' => 'compress', 'label' => 'Compress', 'default' => 85, 'min' => 5, 'max' => 100, 'step' => 1, 'help' => 'Simulate JPEG compression quality.'],
                ],
            ],
            [
                'slug' => 'segmentation',
                'title' => 'Segmentation',
                'tone' => 'text-orange-300',
                'items' => [
                    ['key' => 'kmeans', 'label' => 'K-Means', 'default' => 4, 'min' => 2, 'max' => 10, 'step' => 1, 'help' => 'Cluster colors into a limited number of regions.'],
                    ['key' => 'watershed', 'label' => 'Watershed', 'default' => 0, 'help' => 'Separate touching objects using marker-based segmentation.'],
                ],
            ],
        ];
    }

    public function download($name)
    {
        $path = public_path(self::PROCESSED_DIR . '/' . $name);

        abort_unless(File::exists($path), 404);

        return response()->download($path);
    }
}
