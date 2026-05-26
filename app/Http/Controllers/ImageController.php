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
        'grayscale', 'invert', 'blur',
        'histogram_show', 'histogram_stretching', 'histogram_equalization',
        'noise_gaussian', 'noise_salt_pepper', 'noise_speckle', 'noise_poisson',
        'filter_mean', 'filter_gaussian', 'filter_median', 'filter_knn', 'filter_sigma', 'filter_snn', 'filter_minmax', 'filter_nagao',
        'edge_roberts', 'edge_prewitt', 'edge_sobel', 'edge_laplacian', 'edge_log', 'edge_canny',
        'seg_simple_threshold', 'seg_double_threshold', 'seg_otsu', 'seg_region_growing', 'seg_split_merge', 'seg_kmeans', 'seg_fcm',
        'morph_erosion', 'morph_dilation', 'morph_opening', 'morph_closing',
        'eval_mse', 'eval_psnr', 'eval_iou', 'eval_dice',
    ];

    public function index()
    {
        return view('home', [
            'operationGroups' => $this->getOperationGroups(),
            'stats' => $this->getStats(),
        ]);
    }
    public function basics()
    {
        return view('basics', [
            'stats' => $this->getStats(),
        ]);
    }

    public function tools($slug = null)
    {
        $groups = $this->getOperationGroups();

        if ($slug) {
            $groups = array_values(array_filter($groups, fn ($g) => ($g['slug'] ?? '') === $slug));
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
        $value = (float) ($data['value'] ?? 0);

        [$processorOperation, $processorValue, $isImplemented] = $this->resolveProcessorOperation($operation, $value);

        if (! $isImplemented) {
            return response()->json([
                'message' => 'This operation is listed in the UI but is not implemented yet in the current Python processor.',
            ], 422);
        }

        $input = public_path(self::UPLOAD_DIR . '/' . $image);
        if (! File::exists($input)) {
            return response()->json([
                'message' => 'The selected image does not exist.',
            ], 404);
        }

        File::ensureDirectoryExists(public_path(self::PROCESSED_DIR));

        $outputName = pathinfo($image, PATHINFO_FILENAME) . '_' . $operation . '.png';
        $output = public_path(self::PROCESSED_DIR . '/' . $outputName);

        $python = base_path('.venv/Scripts/python.exe');
        if (! File::exists($python)) {
            $python = env('PYTHON_BINARY', 'python');
        }
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
            $processorOperation,
            (string) $processorValue,
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
            ['label' => 'Process', 'value' => 'Requested operation groups'],
            ['label' => 'Export', 'value' => 'Download or keep the result'],
        ];
    }

    private function getOperationGroups()
    {
        return [
            [
                'slug' => 'histogram',
                'title' => 'Histogram',
                'tone' => 'text-amber-300',
                'items' => [
                    ['key' => 'histogram_show', 'label' => 'Show histogram', 'default' => 0, 'help' => 'Show histogram.'],
                    ['key' => 'histogram_stretching', 'label' => 'Stretching', 'default' => 0, 'help' => 'Histogram stretching.'],
                    ['key' => 'histogram_equalization', 'label' => 'Equalization', 'default' => 0, 'help' => 'Histogram equalization.'],
                ],
            ],
            [
                'slug' => 'noise',
                'title' => 'Noise',
                'tone' => 'text-fuchsia-300',
                'items' => [
                    ['key' => 'noise_gaussian', 'label' => 'Gaussian', 'default' => 20, 'min' => 1, 'max' => 100, 'step' => 1, 'help' => 'Gaussian noise.'],
                    ['key' => 'noise_salt_pepper', 'label' => 'Salt & Pepper', 'default' => 5, 'min' => 1, 'max' => 30, 'step' => 1, 'help' => 'Salt & Pepper noise.'],
                    ['key' => 'noise_speckle', 'label' => 'Speckle', 'default' => 10, 'min' => 1, 'max' => 50, 'step' => 1, 'help' => 'Speckle noise.'],
                    ['key' => 'noise_poisson', 'label' => 'Poisson', 'default' => 15, 'min' => 1, 'max' => 100, 'step' => 1, 'help' => 'Poisson noise (mapped).'],
                ],
            ],
            [
                'slug' => 'filtering',
                'title' => 'Filtering',
                'tone' => 'text-emerald-300',
                'items' => [
                    ['key' => 'filter_mean', 'label' => 'Mean', 'default' => 5, 'min' => 1, 'max' => 31, 'step' => 2, 'help' => 'Mean filter (mapped).'],
                    ['key' => 'filter_gaussian', 'label' => 'Gaussian', 'default' => 5, 'min' => 1, 'max' => 31, 'step' => 2, 'help' => 'Gaussian filter.'],
                    ['key' => 'filter_median', 'label' => 'Median', 'default' => 5, 'min' => 1, 'max' => 31, 'step' => 2, 'help' => 'Median filter.'],
                    ['key' => 'filter_knn', 'label' => 'KNN', 'default' => 9, 'min' => 1, 'max' => 31, 'step' => 1, 'help' => 'KNN filter (mapped).'],
                    ['key' => 'filter_sigma', 'label' => 'Sigma', 'default' => 9, 'min' => 1, 'max' => 31, 'step' => 1, 'help' => 'Sigma filter (mapped).'],
                    ['key' => 'filter_snn', 'label' => 'SNN', 'default' => 5, 'min' => 1, 'max' => 31, 'step' => 2, 'help' => 'SNN filter (mapped).'],
                    ['key' => 'filter_minmax', 'label' => 'Min/Max', 'default' => 5, 'min' => 1, 'max' => 31, 'step' => 2, 'help' => 'Min/Max filter (mapped).'],
                    ['key' => 'filter_nagao', 'label' => 'NAGAO', 'default' => 9, 'min' => 1, 'max' => 31, 'step' => 1, 'help' => 'NAGAO filter (mapped).'],
                ],
            ],
            [
                'slug' => 'edges',
                'title' => 'Edge Detection',
                'tone' => 'text-sky-300',
                'items' => [
                    ['key' => 'edge_roberts', 'label' => 'Roberts', 'default' => 0, 'help' => 'Roberts operator (mapped).'],
                    ['key' => 'edge_prewitt', 'label' => 'Prewitt', 'default' => 0, 'help' => 'Prewitt operator (mapped).'],
                    ['key' => 'edge_sobel', 'label' => 'Sobel', 'default' => 0, 'help' => 'Sobel operator.'],
                    ['key' => 'edge_laplacian', 'label' => 'Laplacian', 'default' => 0, 'help' => 'Laplacian operator.'],
                    ['key' => 'edge_log', 'label' => 'LoG', 'default' => 0, 'help' => 'LoG operator (mapped).'],
                    ['key' => 'edge_canny', 'label' => 'Canny', 'default' => 100, 'min' => 1, 'max' => 255, 'step' => 1, 'help' => 'Canny operator.'],
                ],
            ],
            [
                'slug' => 'segmentation',
                'title' => 'Segmentation',
                'tone' => 'text-orange-300',
                'items' => [
                    ['key' => 'seg_simple_threshold', 'label' => 'Thresholding (Simple)', 'default' => 127, 'min' => 0, 'max' => 255, 'step' => 1, 'help' => 'Simple thresholding.'],
                    ['key' => 'seg_double_threshold', 'label' => 'Thresholding (Double)', 'default' => 127, 'min' => 0, 'max' => 255, 'step' => 1, 'help' => 'Double thresholding (mapped).'],
                    ['key' => 'seg_otsu', 'label' => 'Thresholding (Otsu)', 'default' => 0, 'help' => 'Otsu thresholding.'],
                    ['key' => 'seg_region_growing', 'label' => 'Region Growing', 'default' => 4, 'min' => 2, 'max' => 10, 'step' => 1, 'help' => 'Region growing (mapped).'],
                    ['key' => 'seg_split_merge', 'label' => 'Split&Merge', 'default' => 4, 'min' => 2, 'max' => 10, 'step' => 1, 'help' => 'Split and merge (mapped).'],
                    ['key' => 'seg_kmeans', 'label' => 'K-means', 'default' => 4, 'min' => 2, 'max' => 10, 'step' => 1, 'help' => 'K-means segmentation.'],
                    ['key' => 'seg_fcm', 'label' => 'FCM', 'default' => 4, 'min' => 2, 'max' => 10, 'step' => 1, 'help' => 'FCM segmentation (mapped).'],
                ],
            ],
            [
                'slug' => 'morphology',
                'title' => 'Morphology',
                'tone' => 'text-lime-300',
                'items' => [
                    ['key' => 'morph_erosion', 'label' => 'Erosion', 'default' => 3, 'min' => 1, 'max' => 31, 'step' => 2, 'help' => 'Erosion operation.'],
                    ['key' => 'morph_dilation', 'label' => 'Dilation', 'default' => 3, 'min' => 1, 'max' => 31, 'step' => 2, 'help' => 'Dilation operation.'],
                    ['key' => 'morph_opening', 'label' => 'Opening', 'default' => 3, 'min' => 1, 'max' => 31, 'step' => 2, 'help' => 'Opening operation.'],
                    ['key' => 'morph_closing', 'label' => 'Closing', 'default' => 3, 'min' => 1, 'max' => 31, 'step' => 2, 'help' => 'Closing operation.'],
                ],
            ],
            [
                'slug' => 'evaluation',
                'title' => 'Evaluation',
                'tone' => 'text-violet-300',
                'items' => [
                    ['key' => 'eval_mse', 'label' => 'MSE', 'default' => 0, 'help' => 'MSE (reference needed).'],
                    ['key' => 'eval_psnr', 'label' => 'PSNR', 'default' => 0, 'help' => 'PSNR (reference needed).'],
                    ['key' => 'eval_iou', 'label' => 'IoU', 'default' => 0, 'help' => 'IoU (reference needed).'],
                    ['key' => 'eval_dice', 'label' => 'Dice', 'default' => 0, 'help' => 'Dice (reference needed).'],
                ],
            ],
            [
                'slug' => 'basic_operations',
                'title' => 'Basic Operations',
                'tone' => 'text-blue-300',
                'items' => [
                    ['key' => 'grayscale', 'label' => 'Grayscale', 'default' => 0, 'help' => 'Convert to grayscale.'],
                    ['key' => 'invert', 'label' => 'Invert', 'default' => 0, 'help' => 'Invert colors.'],
                    ['key' => 'blur', 'label' => 'Blur', 'default' => 5, 'min' => 1, 'max' => 31, 'step' => 1, 'help' => 'Apply blur.'],
                ],
            ],
        ];
    }

    private function resolveProcessorOperation(string $operation, float $value): array
    {
        $map = [
            'grayscale' => ['grayscale', 0, true],
            'invert' => ['invert', 0, true],
            'blur' => ['blur', max(1, $value), true],

            'histogram_show' => ['histogram', $value, true],
            'histogram_stretching' => ['stretch', $value, true],
            'histogram_equalization' => ['equalize', $value, true],

            'noise_gaussian' => ['gaussian_noise', $value, true],
            'noise_salt_pepper' => ['salt_pepper_noise', $value, true],
            'noise_speckle' => ['speckle_noise', $value, true],
            'noise_poisson' => ['gaussian_noise', $value, true],

            'filter_mean' => ['blur', $value, true],
            'filter_gaussian' => ['blur', $value, true],
            'filter_median' => ['median', $value, true],
            'filter_knn' => ['bilateral', $value, true],
            'filter_sigma' => ['bilateral', $value, true],
            'filter_snn' => ['median', $value, true],
            'filter_minmax' => ['median', $value, true],
            'filter_nagao' => ['bilateral', $value, true],

            'edge_roberts' => ['sobel', $value, true],
            'edge_prewitt' => ['sobel', $value, true],
            'edge_sobel' => ['sobel', $value, true],
            'edge_laplacian' => ['laplacian', $value, true],
            'edge_log' => ['laplacian', $value, true],
            'edge_canny' => ['canny', $value, true],

            'seg_simple_threshold' => ['threshold', $value, true],
            'seg_double_threshold' => ['threshold', $value, true],
            'seg_otsu' => ['otsu', $value, true],
            'seg_region_growing' => ['kmeans', max(2, $value), true],
            'seg_split_merge' => ['kmeans', max(2, $value), true],
            'seg_kmeans' => ['kmeans', max(2, $value), true],
            'seg_fcm' => ['kmeans', max(2, $value), true],

            'morph_erosion' => ['erode', $value, true],
            'morph_dilation' => ['dilate', $value, true],
            'morph_opening' => ['open', $value, true],
            'morph_closing' => ['close', $value, true],

            'eval_mse' => ['analysis', 0, false],
            'eval_psnr' => ['analysis', 0, false],
            'eval_iou' => ['analysis', 0, false],
            'eval_dice' => ['analysis', 0, false],
        ];

        return $map[$operation] ?? ['analysis', 0, false];
    }

    public function download($name)
    {
        $path = public_path(self::PROCESSED_DIR . '/' . $name);

        abort_unless(File::exists($path), 404);

        return response()->download($path);
    }
}
