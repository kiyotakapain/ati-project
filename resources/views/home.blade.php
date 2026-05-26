@extends('layouts.app')

@section('content')
@php
    /**
     * `operationGroups` and `stats` are injected from the controller.
     * If they are missing, ensure the controller provides them; the
     * view will still work if variables are already defined.
     */
    $operationGroups = $operationGroups ?? [];
    $stats = $stats ?? [];
@endphp

<div class="studio-shell min-h-screen text-slate-100">
    <div class="absolute inset-0 -z-10 overflow-hidden">
        <div class="studio-glow studio-glow-one"></div>
        <div class="studio-glow studio-glow-two"></div>
        <div class="studio-grid"></div>
    </div>

    <main class="mx-auto flex w-full max-w-7xl flex-col gap-8 px-4 py-6 sm:px-6 lg:px-8 lg:py-10">
        @if(count($operationGroups) > 1)
        <section class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="glass-panel rounded-[2rem] p-6 sm:p-8">
                <div class="inline-flex items-center gap-2 rounded-full border border-cyan-400/20 bg-cyan-400/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.3em] text-cyan-200">
                    4th Year AI Engineering
                </div>

                <h1 class="mt-6 max-w-3xl font-display text-4xl font-bold leading-tight text-white sm:text-5xl lg:text-6xl">
                    Image Analysis and Processing Mini-Project
                </h1>

                <p class="mt-4 max-w-2xl text-base leading-7 text-slate-300 sm:text-lg">
                    Load an image from disk, preview it instantly, then run thresholding, histogram operations, filtering, noise injection, edge detection, segmentation, morphology, enhancement, and compression from one interface.
                </p>

                <div class="mt-8 grid gap-3 sm:grid-cols-3">
                    @foreach ($stats as $item)
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-4 backdrop-blur-xl">
                            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">{{ $item['label'] }}</p>
                            <p class="mt-2 text-sm font-semibold text-white">{{ $item['value'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="glass-panel rounded-[2rem] p-6 sm:p-8">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Project Focus</p>
                        <h2 class="mt-2 font-display text-2xl font-bold text-white">Interactive image studio</h2>
                    </div>
                    <span class="rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3 py-1 text-xs font-semibold text-emerald-200">Laravel + Python</span>
                </div>

                <div class="mt-6 space-y-4 text-sm leading-6 text-slate-300">
                    <p>Use the upload panel to send an image to the server, then choose an operation from the stack of tools on the left. The processed image is returned instantly and can be downloaded as a new file.</p>
                    <p>The Python side is modular and easy to extend, so you can add more lecture operations or your own ideas without changing the whole application.</p>
                </div>
            </div>
        </section>
        @endif

        @if(request()->routeIs('tools') && request()->route('slug'))
            @php $group = $operationGroups[0] ?? null; @endphp
            <section class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
                <div class="glass-panel rounded-[2rem] p-6 sm:p-8">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Category</p>
                            <h2 class="mt-2 font-display text-2xl font-bold text-white">{{ $group['title'] ?? 'Tools' }}</h2>
                        </div>
                        <div class="text-sm text-slate-300">{{ count($group['items'] ?? []) }} tools</div>
                    </div>

                    <form class="mt-6 flex w-full flex-col gap-3 sm:w-auto sm:flex-row" data-upload-form>
                        <label class="group flex cursor-pointer items-center justify-between gap-3 rounded-2xl border border-dashed border-cyan-400/30 bg-slate-950/40 px-4 py-3 text-sm text-slate-300 transition hover:border-cyan-300 hover:bg-slate-950/60 sm:min-w-72">
                            <span class="truncate" data-file-label>Choose an image</span>
                            <span class="rounded-full bg-cyan-400/15 px-3 py-1 text-xs font-semibold text-cyan-200">Browse</span>
                            <input class="hidden" type="file" name="image" accept="image/*" data-image-input>
                        </label>

                        <button class="rounded-2xl bg-cyan-400 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-cyan-300 disabled:cursor-not-allowed disabled:opacity-40" type="submit">
                            Upload
                        </button>
                    </form>

                    <div class="mt-6 rounded-[1.75rem] border border-white/10 bg-slate-950/40 p-5">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Selected operation</p>
                                <h3 class="mt-2 font-display text-2xl font-bold text-white" data-operation-title>Grayscale</h3>
                                <p class="mt-2 max-w-xl text-sm leading-6 text-slate-300" data-operation-help>Convert the image to a monochrome view.</p>
                            </div>

                            <div class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-right">
                                <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Parameter</p>
                                <p class="mt-2 text-sm font-semibold text-white" data-operation-key>grayscale</p>
                            </div>
                        </div>

                        <div class="mt-5 grid gap-4 md:grid-cols-2">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-200" for="operation-value" data-value-label>Operation value</label>
                                <input id="operation-value" class="w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-white outline-none transition placeholder:text-slate-500 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20" type="number" value="0" data-value-input>
                                <p class="text-xs text-slate-400" data-value-help>No numeric parameter required for this operation.</p>
                            </div>

                            <div class="flex flex-col gap-3 md:items-end md:justify-end">
                                <button class="rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10 disabled:cursor-not-allowed disabled:opacity-40" type="button" data-reset-button>
                                    Reset previews
                                </button>
                                <button class="rounded-2xl bg-gradient-to-r from-cyan-400 to-emerald-400 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:from-cyan-300 hover:to-emerald-300 disabled:cursor-not-allowed disabled:opacity-40" type="button" data-process-button disabled>
                                    Run processing
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 grid gap-4">
                        <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-5 backdrop-blur-xl">
                            <div class="mt-4 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                                @foreach ($group['items'] ?? [] as $item)
                                    <button
                                        class="operation-button rounded-2xl border border-white/10 bg-slate-950/55 px-4 py-3 text-left transition hover:-translate-y-0.5 hover:border-cyan-300/50 hover:bg-slate-950/80"
                                        type="button"
                                        data-operation="{{ $item['key'] }}"
                                        data-label="{{ $item['label'] }}"
                                        data-default="{{ $item['default'] }}"
                                        data-min="{{ $item['min'] ?? '' }}"
                                        data-max="{{ $item['max'] ?? '' }}"
                                        data-step="{{ $item['step'] ?? '' }}"
                                        data-help="{{ $item['help'] }}"
                                    >
                                        <span class="block text-sm font-semibold text-white">{{ $item['label'] }}</span>
                                        <span class="mt-1 block text-xs leading-5 text-slate-400">{{ $item['help'] }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass-panel rounded-[2rem] p-6 sm:p-8">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Live previews</p>
                            <h2 class="mt-2 font-display text-2xl font-bold text-white">Preview & process</h2>
                        </div>
                    </div>

                    <div class="mt-6 space-y-5">
                        <figure class="preview-card">
                            <div class="preview-head">
                                <h3>Original image</h3>
                                <span data-original-status>Waiting for upload</span>
                            </div>
                            <div class="preview-body">
                                <img alt="Original preview" data-original-preview>
                                <div class="preview-placeholder" data-original-placeholder>
                                    Upload an image to see the source preview here.
                                </div>
                            </div>
                        </figure>

                        <figure class="preview-card">
                            <div class="preview-head">
                                <h3>Processed image</h3>
                                <span data-processed-status>Waiting for processing</span>
                            </div>
                            <div class="preview-body">
                                <img alt="Processed preview" data-processed-preview>
                                <div class="preview-placeholder" data-processed-placeholder>
                                    Run an operation to render the output preview here.
                                </div>
                            </div>
                        </figure>

                        <div class="rounded-[1.5rem] border border-white/10 bg-slate-950/45 p-4 text-sm text-slate-300" data-status>
                            Upload an image, choose a processing tool, and run the operation.
                        </div>
                    </div>
                </div>
            </section>
        @else
            <section class="grid gap-6">
                <div class="glass-panel rounded-[2rem] p-6 sm:p-8">
                    <h2 class="font-display text-2xl font-bold text-white">Explore tools by category</h2>
                    <p class="mt-2 text-sm text-slate-300">Choose a category to view related image processing tools and examples.</p>

                    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <a href="{{ route('tools', ['slug' => 'core']) }}" class="card-link rounded-2xl border border-white/10 bg-white/5 p-4 hover:bg-white/10">
                            <h3 class="font-semibold text-white">Basics</h3>
                            <p class="mt-1 text-xs text-slate-400">Load, grayscale, invert, sepia</p>
                        </a>

                        <a href="{{ route('tools', ['slug' => 'histogram']) }}" class="card-link rounded-2xl border border-white/10 bg-white/5 p-4 hover:bg-white/10">
                            <h3 class="font-semibold text-white">Histogram</h3>
                            <p class="mt-1 text-xs text-slate-400">Equalize, stretch, histogram analysis</p>
                        </a>

                        <a href="{{ route('tools', ['slug' => 'thresholding']) }}" class="card-link rounded-2xl border border-white/10 bg-white/5 p-4 hover:bg-white/10">
                            <h3 class="font-semibold text-white">Thresholding</h3>
                            <p class="mt-1 text-xs text-slate-400">Binary, Otsu, adaptive methods</p>
                        </a>

                        <a href="{{ route('tools', ['slug' => 'noise']) }}" class="card-link rounded-2xl border border-white/10 bg-white/5 p-4 hover:bg-white/10">
                            <h3 class="font-semibold text-white">Noise</h3>
                            <p class="mt-1 text-xs text-slate-400">Add Gaussian, salt & pepper, speckle</p>
                        </a>

                        <a href="{{ route('tools', ['slug' => 'filtering']) }}" class="card-link rounded-2xl border border-white/10 bg-white/5 p-4 hover:bg-white/10">
                            <h3 class="font-semibold text-white">Filtering</h3>
                            <p class="mt-1 text-xs text-slate-400">Blur, median, bilateral, sharpen</p>
                        </a>

                        <a href="{{ route('tools', ['slug' => 'segmentation']) }}" class="card-link rounded-2xl border border-white/10 bg-white/5 p-4 hover:bg-white/10">
                            <h3 class="font-semibold text-white">Segmentation</h3>
                            <p class="mt-1 text-xs text-slate-400">K-means, watershed and region split</p>
                        </a>

                        <a href="{{ route('tools', ['slug' => 'morphology']) }}" class="card-link rounded-2xl border border-white/10 bg-white/5 p-4 hover:bg-white/10">
                            <h3 class="font-semibold text-white">Morphology</h3>
                            <p class="mt-1 text-xs text-slate-400">Erode, dilate, open, close</p>
                        </a>

                        <a href="{{ route('tools', ['slug' => 'enhancement']) }}" class="card-link rounded-2xl border border-white/10 bg-white/5 p-4 hover:bg-white/10">
                            <h3 class="font-semibold text-white">Enhancement</h3>
                            <p class="mt-1 text-xs text-slate-400">Brightness, contrast, gamma, CLAHE</p>
                        </a>
                    </div>
                </div>
            </section>
        @endif
    </main>
</div>
@endsection
