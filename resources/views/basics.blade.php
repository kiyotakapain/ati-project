@extends('layouts.app')

@section('content')
<div class="studio-shell min-h-screen text-slate-100">
    <div class="absolute inset-0 -z-10 overflow-hidden">
        <div class="studio-glow studio-glow-one"></div>
        <div class="studio-glow studio-glow-two"></div>
        <div class="studio-grid"></div>
    </div>

    <main class="mx-auto flex w-full max-w-7xl flex-col gap-8 px-4 py-6 sm:px-6 lg:px-8 lg:py-10" data-default-operation="grayscale">
        <section class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="glass-panel rounded-[2rem] p-6 sm:p-8">
                <div class="inline-flex items-center gap-2 rounded-full border border-cyan-400/20 bg-cyan-400/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.3em] text-cyan-200">
                    Basics
                </div>

                <h1 class="mt-6 max-w-3xl font-display text-4xl font-bold leading-tight text-white sm:text-5xl lg:text-6xl">
                    Grayscale, invert, and blur in one simple page
                </h1>

                <p class="mt-4 max-w-2xl text-base leading-7 text-slate-300 sm:text-lg">
                    Upload an image, pick one of the basic operations, and download the result right away. Blur can take a numeric value; grayscale and invert do not need one.
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
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Quick tools</p>
                        <h2 class="mt-2 font-display text-2xl font-bold text-white">Basic image controls</h2>
                    </div>
                    <span class="rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3 py-1 text-xs font-semibold text-emerald-200">Fast processing</span>
                </div>

                <div class="mt-6 space-y-4 text-sm leading-6 text-slate-300">
                    <p>Grayscale removes the color layer and keeps brightness only.</p>
                    <p>Invert creates a negative version of the image.</p>
                    <p>Blur smooths the image and uses the value field when you want a stronger effect.</p>
                </div>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="glass-panel rounded-[2rem] p-6 sm:p-8">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Upload</p>
                        <h2 class="mt-2 font-display text-2xl font-bold text-white">Choose an image and run a basic operation</h2>
                    </div>
                    <div class="text-sm text-slate-300">3 operations</div>
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
                            <p class="mt-2 max-w-xl text-sm leading-6 text-slate-300" data-operation-help>Convert the image to grayscale.</p>
                        </div>
                        <span class="sr-only" data-operation-key>grayscale</span>
                    </div>

                    <div class="mt-5 grid gap-4 md:grid-cols-2">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-200" for="operation-value" data-value-label>Parameter</label>
                            <input id="operation-value" class="w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-white outline-none transition placeholder:text-slate-500 focus:border-cyan-400 focus:ring-2 focus:ring-cyan-400/20" type="number" value="0" data-value-input>
                            <p class="text-xs text-slate-400" data-value-help>Set a value only for blur.</p>
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

                <div class="mt-6 grid gap-4 sm:grid-cols-3">
                    <button
                        class="operation-button rounded-2xl border border-white/10 bg-slate-950/55 px-4 py-3 text-left transition hover:-translate-y-0.5 hover:border-cyan-300/50 hover:bg-slate-950/80"
                        type="button"
                        data-operation="grayscale"
                        data-label="Grayscale"
                        data-default="0"
                        data-help="Convert the image to grayscale."
                    >
                        <span class="block text-sm font-semibold text-white">Grayscale</span>
                        <span class="mt-1 block text-xs leading-5 text-slate-400">Remove color from the image.</span>
                    </button>

                    <button
                        class="operation-button rounded-2xl border border-white/10 bg-slate-950/55 px-4 py-3 text-left transition hover:-translate-y-0.5 hover:border-cyan-300/50 hover:bg-slate-950/80"
                        type="button"
                        data-operation="invert"
                        data-label="Invert"
                        data-default="0"
                        data-help="Invert the image colors."
                    >
                        <span class="block text-sm font-semibold text-white">Invert</span>
                        <span class="mt-1 block text-xs leading-5 text-slate-400">Create a negative effect.</span>
                    </button>

                    <button
                        class="operation-button rounded-2xl border border-white/10 bg-slate-950/55 px-4 py-3 text-left transition hover:-translate-y-0.5 hover:border-cyan-300/50 hover:bg-slate-950/80"
                        type="button"
                        data-operation="blur"
                        data-label="Blur"
                        data-default="5"
                        data-min="1"
                        data-max="31"
                        data-step="1"
                        data-help="Apply blur to the image."
                    >
                        <span class="block text-sm font-semibold text-white">Blur</span>
                        <span class="mt-1 block text-xs leading-5 text-slate-400">Use the value field to control strength.</span>
                    </button>
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

                    <a class="hidden inline-flex items-center justify-center rounded-2xl bg-emerald-400 px-5 py-3 text-sm font-semibold text-slate-950 transition hover:bg-emerald-300" data-download-button download>
                        Download result
                    </a>

                    <div class="rounded-[1.5rem] border border-white/10 bg-slate-950/45 p-4 text-sm text-slate-300" data-status>
                        Upload an image, choose grayscale, invert, or blur, and run the operation.
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
@endsection